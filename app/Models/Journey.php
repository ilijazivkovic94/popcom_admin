<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journey extends Model
{
    use HasFactory;
    protected $table    = 'journeys';
    public $primaryKey  = 'journey_id';
    public $timestamps  = false;
    
    protected $fillable = [
        'customer_id',
        'kiosk_id',
        'created_at',
    ];

    public function cart(){
    	return $this->hasOne(Cart::class,'journey_id','journey_id');
    }
}
