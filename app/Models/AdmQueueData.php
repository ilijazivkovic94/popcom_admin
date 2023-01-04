<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmQueueData extends Model
{
    use HasFactory;
    protected $table    = 'adm_queue_data';
    public $primaryKey  = 'adm_queue_data_id';
    public $timestamps  = false;

    public static $AGE_GROUP = [
        0 => [
            'min' => 0,
            'max' => 14,
            'label' => 'child'
        ],
        1 => [
            'min' => 15,
            'max' => 24,
            'label' => 'young_adult'
        ],
        2 => [
            'min' => 25,
            'max' => 64,
            'label' => 'adult'
        ],
        3 => [
            'min' => 65,
            'max' => 200,
            'label' => 'senior'
        ],
    ];
    public static $EMOTIONS = [
        0 => 'NEUTRAL',
        1 => 'DISGUST',
        2 => 'SURPRISED',
        3 => 'HAPPY',
        4 => 'ANGRY',
        5 => 'FEAR',
        6 => 'SAD',
    ];

    protected $fillable = [
        'deviceRegistry',
        'deviceId',
        'cameraId',
        'emotion',
        'gender',
        'age',
        'isView',
        'isImpression',
        'dwellTime',
        'insertId',
        'mask',
        'sessionTime',
        'deviceName',
        'adm_queue_id',
        'timestamp'
    ];
}
