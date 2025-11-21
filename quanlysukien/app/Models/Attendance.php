<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Attendance extends Model
{
    protected $table = 'attendance';
    protected $primaryKey = 'attendance_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'attendance_id',
        'event_id',
        'user_id',
        'checkin_time',
        'checkout_time',
        'ip_address',
        'ssid',
        'image_url',
        'status',
        'created_at'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->attendance_id = (string) Str::uuid();
            $model->created_at = now();
        });
    }
}
