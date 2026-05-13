<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Ruta secreta para ejecutar migraciones en Render (Free Tier)
Route::get('/migrar-base-de-datos', function() {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        return 'Migraciones ejecutadas con éxito: ' . \Illuminate\Support\Facades\Artisan::output();
    } catch (\Exception $e) {
        return 'Error al ejecutar migraciones: ' . $e->getMessage();
    }
});

// RUTA NUCLEAR: Borra todo, migra de nuevo y mete los datos de prueba (seeds)
Route::get('/reset-total', function() {
    try {
        $db = \Illuminate\Support\Facades\DB::class;

        // 1. Borrar y recrear el esquema entero (evita problemas de FK con DROP TABLE)
        $db::statement('DROP SCHEMA public CASCADE');
        $db::statement('CREATE SCHEMA public');
        $db::statement('GRANT ALL ON SCHEMA public TO neondb_owner');
        $db::statement('GRANT ALL ON SCHEMA public TO public');

        // 2. Migrar (sin fresh, el esquema ya está vacío)
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $migrateOutput = \Illuminate\Support\Facades\Artisan::output();

        // 3. Seeds
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        $seedOutput = \Illuminate\Support\Facades\Artisan::output();

        return '¡ÉXITO TOTAL!<br><pre>' . $migrateOutput . $seedOutput . '</pre>';
    } catch (\Exception $e) {
        return 'Error al resetear: ' . $e->getMessage();
    }
});


