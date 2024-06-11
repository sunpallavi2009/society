<?php

namespace App\Models;

use App\Models\Voucher;
use App\Models\TallyLedger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VoucherEntry extends Model
{
    use HasFactory;

    protected $guarded = [];

    
    
    public function tallyLedger()
    {
        return $this->belongsTo(TallyLedger::class, 'ledger_guid', 'guid');
    }

}
