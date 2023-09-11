<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\controladorBD;
use App\Http\Controllers\controladorEncargado;

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

//RUTAS DEL LOGIN
Route::get('/',[controladorBD::class,'login'])->name('login')->middleware('alreadyLoggedIn');
Route::post('validar-login',[controladorBD::class,'loginUser'])->name('validate');
Route::get('logout',[controladorBD::class,'logout'])->name('logout');

//RUTAS DE INTERFACES
Route::middleware(['isLoggedIn'])->group(function () {

    //RUTAS ADMIN
    Route::get('inicio', [controladorBD::class, 'index'])->name('index');
    Route::get('graficas', [controladorBD::class, 'charts'])->name('charts');
    Route::get('tabla-unidades', [controladorBD::class, 'tableUnidad'])->name('unidades');
    Route::get('tabla-encargados', [controladorBD::class, 'tableEncargado'])->name('encargados');
    Route::get('tabla-refacciones', [controladorBD::class, 'tableRefaccion'])->name('refacciones');
    Route::get('tabla-salidas', [controladorBD::class, 'tableSalidas'])->name('salidas');
    Route::get('tabla-compras', [controladorBD::class, 'tableCompras'])->name('compras');
    Route::get('tabla-solicitud', [controladorBD::class, 'tableSolicitud'])->name('solicitudes');
    Route::get('form-Unidad', [controladorBD::class, 'createUnidad'])->name('CreateUnidad');
    Route::get('edit-Unidad/{id}', [controladorBD::class, 'editUnidad'])->name('editUnidad');
    Route::get('form-user',[controladorBD::class,'createUser'])->name('createUser');

    //RUTAS ENCARGADO
    Route::get('inicioEnc',[controladorEncargado::class,'index'])->name('indexEnc');
    Route::get('graficasEnc', [controladorEncargado::class, 'charts'])->name('chartsEnc');
    Route::get('tabla-unidadesEnc', [controladorEncargado::class, 'tableUnidad'])->name('unidadesEnc');
    Route::get('tabla-refaccionesEnc', [controladorEncargado::class, 'tableRefaccion'])->name('refaccionesEnc');
    Route::get('tabla-salidasEnc', [controladorEncargado::class, 'tableSalidas'])->name('salidasEnc');
    Route::get('tabla-comprasEnc', [controladorEncargado::class, 'tableCompras'])->name('comprasEnc');
    Route::get('tabla-solicitudEnc', [controladorEncargado::class, 'tableSolicitud'])->name('solicitudesEnc');
    Route::get('form-solicitud', [controladorEncargado::class, 'createSolicitud'])->name('createSolicitud');

    //RUTAS BD
    Route::post('insert-unidad',[controladorBD::class,'insertUnidad'])->name('insertUnidad');
    Route::post('solicitud',[controladorEncargado::class,'insertSolicitud'])->name('insertSolicitud');
    Route::post('insert-User',[controladorBD::class,'insertUser'])->name('insertUser');
    //Route::put('update-unidad/{id}',[controladorBD::class,'updateUnidad'])->name('updateUnidad');
});