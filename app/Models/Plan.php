<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    protected $table    = 'plans';
    public $primaryKey  = 'plan_id';
    public $timestamps  = false;
    
    protected $fillable = [
        'product_name',
        'stripe_product_identifier',
        'stripe_price_identifier',
        'created_at',
    ];
}
