<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\controladorBD;

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
Route::get('/',[controladorBD::class,'login'])->name('login');

//RUTAS DE INTERFACES
Route::get('inicio',[controladorBD::class,'index'])->name('index');
Route::get('graficas',[controladorBD::class,'charts'])->name('charts');
Route::get('tabla-unidades',[controladorBD::class,'tableUnidad'])->name('unidades');   
Route::get('tabla-encargados',[controladorBD::class,'tableEncargado'])->name('encargados');   
Route::get('tabla-refacciones',[controladorBD::class,'tableRefaccion'])->name('refacciones');