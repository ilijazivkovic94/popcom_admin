<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KioskOptin extends Model
{
    use HasFactory;
    protected $table    = 'kiosk_optin';
    public $primaryKey  = 'kiosk_optin_id';
    public $timestamps  = false;
    
    protected $fillable = [
        'kiosk_id',
        'customer_id',
        'optin_promo_id',
        'customer_email',
        'optin_dt',
    ];
}
