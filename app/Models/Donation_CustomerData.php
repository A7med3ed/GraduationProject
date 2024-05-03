<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Donation_CustomerData extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'Donation_CustomerData';
    protected $primaryKey = '_id';

    protected $fillable = ['user_id','service_id','Amount','extra_fields'];

    protected $casts = [
        'extra_fields' => 'array', // Cast extra_fields as array
    ];

    public function Donation()
    {
        return $this->belongsTo(Donation::class, 'service_id', 'Service_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','user_id');
    }

    
}
