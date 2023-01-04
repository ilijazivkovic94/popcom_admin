<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    protected $table    = 'subscriptions';
    public $primaryKey  = 'id';
    public $timestamps  = false;
    
    protected $fillable = [
    ];
}
