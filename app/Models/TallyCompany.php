<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TallyCompany extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function groups()
{
    return $this->hasMany(TallyGroup::class);
}

public function ledgers()
{
    return $this->hasMany(TallyLedger::class);
}

}
