<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;
    protected $table    = 'orders';
    public $primaryKey  = 'order_id';
    public $timestamps  = false;
    
    protected $fillable = [
        'journey_id',
        'cart_id',
        'created_at',
        'order_transaction_ref',
        'order_subtotal',
        'order_discount_value',
        'order_txn_fees',
        'order_tax',
        'order_total',
        'receipt_yn',
        'promo_id',
        'dispensed_yn',
        'unique_identifier'
    ];
}
