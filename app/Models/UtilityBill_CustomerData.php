<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class UtilityBill_CustomerData extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'UtilityBill_CustomerData';
    protected $primaryKey = '_id';

    protected $fillable = ['user_id','service_id','Payment_Code','extra_fields'];

    protected $casts = [
        'extra_fields' => 'array', // Cast extra_fields as array
    ];

    public function UtilityBill()
    {
        return $this->belongsTo(UtilityBill::class, 'service_id', 'Service_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','user_id');
    }

    
}
