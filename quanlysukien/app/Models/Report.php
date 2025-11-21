<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';
    protected $primaryKey = 'report_id';
    public $timestamps = false;

    protected $fillable = [
        'created_by',
        'type',
        'parameters',
        'result_url',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
