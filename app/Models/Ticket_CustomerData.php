<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Ticket_CustomerData extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'Ticket_CustomerData';
    protected $primaryKey = '_id';

    protected $fillable = ['user_id','service_id','number_of_Tickets','extra_fields'];

    protected $casts = [
        'extra_fields' => 'array', // Cast extra_fields as array
    ];

    public function ticketBooking()
    {
        return $this->belongsTo(TicketBooking::class, 'service_id', 'Service_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','user_id');
    }

    
}
