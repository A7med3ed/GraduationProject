<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'Service_id';
    
    protected $fillable = [
        'Service_id','ServiceProviderID', 'Support_Contact_Number', 'Donation_Purpose', 'Address','extra_fields','icon'
    ];

    protected $casts = [
        'extra_fields' => 'array', // Cast extra_fields as array
    ];

    public function Serviceprovider()
    {
        return $this->belongsTo(ServiceProvider::class, 'ServiceProviderID');
    }

    public function customerData(): HasMany
    {
        return $this->hasMany(Donation_CustomerData::class, 'service_id', 'Service_id');
    }


}