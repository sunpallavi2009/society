<?php

namespace App\Http\Controllers\SuperAdmin;

use Carbon\Carbon;
use App\Models\Voucher;
use App\Models\TallyGroup;
use App\Models\TallyLedger;
use App\Models\TallyCompany;
use App\Models\VoucherEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class LedgerController extends Controller
{
    public function upload(Request $request)
    {
        // Ensure file is present and valid
        $request->validate([
            'uploadFile' => 'required|file',
        ]);

        // Process the uploaded file
        $file = $request->file('uploadFile');
        $success = 0;

        $fp = fopen($file->getPathname(), 'rb');
        if ($fp !== false) {
            while (($line = fgets($fp)) !== false) {
                try {
                    $record_json = json_decode($line, true);

                    // Insert into database based on record type
                    if (isset($record_json["t"])) {
                        switch ($record_json["t"]) {
                            case "company":
                                $company_guid = $this->insertOrUpdateCompany($record_json);
                                break;
                            case "group":
                                $this->insertOrUpdateGroup($record_json);
                                break;
                            case "l":
                                $this->insertOrUpdateLedger($record_json, $company_guid ?? null);
                                break;
                            default:
                                throw new \Exception("Invalid record type: " . $record_json["t"]);
                        }
                        $success++;
                    } else {
                        throw new \Exception("Record type not found");
                    }
                } catch (\Exception $e) {
                    // Handle exceptions
                    // Optionally log the error for debugging
                    Log::error('Error processing record: ' . $e->getMessage());
                    Log::error('Record data: ' . $line);
                }
            }
            fclose($fp);
        } else {
            throw new \Exception("Failed to open file: " . $file->getPathname());
        }

        return response()->json(['success' => $success]);
    }

    private function insertOrUpdateCompany($record_json)
    {
        $company = TallyCompany::updateOrCreate(
            ['guid' => $record_json["guid"]],
            [
                'name' => $record_json["name"],
                'address1' => $record_json["address1"],
                'address2' => $record_json["address2"],
                'fax_number' => $record_json["fax_number"],
                'email' => $record_json["email"],
                'mobile_number' => $record_json["mobile_number"],
                'phone_number' => $record_json["phone_number"],
                'website' => $record_json["website"],
                'company_number' => $record_json["company_number"],
            ]
        );

        return $company->guid;
    }

    private function insertOrUpdateGroup($record_json)
    {
        TallyGroup::updateOrCreate(
            ['guid' => $record_json["guid"]],
            [
                'name' => $record_json["name"],
                'parent' => $record_json["parent"],
                'alter_id' => $record_json["alterid"],
            ]
        );
    }

    private function insertOrUpdateLedger($record_json, $company_guid)
    {
        $ledger = TallyLedger::updateOrCreate(
            ['guid' => $record_json["g"]],
            [
                'name' => $record_json["n"],
                'alias1' => $record_json["a1"],
                'alias2' => $record_json["a2"],
                'parent' => $record_json["p"],
                'address' => $record_json["a"],
                'alter_id' => $record_json["ai"],
                'note' => $record_json["nt"],
                'primary_group' => $record_json["pg"],
                'previous_year_balance' => $record_json["pb"],
                'this_year_balance' => $record_json["tb"],
                'email' => $record_json["e"],
                'mobile' => $record_json["m"],
                'phone' => $record_json["c"],
                'xml' => json_encode((object)$record_json["x"]),
                'company_guid' => $company_guid,
            ]
        );

        // Process XML data for vouchers and voucher entries
        if (isset($record_json["x"])) {
            $this->processVoucherData($ledger->name,$ledger->guid, $company_guid, $record_json["x"]);
        }
    }

    private function processVoucherData($ledger_name, $ledger_guid, $company_guid, $xmlData)
    {
        foreach ($xmlData as $voucher) {
            try {
                // Extract voucher details
                $voucherData = [
                    'ledger_guid' => $ledger_guid,
                    'company_guid' => $company_guid,
                    'json' => json_encode($voucher), // Store the entire voucher JSON data
                ];
    
                // Decode the JSON and extract specific values
                $decodedVoucher = json_decode($voucherData['json'], true);
    
                // Calculate the financial year
                $financialYear = $this->getFinancialYear($decodedVoucher["DATE"]);
    
                // Create new Voucher instance
                $newVoucher = new Voucher($voucherData);
                $newVoucher->financial_year = $financialYear;
    
                if (isset($decodedVoucher["VNO"])) {
                    $newVoucher->voucher_number = $decodedVoucher["VNO"];
                }
                if (isset($decodedVoucher["DATE"])) {
                    $newVoucher->voucher_date = Carbon::parse($decodedVoucher["DATE"])->format('Y-m-d');
                }
                if (isset($decodedVoucher["TYPE"])) {
                    $newVoucher->type = $decodedVoucher["TYPE"];
                }
                if (isset($decodedVoucher["NAR"])) {
                    $newVoucher->narration = $decodedVoucher["NAR"];
                }
                if (isset($decodedVoucher["AMT"])) {
                    $newVoucher->amount = $decodedVoucher["AMT"];
                }
                if (isset($decodedVoucher["ACC"])) {
                    $newVoucher->credit_ledger = $decodedVoucher["ACC"];
                }
                if (isset($decodedVoucher["BAL"])) {
                    $newVoucher->balance_amount = $decodedVoucher["BAL"];
                }
                if (isset($decodedVoucher["IDATE"])) {
                    $newVoucher->instrument_date = Carbon::parse($decodedVoucher["IDATE"])->format('Y-m-d');
                }
                if (isset($decodedVoucher["INO"])) {
                    $newVoucher->instrument_number = $decodedVoucher["INO"];
                }
                if (isset($decodedVoucher["IAMT"])) {
                    $newVoucher->instrument_amount = $decodedVoucher["IAMT"];
                }
                if (isset($decodedVoucher["ITYPE"])) {
                    $newVoucher->instrument_type = $decodedVoucher["ITYPE"];
                }
    
                // Save the new Voucher
                // Log the BD map for debugging
                Log::info('Inserting Voucher: ' . $newVoucher->voucher_number);
                $status = $newVoucher->save();
                Log::info('Status: ' . ($status ? 'success' : 'failure'));
    
                // Process BD map if the voucher type is 'Bill' or 'Sale'
                $bd_map = [];
                $amount = isset($decodedVoucher["AMT"]) ? $decodedVoucher["AMT"] : 0;
                $name = $ledger_name;
    
                switch (strtolower($decodedVoucher['TYPE'])) {
                    case "bill":
                    case "sale":
                    case "bills":
                    case "jrnl":
                    case "journal":
                        if (isset($decodedVoucher['BD'])) {
                            $bd_jsonarray = $decodedVoucher['BD'];
                            foreach ($bd_jsonarray as $item) {
                                foreach ($item as $key => $value) {
                                    $bd_map[$key] = $value;
                                }
                            }
                        } else {
                            $bd_map[$decodedVoucher['ACC']] = -$amount;
                        }
                        $bd_map[$name] = $amount;
                        break;
                    default:
                        $bd_map[$name] = $amount;
                        $bd_map[$decodedVoucher['ACC']] = -$amount;
                }
    
                // Log the BD map for debugging
                // Log::info('BD Map: ', $bd_map);
    
                // Insert the BD map into the VoucherEntries table
                foreach ($bd_map as $key => $value) {
                    $entry_type = $value < 0 ? "debit" : "credit";
    
                    VoucherEntry::create([
                        'voucher_id' => $newVoucher->id,
                        'ledger' => $key,
                        'amount' => $value,
                        'account' => $decodedVoucher["ACC"], // Store the account value
                        'type' => $decodedVoucher["TYPE"], // Store the type value
                        'narration' => $decodedVoucher["NAR"] ?? null, // Store the narration value
                        'entry_type' => $entry_type,
                    ]);
                }
            } catch (\Exception $e) {
                // Handle exceptions
                // Optionally log the error for debugging
                Log::error('Error processing voucher: ' . $e->getMessage());
                Log::error('Voucher data: ' . json_encode($voucher));
            }
        }
    }
    

    private function getFinancialYear($date) {
        // Convert date string to DateTime object
        $dateTime = new \DateTime($date);
    
        // Get the year and month from the DateTime object
        $year = (int) $dateTime->format('Y');
        $month = (int) $dateTime->format('m');
    
        // Determine the financial year based on the month
        if ($month >= 4) {
            // Financial year starts from April
            $startYear = $year;
            $endYear = $year + 1;
        } else {
            // Financial year starts from April of the previous year
            $startYear = $year - 1;
            $endYear = $year;
        }
    
        // Format the financial year range
        $financialYear = $startYear . '-' . $endYear;
    
        return $financialYear;
    }
    
}
