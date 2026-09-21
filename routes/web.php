<?php

use App\Http\Controllers\AuditController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotifController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\PermohonanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/', [HomeController::class, 'index'])->name('home');


    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');


    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::get('/profil', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profil', [ProfileController::class, 'update'])
        ->name('profile.update');


    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    Route::post('/keluar', [AuthController::class, 'logout'])
        ->name('logout');


    /*
    |--------------------------------------------------------------------------
    | Permohonan
    |--------------------------------------------------------------------------
    */

    Route::get('/permohonan', [PermohonanController::class, 'index'])
        ->name('permohonan.index');

    Route::get('/permohonan/baru', [PermohonanController::class, 'create'])
        ->name('permohonan.create');

    Route::post('/permohonan', [PermohonanController::class, 'store'])
        ->name('permohonan.store');

    Route::get('/permohonan/{permohonan}', [PermohonanController::class, 'show'])
        ->name('permohonan.show');

    Route::post('/permohonan/{permohonan}/transisi', [PermohonanController::class, 'transition'])
        ->name('permohonan.transition');


    /*
    |--------------------------------------------------------------------------
    | Password
    |--------------------------------------------------------------------------
    */

    Route::get('/kata-sandi', [PasswordController::class, 'edit'])
        ->name('password.edit');

    Route::put('/kata-sandi', [PasswordController::class, 'update'])
        ->name('password.update');


    /*
    |--------------------------------------------------------------------------
    | Notifikasi
    |--------------------------------------------------------------------------
    */

    Route::get('/notifikasi', [NotifController::class, 'index'])
        ->name('notifs.index');

    Route::post('/notifikasi/{notif}/baca', [NotifController::class, 'read'])
        ->name('notifs.read');

    Route::post('/notifikasi/baca-semua', [NotifController::class, 'readAll'])
        ->name('notifs.readAll');


    /*
    |--------------------------------------------------------------------------
    | Data Siswa
    |--------------------------------------------------------------------------
    */

    Route::get('/siswa', [StudentController::class, 'index'])
        ->name('students.index');

    Route::get('/siswa/{student}', [StudentController::class, 'show'])
        ->name('students.show');


    /*
    |--------------------------------------------------------------------------
    | Admin
    |--------------------------------------------------------------------------
    */

    Route::middleware('peran:admin')->group(function () {

        Route::get('/pengguna', [UserController::class, 'index'])
            ->name('users.index');

        Route::post('/pengguna', [UserController::class, 'store'])
            ->name('users.store');

        Route::put('/pengguna/{user}/peran', [UserController::class, 'updateRole'])
            ->name('users.role');

        Route::put('/pengguna/{user}/status', [UserController::class, 'toggleStatus'])
            ->name('users.status');

        Route::put('/pengguna/{user}/kata-sandi', [UserController::class, 'resetPassword'])
            ->name('users.password');


        Route::get('/log-audit', [AuditController::class, 'index'])
            ->name('audit.index');

        Route::get('/log-audit/ekspor', [AuditController::class, 'export'])
            ->name('audit.export');
    });
});


require __DIR__.'/auth.php';