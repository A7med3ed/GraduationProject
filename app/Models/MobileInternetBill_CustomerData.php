<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class MobileInternetBill_CustomerData extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'MobileInternetBill_CustomerData';
    protected $primaryKey = '_id';

    protected $fillable = ['user_id','service_id','Phone_Number', 'Amount','extra_fields'];

    protected $casts = [
        'extra_fields' => 'array', // Cast extra_fields as array
    ];

    public function MobileInternetBill()
    {
        return $this->belongsTo(MobileInternetBill::class, 'service_id', 'Service_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','user_id');
    }

    
}