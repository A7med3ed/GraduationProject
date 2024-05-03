<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class CardDetails extends Model
{
    use HasFactory;

    protected $primaryKey = 'card_id';

    protected $fillable = ['card_id','card_holder_name','card_type', 'card_number', 'expiry_date', 'cvv','user_id'];

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'user_id', 'user_id');
    }

}
