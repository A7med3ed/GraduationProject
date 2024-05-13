<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class MobileInternetBill extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'Service_id';
    
    protected $fillable = ['Service_id','ServiceProviderID', 'Support_Contact_Number','Mobile_code' , 'extra_fields','icon','Type'];

    protected $casts = [
        'extra_fields' => 'array',// Cast details as array
    ];

    public function Serviceprovider()
    {
        return $this->belongsTo(ServiceProvider::class, 'ServiceProviderID');
    }

    public function customerData(): HasMany
    {
        return $this->hasMany(MobileInternetBill_CustomerData::class, 'service_id', 'Service_id');
    }

}