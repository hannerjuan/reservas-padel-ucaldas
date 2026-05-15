<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\BlockedSlotController;


// RUTAS PÚBLICAS 
// Listado de espacios, filtros por tipo y acceso a la disponibilidad
Route::get('/', [SpaceController::class, 'publicIndex'])->name('home');

// Detalle del espacio y próximos horarios disponibles (Usa model binding por slug) 
Route::get('/spaces/{space:slug}', [SpaceController::class, 'publicShow'])->name('spaces.public.show');

// Formulario de reserva 
Route::get('/reservations/new', [ReservationController::class, 'create'])->name('reservations.new');

// Crea la reserva, valida colisiones y verifica disponibilidad 
Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');




// RUTAS PROTEGIDAS (Panel Administrativo) 
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->prefix('admin')->group(function () {

    // Resumen general de reservas 
    Route::get('/home', [ReservationController::class, 'dashboard'])->name('admin.home');

    // Calendario semanal del espacio seleccionado 
    Route::get('/calendar', [SpaceController::class, 'calendar'])->name('admin.calendar');

    // CRUD completo de espacios 
    Route::resource('spaces', SpaceController::class)->parameters([
        'spaces' => 'space:slug',
    ]);

    // Listado con filtros y acciones administrativas de reservas 
    Route::resource('reservations', ReservationController::class)->except(['create', 'store']);

    // Acciones de cambio de estado para reservas 
    Route::post('/reservations/{reservation}/accept', [ReservationController::class, 'accept'])->name('reservations.accept');
    Route::post('/reservations/{reservation}/reject', [ReservationController::class, 'reject'])->name('reservations.reject');
    Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
    
    // CRUD para Disponibilidad y Bloqueos (necesarios para administrar)
    Route::resource('availabilities', AvailabilityController::class);
    Route::resource('blocked-slots', BlockedSlotController::class);

    Route::get('/spaces/{space:slug}/availability', [AvailabilityController::class, 'edit'])->name('availabilities.edit');
Route::put('/spaces/{space:slug}/availability', [AvailabilityController::class, 'update'])->name('availabilities.update');

});