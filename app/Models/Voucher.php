<?php

namespace App\Models;

use App\Models\VoucherEntry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voucher extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function entries()
    {
        return $this->hasMany(VoucherEntry::class);
    }
    
    public function tallyLedger()
    {
        return $this->belongsTo(TallyLedger::class, 'ledger_guid', 'guid');
    }
}
