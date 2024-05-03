<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTransaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaction_id'; 

    protected $fillable = ['transaction_id', 'sender_id', 'user_receiver_id', 'amount'];

    public function user()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'user_receiver_id');
    }
}
