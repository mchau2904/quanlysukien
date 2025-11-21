<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAssignment extends Model
{
    use HasFactory;

    protected $table = 'task_assignment';
    protected $primaryKey = 'assignment_id';
    public $timestamps = false;

    protected $fillable = [
        'task_id',
        'user_id',
        'assigned_at',
        'status',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
