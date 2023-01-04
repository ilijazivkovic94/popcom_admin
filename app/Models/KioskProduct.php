<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KioskProduct extends Model
{
    use HasFactory;
    protected $table    = 'kiosk_product';
    public $primaryKey  = 'kiosk_product_id';
    public $timestamps  = false;
    
    protected $fillable = [
        'kiosk__id',
        'product_variant_id',
        'bay_no',
        'quantity',
        'price',
    ];
}
