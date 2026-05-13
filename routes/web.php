<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Ruta secreta para ejecutar migraciones en Render (Free Tier)
Route::get('/migrar-base-de-datos', function() {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate --force');
        return 'Migraciones ejecutadas con éxito: ' . \Illuminate\Support\Facades\Artisan::output();
    } catch (\Exception $e) {
        return 'Error al ejecutar migraciones: ' . $e->getMessage();
    }
});

// RUTA NUCLEAR: Borra todo, migra de nuevo y mete los datos de prueba (seeds)
Route::get('/reset-total', function() {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh --seed --force');
        return '¡ÉXITO! Base de datos borrada y recreada desde cero con datos de prueba: <br><pre>' . \Illuminate\Support\Facades\Artisan::output() . '</pre>';
    } catch (\Exception $e) {
        return 'Error al resetear la base de datos: ' . $e->getMessage();
    }
});

