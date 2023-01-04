<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivateSubAccount extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */ 
    public function __construct($user, $parentAccountDetails){
        $this->user = $user;
        $this->account_name = $user['account_name'];
        $this->parent_account = $parentAccountDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        return  $this->subject("The Sub-Account ".$this->account_name." has been activated under your")->with(['user' => $this->user, 'parent_account' => $this->parent_account])->markdown('emails.activate_sub_account');
    }
}
