<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'user_id';
    
    protected $fillable = ['account_number', 'account_holder_name', 'bank_name', 'account_type','user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cardDetails()
    {
        return $this->hasMany(CardDetails::class, 'user_id', 'user_id');
    }

}
