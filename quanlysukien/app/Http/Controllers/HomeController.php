<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // public function index(Request $request)
    // {
    //     // 🔍 Lấy từ khóa tìm kiếm
    //     $q = trim($request->get('q', ''));

    //     // 🕒 Sự kiện đang diễn ra
    //     $ongoing = Event::query()
    //         ->when($q !== '', function ($query) use ($q) {
    //             $query->where('event_name', 'like', "%{$q}%");
    //         })
    //         ->ongoing() // scope để lọc sự kiện đang diễn ra
    //         ->orderBy('start_time')
    //         ->limit(12)
    //         ->get();

    //     // ⭐ Sự kiện nổi bật (10 mới nhất)
    //     $featured = Event::query()
    //         ->when($q !== '', function ($query) use ($q) {
    //             $query->where('event_name', 'like', "%{$q}%");
    //         })
    //         ->orderByDesc('start_time')
    //         ->limit(10)
    //         ->get();

    //     return view('welcome', compact('ongoing', 'featured', 'q'));
    // }
     public function index(Request $request)
    {
        // 🔍 Lấy từ khóa tìm kiếm
        $q = trim($request->get('q', ''));

        // 🕒 Sự kiện đang diễn ra
        $ongoing = Event::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('event_name', 'like', "%{$q}%");
            })
            ->ongoing() // scope để lọc sự kiện đang diễn ra
            ->orderBy('start_time')
            ->limit(12)
            ->get();

        // Sự kiện sắp diễn ra
        $upcoming = Event::query()
        ->when($q !== '', fn($query) => $query->where('event_name', 'like', "%{$q}%"))
        ->upcoming()
        ->orderBy('start_time')
        ->limit(12)
        ->get();

        // ⭐ Sự kiện nổi bật (10 mới nhất)
        $featured = Event::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('event_name', 'like', "%{$q}%");
            })
            ->orderByDesc('start_time')
            ->limit(10)
            ->get();

        return view('index', compact('ongoing', 'upcoming','featured', 'q'));
    }
}
