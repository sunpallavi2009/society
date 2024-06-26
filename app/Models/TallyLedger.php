<?php

namespace App\Models;

use App\Models\Voucher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TallyLedger extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(TallyCompany::class);
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class, 'ledger_guid', 'guid');
    }

    public function firstVoucherDate()
    {
        return $this->vouchers()->orderBy('voucher_date', 'asc')->first()->voucher_date ?? null;
    }

    public function voucher_entries()
    {
        return $this->hasMany(VoucherEntry::class, 'voucher_id', 'id');
    }
}
