<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountSetting extends Model
{
    use HasFactory;

    protected $table    = 'account_setting';
    public $primaryKey  = 'account_setting_id';
    public $timestamps  = false;

    protected $fillable = [
        'account_id',
        'account_logo',
        'primany_color',
        'secondary_color',
        'account_org_name',
        'account_survey_url',
        'account_poc',
        'account_timezone',
        'account_contact_email',
        'account_contact_phone',
        'receipt_subject_line',
        'receipt_custom_text_1',
        'receipt_custom_text_2',
        'receipt_sender_email',
        'receipt_sender_password',
        'receipt_sender_host',
        'receipt_sender_port',
        'receipt_survey_url',
        'cms_about',
        'cms_testimonials',
        'cms_privacy_policy',
        'cms_terms_of_use',
        'cms_contact_us',
        'cms__faq',
        'include_survey_url',
        'cms_about_active_yn',
        'cms_testimonials_active_yn',
        'cms_privacy_policy_active_yn',
        'cms_terms_of_use_active_yn',
        'cms_contact_us_active_yn',
        'cms__faq_active_yn',
        'country_code',
        'main_setting_yn',
        'receipt_setting_yn'
    ];
}