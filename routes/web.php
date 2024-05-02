<?php

use App\Models\IpGlobal;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\CategoryTutorialController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MaterialsController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\Admin\DashboardAdmin;
use App\Http\Controllers\Admin\TutorialsAdminController;
use App\Http\Controllers\Admin\CoursesAdminController;
use App\Http\Controllers\Admin\DaftarPengguna;
use App\Http\Controllers\Admin\AktivitasPenggunaController;
use App\Http\Controllers\user\Dashboarduser;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Pengurus\DashboardPengurus;
use App\Http\Controllers\Pengurus\TutorialsPengurusController;
use App\Http\Controllers\Admin\ChatDashboardController;
use App\Http\Controllers\Admin\IpGlobalController;
use App\Http\Controllers\Admin\IpLockedController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [DashboardController::class, 'index'])->name('home_dashboard');
Route::get('/login', [LoginController::class, 'index'])->name('form.login');
Route::post('/register/submit', [RegisterController::class, 'submit'])->name('register.submit');
Route::get('/materials', [MaterialsController::class, 'index'])->name('materials');
Route::post('/materials/find', [MaterialsController::class, 'find'])->name('materials.find');
Route::get('/courses/{jenis_materi}', [CoursesController::class, 'index'])->name('courses');
Route::post('/courses/submit', [CoursesController::class, 'submitBook'])->name('courses.submit');
Route::post('/dashboard/chat/submit', [DashboardController::class, 'submitChat'])->name('dashboard.submit_chat');

Route::post('/login', [LoginController::class, 'login'])->name('submit_form.login');

Route::get('/api/pusher-key', function () {
    return [
        'pusher_app_key' => env('PUSHER_APP_KEY'),
        'pusher_app_cluster' => env('PUSHER_APP_CLUSTER'),
    ];
});

// Tambahkan rute lain untuk admin di sini
Route::middleware(['auth.login', 'admin.auth', 'blocked', 'check.cookie'])->group(function () {

    Route::get('/admin', [DashboardAdmin::class, 'index'])->name('admin.dashboard');

    Route::prefix('/admin')->group(function () {
        Route::get('/search', [DashboardAdmin::class, 'search'])->name('admin.dashboard.search');
    });

    Route::get('/admin/courses', [CoursesAdminController::class, 'index'])->name('admin.courses.index');

    Route::prefix('/admin/courses')->group(function () {
        Route::get('/search', [CoursesAdminController::class, 'search'])->name('admin.courses.search');
        Route::get('/add', [CoursesAdminController::class, 'add'])->name('admin.courses.add');
        Route::post('info-courses', [CoursesAdminController::class, 'infoCourses'])->name('admin.courses.info_courses');
        Route::post('/save-add-courses', [CoursesAdminController::class, 'saveCourses'])->name('admin.courses.save_courses');
        Route::get('/force-delete/{id}', [CoursesAdminController::class, 'forceDelete'])->name('admin.courses.forceDelete');
        Route::get('/update/{id}', [CoursesAdminController::class, 'update'])->name('admin.courses.update');
    });

    Route::get('/admin/tutorials', [TutorialsAdminController::class, 'index'])->name('tutorials.index');

    Route::prefix('/admin/tutorials')->group(function () {
        Route::get('/add', [TutorialsAdminController::class, 'add'])->name('tutorials.add');
        Route::get('/search', [TutorialsAdminController::class, 'search'])->name('tutorials.search');
        Route::post('/save-add-tutorial', [TutorialsAdminController::class, 'saveTutorial'])->name('tutorials.save_tutorial');
        Route::get('/delete/{video_id}', [TutorialsAdminController::class, 'delete'])->name('tutorials.delete');
        Route::get('/restore/{video_id}', [TutorialsAdminController::class, 'restore'])->name('tutorials.restore');
        Route::get('/update/{video_id}', [TutorialsAdminController::class, 'update'])->name('tutorials.update');
        Route::put('/save-update-tutorial/video_id/{video_id}', [TutorialsAdminController::class, 'saveUpdate'])->name('tutorials.save_update');
        Route::get('/update-password/video_id/{video_id}', [TutorialsAdminController::class, 'updatePassword'])->name('tutorials.update_password');
        Route::get('/view/{video_id}', [TutorialsAdminController::class, 'view'])->name('tutorials.view');
    });

    Route::get('/admin/daftar_pengguna', [DaftarPengguna::class, 'index'])->name('daftar_pengguna.index');

    Route::prefix('/admin/daftar_pengguna')->group(function () {
        Route::get('/add', [DaftarPengguna::class, 'add'])->name('daftar_pengguna.add');
        Route::get('/search', [DaftarPengguna::class, 'search'])->name('daftar_pengguna.search');
        Route::get('/delete/{user_id}', [DaftarPengguna::class, 'delete'])->name('daftar_pengguna.delete');
        Route::post('/save-add-user', [DaftarPengguna::class, 'save'])->name('daftar_pengguna.save_add_user');
        Route::get('/update/{user_id}', [DaftarPengguna::class, 'update'])->name('daftar_pengguna.update');
        Route::put('/save-update-pengguna/user_id/{user_id}', [DaftarPengguna::class, 'saveUpdate'])->name('daftar_pengguna.save_update');
        Route::get('/update-password/user_id/{user_id}', [DaftarPengguna::class, 'updatePassword'])->name('daftar_pengguna.update_password');
        Route::get('/view/{user_id}', [DaftarPengguna::class, 'view'])->name('daftar_pengguna.view');
        Route::get('/restore/{user_id}', [DaftarPengguna::class, 'restore'])->name('daftar_pengguna.restore');
    });

    Route::get('/admin/chat', [ChatDashboardController::class, 'index'])->name('admin.chat_dashboard.index');

    Route::prefix('/admin/chat')->group(function () {
        Route::post('/delete', [ChatDashboardController::class, 'delete'])->name('admin.chat_dashboard.delete');
    });

    Route::get('/admin/category_tutorial', [CategoryTutorialController::class, 'index'])->name('category_tutorial.index');

    Route::prefix('/admin/category_tutorial')->group(function () {
        Route::post('/add-submit', [CategoryTutorialController::class, 'addSubmit'])->name('category_tutorial.addSubmit');
        Route::get('/search', [CategoryTutorialController::class, 'search'])->name('category_tutorial.search');
        Route::get('/delete/{id_cat}', [CategoryTutorialController::class, 'delete'])->name('category_tutorial.delete');
        Route::get('/update/{id_cat}', [CategoryTutorialController::class, 'update'])->name('category_tutorial.update');
        Route::put('/save-update/{id_cat}', [CategoryTutorialController::class, 'saveUpdate'])->name('category_tutorial.saveUpdate');
    });

    Route::get('/admin/language_translate', [LanguageController::class, 'index'])->name('language.index');

    Route::prefix('/admin/language_translate')->group(function () {
        Route::get('/add', [LanguageController::class, 'add'])->name('language.add');
        Route::get('/search', [LanguageController::class, 'search'])->name('language.search');
        Route::post('/save-language', [LanguageController::class, 'saveLanguage'])->name('language.saveLanguage');
    });

    Route::get('/admin/aktivitas_pengguna', [AktivitasPenggunaController::class, 'index'])->name('aktivitas_pengguna.index');

    Route::get('/admin/ip_global', [IpGlobalController::class, 'index'])->name('ip_global.index');

    Route::prefix('/admin/ip_global')->group(function () {
        Route::get('/search', [IpGlobalController::class, 'search'])->name('ip_global.search');
        Route::get('/blocked/{id}', [IpGlobalController::class, 'blocked'])->name('ip_global.blocked');
        Route::get('/locked/{id}', [IpGlobalController::class, 'locked'])->name('ip_global.locked');
        Route::get('/unlocked/{id}', [IpGlobalController::class, 'unlocked'])->name('ip_global.unlocked');
    });

    Route::get('/admin/ip_locked', [IpLockedController::class, 'index'])->name('ip_locked.index');

    Route::prefix('/admin/ip_locked')->group(function () {
        Route::get('/search', [IpLockedController::class, 'search'])->name('ip_locked.search');
        Route::get('/save-unlock/{id}', [IpLockedController::class, 'saveUnlocked'])->name('ip_locked.saveUnlocked');
    });

});


// Tambahkan rute lain untuk pengurus di sini
Route::middleware(['auth.login', 'pengurus.auth', 'blocked', 'check.cookie'])->group(function () {
    Route::get('/pengurus', [DashboardPengurus::class, 'index'])->name('pengurus.dashboard');
    Route::get('/pengurus/tutorials', [TutorialsPengurusController::class, 'index'])->name('pengurus.tutorials.index');
    Route::get('/add', [TutorialsPengurusController::class, 'add'])->name('pengurus.tutorials.add');
    Route::get('/search', [TutorialsPengurusController::class, 'search'])->name('pengurus.tutorials.search');
    Route::get('/update/{video_id}', [TutorialsPengurusController::class, 'update'])->name('pengurus.tutorials.update');
    Route::put('/save-update-tutorial/video_id/{video_id}', [TutorialsPengurusController::class, 'saveUpdate'])->name('pengurus.tutorials.save_update');
    Route::post('/pengurus/save-tutorial', [TutorialsPengurusController::class, 'saveTutorial'])->name('pengurus.saveTutorial');
    Route::post('/pengurus/tutorials', [TutorialsPengurusController::class, 'index']);
});


// Tambahkan rute lain untuk user di sini
Route::middleware(['auth.login', 'user.auth', 'blocked', 'check.cookie'])->group(function () {
    Route::get('/user', [DashboardUser::class, 'index'])->name('user.dashboard');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');