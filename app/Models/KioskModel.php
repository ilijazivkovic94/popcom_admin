<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KioskModel extends Model
{
    use HasFactory;
    protected $table    = 'kiosk_model';
    public $primaryKey  = 'kiosk_model_id';
    public $timestamps  = false;
    
    protected $fillable = [
        'model_name',
        'model_description',
        'model_type',
        'model_is_refrigerated',
        'model_manufacturer',
        'model_avaialble_yn',
        'created_at',
        'modified_at'
    ];
}
