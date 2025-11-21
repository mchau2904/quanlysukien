<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackReply extends Model
{
    use HasFactory;

    protected $table = 'feedback_replies';
    protected $primaryKey = 'reply_id';
    public $timestamps = false;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'content',
        'created_at',
    ];

    // Người gửi phản hồi
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'user_id');
    }

    // Người nhận phản hồi
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id', 'user_id');
    }
}
