<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderTransaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaction_id'; 

    protected $fillable = ['transaction_id', 'sender_id', 'provider_receiver_id', 'amount'];

    public function user()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function provider()
    {
        return $this->belongsTo(ServiceProvider::class, 'provider_receiver_id');
    }
}
