<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeactivateMachine extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */ 
    public function __construct($user,$kiosk){
        $this->user = $user;
        $this->kiosk = $kiosk;
        $this->kiosk_identifier = $kiosk->kiosk_identifier;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        return  $this->subject("Your PopCom machine named ".$this->kiosk_identifier." has been deactivated!")->with(['user' => $this->user, 'kiosk' => $this->kiosk])->markdown('emails.deactivate_machine');
    }
}