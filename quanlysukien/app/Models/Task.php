<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';
    protected $primaryKey = 'task_id';
    public $timestamps = false;

    protected $fillable = [
        'event_id',
        'task_name',
        'description',
        'required_number',
        'created_at'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
