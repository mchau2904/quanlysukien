<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';
    protected $primaryKey = 'event_id';
    public $timestamps = false;

    protected $fillable = [
        'event_code',
        'event_name',
        'organizer',
        'manager_id',
        'level',
        'semester',
        'academic_year',
        'start_time',
        'end_time',
        'registration_deadline',
        'location',
        'description',
        'is_recruiting',
        'max_participants',
        'created_at',
        'image_url',
        'target_faculty',
        'target_class',
        'target_gender',
    ];

    // ⚠️ QUAN TRỌNG: cast về datetime để dùng ->format()
    protected $casts = [
        'start_time' => 'datetime:Asia/Ho_Chi_Minh',
        'end_time' => 'datetime:Asia/Ho_Chi_Minh',
        'max_participants' => 'integer',
        'created_at' => 'datetime',
    ];

    // (Tuỳ chọn) phục vụ input type="datetime-local"
    public function getStartTimeInputAttribute(): ?string
    {
        return $this->start_time ? Carbon::parse($this->start_time)->format('Y-m-d\TH:i') : null;
    }
    public function getEndTimeInputAttribute(): ?string
    {
        return $this->end_time ? Carbon::parse($this->end_time)->format('Y-m-d\TH:i') : null;
    }

    // Quan hệ (nếu dùng)
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id', 'user_id');
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'event_id', 'event_id');
    }
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'event_id', 'event_id');
    }

    // Scopes
    public function scopeOngoing(Builder $q): Builder
    {
        $now = Carbon::now();
        return $q->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now);
    }
    public function scopeUpcoming(Builder $q): Builder
    {
        return $q->where('start_time', '>', Carbon::now());
    }
}
