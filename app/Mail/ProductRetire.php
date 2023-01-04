<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductRetire extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */ 
    public function __construct($user, $product, $variantData, $orgName){
        $this->user = $user;
        $this->product = $product;
        $this->variantData = $variantData;
        $this->orgName = $orgName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        return  $this->subject($this->orgName['account_name']." org has retired a product
")->with(['user' => $this->user, 'product' => $this->product, 'variantData' => $this->variantData, 'orgName' => $this->orgName])->markdown('emails.product_retire');
    }
}
