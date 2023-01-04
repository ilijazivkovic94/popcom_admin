<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;
    protected $table    = 'product_variants';
    public $primaryKey  = 'product_variant_id';
    public $timestamps  = false;
    
    protected $fillable = [
        'product_id',
        'product_identifier',
        'variant_sku',
        'variant_name',
        'variant_value',
        'variant_price'
    ];
}
