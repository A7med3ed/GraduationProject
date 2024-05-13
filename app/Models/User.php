<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable 
{
    use HasApiTokens, HasFactory, Notifiable;

    
    protected $primaryKey = 'user_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id','Name', 'Email', 'National_ID', 'Password', 'Phone_Number','Date_of_Birth'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'id',
        'Password',
        'remember_token',
    ];

    public function Cardtransactions()
    {
        return $this->hasMany(CardTransaction::class, 'user_id');
    }

    public function Banktransactions()
    {
        return $this->hasMany(BankTransaction::class, 'user_id');
    }

    public function Usertransactions()
    {
        return $this->hasMany(UserTransaction::class, 'user_id');
    }

    public function Providertransactions()
    {
        return $this->hasMany(ProviderTransaction::class, 'user_id');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class,'user_id');
    }

    public function bankAccount()
    {
        return $this->hasOne(BankAccount::class,'user_id');
    }

    public function Ticket_CustomerData(): HasMany
    {
        return $this->hasMany(Ticket_CustomerData::class, 'user_id','user_id');
    }

    public function Donation_CustomerData(): HasMany
    {
        return $this->hasMany(Donation_CustomerData::class, 'user_id','user_id');
    }

}
