<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyProduct extends Model
{
    use HasFactory;
    protected $table    = 'myproducts';
    public $primaryKey  = 'myproduct_id';
    public $timestamps  = false;
    
    protected $fillable = [
        'account_id',
        'product_id',
        'name',
        'variant_id',
        'price'
    ];
}
