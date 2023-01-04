<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table    = 'carts';
    public $primaryKey  = 'cart_id';
    public $timestamps  = false;
    
    protected $fillable = [
        'journey_id',
        'product_variant_id',
        'product_qty',
        'bin_no'
    ];

    public function journey(){
    	return $this->hasOne(Journey::class, 'journey_id', 'journey_id');
    }
}
