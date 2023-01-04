<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubAccountSettings extends Model
{
    use HasFactory;
    protected $table    = 'sub_account_settings';
    public $primaryKey  = 'sub_account_setting_id';
    public $timestamps  = false;
    
    protected $fillable = [
        'account_id',
        'cms_faq',
        'cms_contact',
        'cms_about',
        'cms_testimonail',
        'cms_privacy',
        'cms_terms',
        'products_price',
        'products_name',
        'products_create',
        'ads_status',
        'ads_gender',
        'ads_age',
        'ads_create'
    ];
}
