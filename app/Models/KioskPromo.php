<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KioskPromo extends Model
{
    use HasFactory;
    protected $table    = 'kiosk_promos';
    public $primaryKey  = 'kiosk_promo_id';
    public $timestamps  = false;
    
    protected $fillable = [
        'kiosk_id',
        'promo_id',
        'optin_yn'
    ];
}
