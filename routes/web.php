<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RetentionController;
use Illuminate\Support\Facades\Route;

// RUTAS DE LOGIN PERSONALIZADAS 
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// RUTAS PROTEGIDAS POR authssssss
Route::middleware('auth')->group(function () {
    // Menú principal de retenciones
    Route::get('/', [RetentionController::class, 'menu'])->name('menu');
    
    // Perfil
    Route::get('/profile',   [ProfileController::class, 'edit']  )->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class,'destroy'])->name('profile.destroy');

    // Menú principal de retenciones
    Route::get('/', [RetentionController::class, 'menu'])->name('menu');

    // Generar retención
    Route::get('/retention/create', [RetentionController::class, 'create'])->name('retention.create');
    Route::post('/retention/store', [RetentionController::class, 'store' ]   )->name('retention.store');

    // Mostrar/descargar PDF
    Route::get('/retention/pdf/{id}', [RetentionController::class, 'showPdf'])
         ->name('retention.pdf');

    // Búsqueda de retenciones
    Route::get('/retentions/search', [RetentionController::class, 'search'])
         ->name('retention.search');

    // Reportes (quincenal/mensual)
    Route::get('/retentions/report', [RetentionController::class, 'report'])
         ->name('retention.report');
});

// (Elimina o comenta require __DIR__.'/auth.php'; si ya no usas ese scaffolding)
