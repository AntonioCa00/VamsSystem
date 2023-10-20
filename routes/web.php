<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\controladorAdmin;
use App\Http\Controllers\controladorSolic;
use App\Http\Controllers\controladorDir;
use App\Http\Controllers\controladorAlm;

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
Route::get('/',[controladorAdmin::class,'login'])->name('login')->middleware('alreadyLoggedIn');
Route::post('validar-login',[controladorAdmin::class,'loginUser'])->name('validate');
Route::get('logout',[controladorAdmin::class,'logout'])->name('logout');

//RUTAS DE INTERFACES
Route::middleware(['isLoggedIn'])->group(function () {

    //------------------------RUTAS DE LAS VISTAS------------------------

    //RUTAS ADMIN-COMPRAS
    Route::get('inicio/Compras', [controladorAdmin::class, 'index'])->name('index');
    Route::get('graficas', [controladorAdmin::class, 'charts'])->name('charts');
    Route::get('unidades/Compra', [controladorAdmin::class, 'tableUnidad'])->name('unidades');
    Route::get('tabla-refacciones', [controladorAdmin::class, 'tableRefaccion'])->name('refacciones');
    Route::get('tabla-salidas', [controladorAdmin::class, 'tableSalidas'])->name('salidas');
    Route::get('tabla-compras', [controladorAdmin::class, 'tableCompras'])->name('compras');
    Route::get('tabla-solicitud', [controladorAdmin::class, 'tableSolicitud'])->name('solicitudes');
    Route::get('proveedores',[controladorAdmin::class, 'tableProveedor'])->name('proveedores');
    Route::get('form-proveedor',[controladorAdmin::class, 'createProveedor'])->name('createProveedor');
    Route::get('edit-proveedor/{id}',[controladorAdmin::class,'editProveedor'])->name('editProveedor');
    Route::get('form-Unidad', [controladorAdmin::class, 'createUnidad'])->name('CreateUnidad');
    Route::get('edit-Unidad/{id}', [controladorAdmin::class, 'editUnidad'])->name('editUnidad');
    Route::get('form-compra',[controladorAdmin::class,'createCompra'])->name('createCompra');
    Route::get('form/{id}/cotizar',[controladorAdmin::class,'createCotiza'])->name('createCotiza');
    Route::get('ordenCompra/{id}',[controladorAdmin::class,'ordenCompra'])->name('ordenCompra');
    Route::get('ordenesCompras',[controladorAdmin::class,'ordenesCompras'])->name('ordenesCompras');

    //RUTAS SOLICITANTE
    Route::get('inicio',[controladorSolic::class,'index'])->name('indexSoli');
    Route::get('graficasSoli', [controladorSolic::class, 'charts'])->name('chartsEnc');
    Route::get('almacen', [controladorSolic::class, 'almacen'])->name('almacenSoli');
    Route::get('salidas', [controladorSolic::class, 'tableSalidas'])->name('salidasSoli');
    Route::get('solicitud', [controladorSolic::class, 'tableRequisicion'])->name('solicitudesSoli');
    Route::get('solicitud/form', [controladorSolic::class, 'createSolicitud'])->name('createSolicitud');

    //RUTAS DIRECCION   
    Route::get('inicio/Direccion',[controladorDir::class,'index'])->name('indexDir');
    Route::get('solicitudes/Direccion',[controladorDir::class,'tableSolicitud'])->name('solicitudesDir');
    Route::get('tabla-encargados', [controladorDir::class, 'tableEncargado'])->name('encargados');
    Route::get('form-user',[controladorDir::class,'createUser'])->name('createUser');
    Route::get('edit-user/{id}', [controladorDir::class, 'editUser'])->name('editUser');
    Route::get('cotizaciones/{id}',[controladorDir::class,'cotizaciones'])->name('verCotiza');

    //RUTAS ALMACEN
    Route::get('inicio/Almacen',[controladorAlm::class,'index'])->name('indexAlm');
    Route::get('solicitudes/Almacen',[controladorAlm::class,'requisiciones'])->name('requisicionesAlm');
    Route::get('entradas/Almacen',[controladorAlm::class,'entradas'])->name('entradasAlm');
    Route::get('almacen/Almacen',[controladorAlm::class, 'almacen'])->name('almacenAlm');
    Route::get('salidas/Almacen',[controladorAlm::class, 'salidas'])->name('salidasAlm');

    //------------------------RUTAS CON ACCIONES EN BD------------------------

    //RUTAS ADMIN
    Route::post('insert-unidad',[controladorAdmin::class,'insertUnidad'])->name('insertUnidad');
    Route::put('update-unidad/{id}',[controladorAdmin::class, 'updateUnidad'])->name('updateUnidad');
    Route::put('delete-Unidad/{id}',[controladorAdmin::class,'deleteUnidad'])->name('deleteUnidad');
    Route::post('insert-User',[controladorAdmin::class,'insertUser'])->name('insertUser');
    Route::put('validar-soli/{id}',[controladorAdmin::class,'validarSoli'])->name('validSoli');
    Route::post('insert-compra',[controladorAdmin::class,'insertCompra'])->name('insertCompra');
    Route::post('insert-cotiza',[controladorAdmin::class,'insertCotiza'])->name('insertCotiza');
    Route::delete('delete-cotiza/{id}',[controladorAdmin::class,'deleteCotiza'])->name('deleteCotiza');
    Route::post('insert-proveedor',[controladorAdmin::class,'insertProveedor'])->name('insertProveedor');
    Route::put('update-proveedor/{id}',[controladorAdmin::class,'updateProveedor'])->name('updateProveedor');
    Route::put('delete-proveedor/{id}',[controladorAdmin::class,'deleteProveedor'])->name('deleteProveedor');
    Route::post('array-ordenCom',[controladorAdmin::class,'ArrayOrdenComp'])->name('arrayOrdenCom');
    Route::delete('delete-arrayOrden/{index}',[controladorAdmin::class,'deleteArray'])->name('eliminarElemOrden');
    Route::post('ordenCompra',[controladorAdmin::class,'insertOrdenCom'])->name('createOrdenCompra');

    //RUTAS SOLICITANTE
    Route::post('array-solicitud',[controladorSolic::class,'ArraySolicitud'])->name('arraySoli');
    Route::delete('delete-array/{index}',[controladorSolic::class,'deleteArray'])->name('eliminarElemento');
    Route::post('solicitud',[controladorSolic::class,'insertSolicitud'])->name('insertSolicitud');
    Route::delete('delete-solici/{id}',[controladorSolic::class,'deleteSolicitud'])->name('deleteSolicitud');
    Route::post('requisicion', [controladorSolic::class, 'requisicion'])->name('requisicion');

    //RUTAS DIRECCION
    Route::put('update-user/{id}', [controladorDir::class, 'updateUser'])->name('updateUser');
    Route::put('delete-user/{id}',[controladorDir::class,'deleteUser'])->name('deleteUser');
    Route::put('select-cotiza/{id}/{sid}',[controladorDir::class,'selectCotiza'])->name('selectCotiza');    

    //RUTAS ALMACEN
    Route::get('refaccion',[controladorAlm::class,'createRefaccion'])->name('createRefaccion');
});