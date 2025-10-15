<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EntradaController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\ResponsavelController;
use App\Http\Controllers\VinculoController;
use App\Http\Controllers\ControleController;

Route::get('/', function () {
    return view('app');
});

Route::get('/welcome', function () {
    return view('welcome');
});

// Auth Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes (não autenticado)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    });
    
    // Authenticated routes
    Route::middleware('auth.admin')->group(function () {
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
        
        // Profile routes
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/', [AdminDashboardController::class, 'index']); // redirect /admin to dashboard
        
        // API Routes for dashboard
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/stats', [AdminDashboardController::class, 'stats'])->name('stats');
            Route::get('/activity', [AdminDashboardController::class, 'activity'])->name('activity');
            Route::get('/eventos', [AdminDashboardController::class, 'eventos'])->name('eventos');
            Route::get('/responsaveis', [AdminDashboardController::class, 'responsaveis'])->name('responsaveis');
        });
        
        // Admin only routes
        Route::middleware('auth.admin:admin')->group(function () {
            // User Management
            Route::resource('usuarios', UserController::class);
            Route::post('/usuarios/bulk-status', [UserController::class, 'bulkStatus'])->name('usuarios.bulk-status');
            
            // Vinculos Management
            Route::resource('vinculos', VinculoController::class);
            Route::post('/vinculos/{vinculo}/toggle-status', [VinculoController::class, 'toggleStatus'])->name('vinculos.toggle-status');
            Route::post('/vinculos/bulk-action', [VinculoController::class, 'bulkAction'])->name('vinculos.bulk-action');
        });
        
        // Admin and Supervisor routes
        Route::middleware('auth.admin:admin,supervisor')->group(function () {
            // Event Management
            Route::resource('eventos', EventoController::class);
            Route::post('/eventos/{evento}/toggle-status', [EventoController::class, 'toggleStatus'])->name('eventos.toggle-status');
        });
        
        // Operator routes
        Route::middleware('auth.admin:admin,operador,supervisor')->group(function () {
            // Entry Management - Custom routes first (before resource route)
            Route::get('/entradas-data', [EntradaController::class, 'data'])->name('entradas.data');
            Route::get('/entradas/evento/{evento}/data', [EntradaController::class, 'getEventData'])->name('entradas.evento.data');
            Route::get('/entradas/vinculados', [EntradaController::class, 'getVinculados'])->name('entradas.vinculados');
            Route::get('/entradas/perfis-acesso/{evento}', [EntradaController::class, 'getPerfisAcesso'])->name('entradas.perfis-acesso');
            Route::get('/entradas/check-duplicate', [EntradaController::class, 'checkDuplicate'])->name('entradas.check-duplicate');
            Route::post('/entradas/{entrada}/saida', [EntradaController::class, 'saida'])->name('entradas.saida');
            // Resource route last to avoid conflicts
            Route::resource('entradas', EntradaController::class);
            
            // Recreation Control Routes
            Route::prefix('controle')->name('controle.')->group(function () {
                Route::get('/', [ControleController::class, 'index'])->name('index');
                Route::get('/evento/{evento}', [ControleController::class, 'show'])->name('evento')->where('evento', '[0-9]+');
                Route::get('/data', [ControleController::class, 'data'])->name('data');
                Route::get('/stats/{evento}', [ControleController::class, 'stats'])->name('stats')->where('evento', '[0-9]+');
                Route::post('/entrada/{entrada}/saida', [ControleController::class, 'registrarSaida'])->name('saida');
                Route::post('/entrada/{entrada}/adicional', [ControleController::class, 'cobrarAdicional'])->name('adicional');
                Route::get('/entrada/{entrada}/detalhes', [ControleController::class, 'detalhes'])->name('detalhes');
            });
            
            // Responsaveis Management
            Route::resource('responsaveis', ResponsavelController::class)->parameters([
                'responsaveis' => 'responsavel'
            ]);
            Route::post('/responsaveis/{responsavel}/toggle-status', [ResponsavelController::class, 'toggleStatus'])->name('responsaveis.toggle-status');
            Route::post('/responsaveis/bulk-action', [ResponsavelController::class, 'bulkAction'])->name('responsaveis.bulk-action');
        });
        
        // Cashier routes
        Route::middleware('auth.admin:admin,operador,caixa,supervisor')->group(function () {
            Route::get('/caixa', function () { return view('admin.caixa.index'); })->name('caixa.index');
        });
        
        // Reports routes  
        Route::middleware('auth.admin:admin,supervisor')->group(function () {
            Route::get('/relatorios', function () { return view('admin.relatorios.index'); })->name('relatorios.index');
        });
    });
});
