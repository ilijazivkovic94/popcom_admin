<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyAd extends Model
{
    use HasFactory;
    protected $table    = 'myadvertisement';
    public $primaryKey  = 'myadvertisement_id';
    public $timestamps  = false;
    
    protected $fillable = [
        'advertisement_id',
        'account_id',
        'age',
        'gender',
        'status'
    ];
}
