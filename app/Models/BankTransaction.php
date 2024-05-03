<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaction_id'; 

    protected $fillable = ['transaction_id', 'sender_id', 'bank_account_number', 'bank_name', 'receiver_name', 'amount'];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

}
