<?php

use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Visitantes
 */
Route::group(['middleware' => ['guest']], function () {

    Route::redirect('/', '/login');
    
    Route::get('/login', function() {
        return view('auth/login');
    })->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', function() {
        return view('auth/register');
    })->name('register');
    Route::post('/register', [AuthController::class, 'store']);


    Route::get('/forgot-password', function(){
        return view('auth/passwords/email');
    });
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink']);

    Route::get('/reset-password/{token}', function ($token) {
        return view('auth/passwords/reset', ['token' => $token, 'email' => $_GET['email']]);
    })->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])
        ->name('password.update');
});



/**
 * Usuários logados
 */
Route::middleware('auth')->group(function () {

    Route::group(['middleware' => ['admin']], function () {
        Route::redirect('/', '/admin/dashboard');

        Route::get('/admin/dashboard', function(){
            return view('admin/dashboard');
        })->name('admin/dashboard');
    });

    Route::group(['middleware' => ['comprador']], function () {
        Route::redirect('/', '/comprador/dashboard');

        Route::get('/comprador/dashboard', function(){
            return view('comprador/dashboard');
        })->name('comprador/dashboard');
    });

    Route::group(['middleware' => ['vendedor']], function () {
        Route::redirect('/', '/vendedor/dashboard');

        Route::get('/vendedor/dashboard', function(){
            return view('vendedor/dashboard');
        })->name('vendedor/dashboard');
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/email/verify', function () {
        return view('auth/verify');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verificateEmail'])
        ->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', [AuthController::class, 'sendEmailVerification'])
        ->middleware(['throttle:6,1'])->name('verification.send');
});