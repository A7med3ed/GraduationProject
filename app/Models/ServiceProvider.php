<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ServiceProvider extends Authenticatable 
{
    use HasApiTokens, HasFactory, Notifiable;

    
    protected $primaryKey = 'ServiceProviderID';

    protected $fillable = ['ServiceProviderID', 'organizationName', 'RepName', 'RepEmail','Password', 'RepPhoneNumber'];

    protected $hidden = [
        'id',
        'Password',
        'remember_token',
    ];

    public function TicketBooking()
    {
        return $this->hasMany(TicketBooking::class,'ServiceProviderID');
    }

    public function transactionsReceived()
    {
        return $this->hasMany(ProviderTransaction::class, 'ServiceProviderID');
    }

    public function providerWallet()
    {
        return $this->hasMany(ProviderWallet::class, 'ServiceProviderID');
    }

    public function ProviderBankTransaction()
    {
        return $this->hasMany(ProviderBankTransaction::class, 'ServiceProviderID');
    }
    
}
