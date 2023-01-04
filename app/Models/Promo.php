<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;
    protected $table    = 'promos';
    public $primaryKey  = 'promo_id';
    public $timestamps  = false;
    
    protected $fillable = [
        'account_id',
        'promo_code',
        'promo_cart_desc',
        'promo_optin_message',
        'promo_discount',
        'promo_status',
        'created_at',
        'modified_at'
    ];
}
