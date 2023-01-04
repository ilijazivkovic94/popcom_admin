<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table    = 'customers';
    public $primaryKey  = 'customer_id';
    public $timestamps  = false;
    
    protected $fillable = [
        'account_id',
        'customer_email',
        'customer_gender',
        'customer_age_group',
        'created_at',
        'modified_at'
    ];
}
