<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KioskMonitor extends Model
{
    use HasFactory;
    protected $table    = 'kiosk_monitor';
    public $primaryKey  = 'kiosk_monitor_id';
    public $timestamps  = false;
    
    protected $fillable = [
        'kiosk_id',
        'monitor_status',
        'monitor_dt',
        'error_type',
        'erroe_message',
    ];

     public function kiosk(){
        return $this->hasOne(Kiosk::class, 'kiosk_id', 'kiosk_id');
    }
}
