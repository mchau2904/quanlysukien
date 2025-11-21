<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function markAsRead()
{
    $userId = auth()->id();
    if (!$userId) return response()->json(['error' => 'Unauthenticated'], 401);

    // Lưu vào bảng notification_reads
    $notifications = DB::table('notifications')
        ->select('id')
        ->where(function($q) use ($userId) {
            $q->where('user_id', $userId)->orWhereNull('user_id');
        })
        ->pluck('id');

    foreach ($notifications as $nid) {
        DB::table('notification_reads')->updateOrInsert([
            'user_id' => $userId,
            'notification_id' => $nid,
        ], ['read_at' => now()]);
    }

    return response()->json(['status' => 'success']);
}
}
