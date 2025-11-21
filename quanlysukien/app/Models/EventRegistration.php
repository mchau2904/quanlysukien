<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    protected $table = 'event_registration';
    protected $primaryKey = 'registration_id';
    public $timestamps = false;

    protected $fillable = ['event_id', 'user_id', 'status', 'note', 'register_date'];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }
}
