<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderWallet extends Model
{
    use HasFactory;

    protected $primaryKey = 'ServiceProviderID';

    protected $fillable = [
        'ServiceProviderID',
        'Balance',
    ];

    public function provider()
    {
        return $this->belongsTo(ServiceProvider::class, 'ServiceProviderID');
    }
}
