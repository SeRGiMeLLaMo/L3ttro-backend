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
