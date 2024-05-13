<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CustomerNotification extends Notification
{
    use Queueable;

    private $type;
    private $sender;
    private $amount;
    private $massage;

    public function __construct($type,$sender,$amount)
    {
        $this->type=$type;
        $this->sender=$sender;
        $this->amount=$amount;
    }



    public function via($notifiable)
    {
        return ['database'];
    }



    public function toDatabase($notifiable)
    {
        if($this->sender==true){
        return [
            'massage'=>"you have successfully make".$this->type." Transaction by amount of :".$this->amount
        ];

        }
        
        else if($this->sender==false){
            return [
                'massage'=>"you have successfully received".$this->type." Transaction by amount of :".$this->amount."from user name:".auth()->user()->Name
            ];
        }

        else if($this->sender==null){
            return [
                'massage'=>"you are requested to".$this->type."by amount of :".$this->amount."to user name:".auth()->user()->Name
            ];
        }

    }
}
