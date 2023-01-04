<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JourneyStep extends Model
{
    use HasFactory;
    protected $table    = 'journey_steps';
    public $primaryKey  = 'journey_step_id';
    public $timestamps  = false;
    
    protected $fillable = [
        'journey__id',
        'journey_step_name',
        'journey_emotion_json',
        'journey_age_group',
        'journey_gender',
        'journey_step_dt'
    ];
}
