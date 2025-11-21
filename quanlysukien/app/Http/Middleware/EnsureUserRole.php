<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // ⬅️ thêm Facade
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Dùng: ->middleware(['auth', 'role:admin']) hoặc 'role:student'
     * Có thể truyền nhiều vai trò: 'role:admin,student'
     */
    public function handle(Request $request, Closure $next, ...$roles) /*: Response*/
    {
        // Chưa đăng nhập → chuyển về trang login
        if (!Auth::check()) { // ⬅️ dùng Auth::check()
            return redirect()->guest('/login');
        }

        // Lấy role trong DB
        $user = Auth::user(); // ⬅️ dùng Auth::user()
        $userRole = $user->role ?? null; // ENUM('admin','student')

        // Không truyền tham số → chặn
        if (empty($roles)) {
            abort(403);
        }

        // Hợp lệ nếu role của user thuộc danh sách cho phép
        if (!in_array($userRole, $roles, true)) {
            abort(403); // hoặc redirect()->to('/')->with('error','Không đủ quyền')
        }

        return $next($request);
    }
}
