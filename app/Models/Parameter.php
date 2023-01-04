<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    use HasFactory;
    protected $table    = 'parameters';
    public $primaryKey  = 'parameter_id';
    public $timestamps  = false;
    
    protected $fillable = [
        'parameter_key',
        'parameter_value'
    ];
}
