<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminImportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TaskAssignmentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController; // nếu bạn còn dùng resource users
use App\Http\Controllers\StudentImportController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');


/* Auth (dùng LoginController — bỏ route login của UserController để tránh trùng) */
Route::get('/login',  [LoginController::class, 'show'])->name('login.show');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.do');
Route::get('/captcha/refresh', [LoginController::class, 'refreshCaptcha'])->name('captcha.refresh');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('home');
})->name('logout');

/* Các trang public (ai cũng truy cập) */
Route::get('/attendance', [AttendanceController::class, 'showForm'])->name('attendance.form');
Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');

// Route::get('/evaluation', [EvaluationController::class, 'index'])->name('evaluation.index');
// Route::get('/report',     [ReportController::class, 'index'])->name('report.index');

/*
|--------------------------------------------------------------------------
| Events (public xem danh sách/chi tiết)
|--------------------------------------------------------------------------
*/
Route::get('/events', [EventController::class, 'index'])->name('events.index');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/events/create',       [EventController::class, 'create'])->name('events.create');
    Route::post('/events',             [EventController::class, 'store'])->name('events.store');

    Route::get('/events/{event}/edit', [EventController::class, 'edit'])
        ->name('events.edit')->whereNumber('event');
    Route::put('/events/{event}',      [EventController::class, 'update'])
        ->name('events.update')->whereNumber('event');
    Route::delete('/events/{event}',   [EventController::class, 'destroy'])
        ->name('events.destroy')->whereNumber('event');
    // Route::get('/report',     [ReportController::class, 'index'])->name('report.index');
    // Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    Route::get('/report', [ReportController::class, 'listEvents'])->name('report.index'); // Trang danh sách sự kiện
    Route::get('/reports/{eventId}', [ReportController::class, 'showEvent'])->name('reports.show'); // Trang chi tiết sự kiện
    Route::get('/reports/{eventId}/export', [ReportController::class, 'export'])->name('reports.export'); // Xuất Excel
});

/* Route show đặt SAU CÙNG + ràng buộc số để không bắt 'create' */
Route::get('/events/{event}', [EventController::class, 'show'])
    ->name('events.show')->whereNumber('event');
Route::get('/events/{event}/registrations', [App\Http\Controllers\EventController::class, 'registrations'])
    ->name('events.registrations');

/*
|--------------------------------------------------------------------------
| Users / Tasks (nếu bạn vẫn cần)
|--------------------------------------------------------------------------
*/
Route::resource('users', UserController::class)->middleware(['auth', 'role:admin']);
Route::resource('tasks', TaskController::class)->middleware(['auth', 'role:admin']);
Route::resource('task_assignment', TaskAssignmentController::class)->middleware(['auth', 'role:admin']);

/*
|--------------------------------------------------------------------------
| Profile   
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/edit',   [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/evaluation', [EvaluationController::class, 'listEvents'])->name('evaluation.index');
    Route::prefix('evaluation')->group(function () {
        Route::get('/{event_id}', [EvaluationController::class, 'index'])->name('evaluations.index');
        Route::post('/store', [EvaluationController::class, 'store'])->name('evaluations.store');
        Route::post('/feedback', [EvaluationController::class, 'feedback'])->name('evaluations.feedback');
    });
});

/*
|--------------------------------------------------------------------------
| Students (quản trị sinh viên – chỉ admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/students',               [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create',        [StudentController::class, 'create'])->name('students.create');
    Route::post('/students',              [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{user}/edit',   [StudentController::class, 'edit'])->name('students.edit')->whereNumber('user');
    Route::put('/students/{user}',        [StudentController::class, 'update'])->name('students.update')->whereNumber('user');
    Route::delete('/students/{user}',     [StudentController::class, 'destroy'])->name('students.destroy')->whereNumber('user');
    Route::delete('/students/bulk-delete', [StudentController::class, 'bulkDelete'])->name('students.bulkDelete');


    // Import Excel
    Route::get('/students/import',  [StudentImportController::class, 'show'])->name('students.import.form');
    Route::post('/students/import', [StudentImportController::class, 'store'])->name('students.import.store');
    Route::get('/students/export', [StudentController::class, 'export'])->name('students.export');
    Route::post('/events/{event}/recruit', [App\Http\Controllers\EventController::class, 'recruit'])
        ->name('events.recruit');
    Route::get('/students/{user}', [StudentController::class, 'show'])->name('students.show');

});

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::post('/events/{event}/register', [EventRegistrationController::class, 'store'])
        ->name('registrations.store');
    Route::get('/events/{event}/register', [EventRegistrationController::class, 'confirm'])
        ->name('registrations.confirm');


    Route::get('/my/events', [EventRegistrationController::class, 'myEvents'])
        ->name('registrations.mine');

    Route::get('/events/{event}/checkin', [EventRegistrationController::class, 'checkin'])
        ->name('registrations.checkin')->whereNumber('event');
});;
Route::get('/students/import/sample', function () {
    return response()->download(public_path('samples/test_import_excel.xlsx'));
})->name('students.import.sample');
Route::get('/evaluations/{event_id}', [EvaluationController::class, 'show'])
    ->name('evaluations.show')
    ->middleware('auth');
Route::post('/evaluations/reply', [App\Http\Controllers\EvaluationController::class, 'reply'])
    ->name('evaluations.reply')
    ->middleware('auth');
Route::get('/feedbacks/{event}/{user}', function ($eventId, $userId) {
    $feedback = \App\Models\Feedback::where('event_id', $eventId)
        ->where('user_id', $userId)
        ->first();

    $replies = [];
    if ($feedback) {
        $replies = \App\Models\FeedbackReply::join('users as sender', 'sender.user_id', '=', 'feedback_replies.sender_id')
            ->where('feedback_replies.feedback_id', $feedback->feedback_id)
            ->select('sender.full_name as sender_name', 'feedback_replies.content', 'feedback_replies.created_at')
            ->orderBy('feedback_replies.created_at')
            ->get();
    }

    return response()->json(['feedback' => $feedback, 'replies' => $replies]);
});
Route::post('/notifications/mark-as-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])
    ->name('notifications.markAsRead')
    ->middleware('auth');
Route::middleware(['auth'])->group(function () {
    Route::resource('admins', AdminController::class)->except(['show']);
});
Route::middleware(['auth'])->group(function () {
    Route::get('/admins/import', [AdminImportController::class, 'show'])->name('admins.import');
    Route::post('/admins/import', [AdminImportController::class, 'store'])->name('admins.import.store');
    Route::get('/admins/import/sample', [AdminImportController::class, 'downloadSample'])->name('admins.import.sample');
    Route::get('/admins/import/sample', function () {
        return response()->download(public_path('samples/import_can_bo.xlsx'));
    })->name('admins.import.sample');
});
