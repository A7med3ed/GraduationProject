<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardTransaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaction_id'; 

    protected $fillable = ['transaction_id', 'sender_id' ,'card_number', 'card_name', 'amount'];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
