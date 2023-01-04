<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CreateMachine extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */ 
    public function __construct($user){
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        $subject = $this->user['pre_account_name'].' would like to add a machine to the '.$this->user["sub_account_name"].' sub-account.';
        return $this->subject($subject)->with(['user' => $this->user])->markdown('emails.create_machine');
    }
}
