<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeactivateMachineParent extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */ 
    public function __construct($parent, $kiosk, $user){
        $this->parent = $parent;
        $this->user = $user;
        $this->kiosk = $kiosk;
        $this->kiosk_identifier = $kiosk->kiosk_identifier;
        $this->account_name = $user->accountDetails->account_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        return  $this->subject("The ".$this->account_name." machine named ".$this->kiosk_identifier." has been deactivated!")->with(['user' => $this->user, 'kiosk' => $this->kiosk, 'parent' => $this->parent])->markdown('emails.deactivate_machine_parent');
    }
}
