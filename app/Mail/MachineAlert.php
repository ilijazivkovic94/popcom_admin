<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MachineAlert extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */ 
    public function __construct($machine, $account){
        $this->machine = $machine;
        $this->account = $account;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        $subject = 'SaaS Generated Machine Alert - OFFLINE';

        return $this->subject($subject)->with(['kiosk' => $this->machine, 'account' => $this->account])->markdown('emails.machine_alert');
    }
}
