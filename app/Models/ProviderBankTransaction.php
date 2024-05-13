<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderBankTransaction extends Model
{
    protected $primaryKey = 'transaction_id';
    protected $fillable = ['transaction_id','ServiceProviderID', 'bank_account_number', 'bank_name', 'receiver_name', 'amount'];



    public function ServiceProvider()
    {
        return $this->belongsTo(ServiceProvider::class, 'ServiceProviderID');
    }
}
