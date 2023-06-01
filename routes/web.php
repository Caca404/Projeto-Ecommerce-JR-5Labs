<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompradorController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\VendedorController;
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
        Route::redirect('/admin', '/admin/dashboard');

        Route::get('/admin/dashboard', function(){
            return view('admin/dashboard');
        })->name('admin/dashboard');

        Route::get('/admin/compradores', [AdminController::class, 'compradores'])
            ->name('admin/compradores');

        Route::get('/admin/vendedores', [AdminController::class, 'vendedores'])
            ->name('admin/vendedores');

        Route::get('/admin/produtos', [AdminController::class, 'produtos'])
            ->name('admin/produtos');

        Route::get('/admin/decisionStatusVendedor/{id}/{decision}', 
            [AdminController::class, 'decisionStatusVendedor'])->name('admin/decisionStatusVendedor');
            
    });

    Route::group(['middleware' => ['comprador']], function () {
        Route::redirect('/', '/comprador/dashboard');
        Route::redirect('/comprador', '/comprador/dashboard');

        Route::get('/comprador/dashboard', [CompradorController::class, 'dashboard'])
            ->name('comprador/dashboard');

        Route::get('/comprador/perfil', [CompradorController::class, 'perfil'])
            ->name('comprador/perfil');

        Route::get('/produto/{id}', [ProdutoController::class, 'show']);

        Route::get('/buy/{id}', [CompradorController::class, 'buy']);

        Route::get('/comprador/minhas-compras', [CompradorController::class, 'myOrders'])
            ->name('comprador/minhas-compras');

        Route::patch('/comprador/perfil', [CompradorController::class, 'update']);
        Route::post('/updatePassword', [CompradorController::class, 'updatePassword']);
    });

    Route::group(['middleware' => ['vendedor']], function () {
        Route::redirect('/', '/vendedor/dashboard');
        Route::redirect('/vendedor', '/vendedor/dashboard');

        Route::get('/vendedor/dashboard', [VendedorController::class, 'dashboard'])
            ->name('vendedor/dashboard');

        Route::get('/vendedor/minhas-vendas', [VendedorController::class, 'minhasVendas'])
            ->name('vendedor/minhas-vendas');

        Route::get('/produto', [ProdutoController::class, 'produto']);
        Route::post('/produto/create', [ProdutoController::class, 'createProduto']);

        Route::get('/produto/edit/{id}', [ProdutoController::class, 'produto']);
        Route::post('/produto/edit/{id}', [ProdutoController::class, 'editProduto']);
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