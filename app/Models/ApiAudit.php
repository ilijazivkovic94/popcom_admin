<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiAudit extends Model
{
    use HasFactory;
    protected $table        = 'api_audit';
    protected $primaryKey   = 'api_audit_id';
    public $timestamps      = false;

    protected $fillable     = ['kiosk_identifier', 'endpoint', 'request', 'respones', 'ip_address', 'created_at', 'rtime'];
}
