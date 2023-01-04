<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;
    protected $table    = 'ads';
    public $primaryKey  = 'ad_id';
    public $timestamps  = false;
    
    protected $fillable = [
        'account_id',
        'ad_title',
        'ad_type',
        'ad_age_group',
        'ad_status',
        'ad_gender',
        'ad_data',
        'created_at',
        'modified_at',
        'parent_ad_id',
    ];
}
