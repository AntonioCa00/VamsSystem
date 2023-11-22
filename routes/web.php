<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login;
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
//Route::get('/',[Login::class,'login'])->name('login')->middleware('alreadyLoggedIn');
Route::post('validar-login',[Login::class,'loginUser'])->name('validate');
Route::get('logout',[Login::class,'logout'])->name('logout');

Route::middleware(['alreadyLoggedIn'])->group(function () {
    Route::get('/',[Login::class,'login'])->name('login');
});

Route::middleware(['check.role:Compras'])->group(function () {
    //------------------------RUTAS DE LAS VISTAS------------------------//

    //RUTAS ADMIN-COMPRAS
    Route::get('inicio/Compras', [controladorAdmin::class, 'index'])->name('index');
    Route::get('graficas', [controladorAdmin::class, 'charts'])->name('charts');
    Route::get('almacen/Compras', [controladorAdmin::class, 'tableRefaccion'])->name('refacciones');
    Route::get('entradas/Compras',[controladorAdmin::class,'tableEntradas'])->name('entradas');
    Route::get('salidas/Compras', [controladorAdmin::class, 'tableSalidas'])->name('salidas');
    Route::get('ordenesC/Compras', [controladorAdmin::class, 'tableCompras'])->name('compras');
    Route::get('solicitud/Compras', [controladorAdmin::class, 'tableSolicitud'])->name('solicitudes');
    Route::get('proveedores/Compras',[controladorAdmin::class, 'tableProveedor'])->name('proveedores');
    Route::get('form-proveedor',[controladorAdmin::class, 'createProveedor'])->name('createProveedor');
    Route::get('edit-proveedor/{id}',[controladorAdmin::class,'editProveedor'])->name('editProveedor');
    Route::get('activaUnidad',[controladorAdmin::class,'activarUnidad'])->name('actUnui');
    Route::get('form-compra',[controladorAdmin::class,'createCompra'])->name('createCompra');
    Route::get('form/{id}/cotizar',[controladorAdmin::class,'createCotiza'])->name('createCotiza');
    Route::get('ordenCompra/{id}',[controladorAdmin::class,'ordenCompra'])->name('ordenCompra');
    Route::get('ordenesCompras',[controladorAdmin::class,'ordenesCompras'])->name('ordenesCompras');
    
    //------------------------RUTAS CON ACCIONES EN BD------------------------//

    //RUTAS COMPRAS
    Route::put('validar-soli/{id}',[controladorAdmin::class,'validarSoli'])->name('validSoli');
    Route::post('insert-compra',[controladorAdmin::class,'insertCompra'])->name('insertCompra');
    Route::post('insert-cotiza',[controladorAdmin::class,'insertCotiza'])->name('insertCotiza');
    Route::delete('delete-cotiza/{id}',[controladorAdmin::class,'deleteCotiza'])->name('deleteCotiza');
    Route::post('insert-proveedor',[controladorAdmin::class,'insertProveedor'])->name('insertProveedor');
    Route::put('update-proveedor/{id}',[controladorAdmin::class,'updateProveedor'])->name('updateProveedor');
    Route::put('delete-proveedor/{id}',[controladorAdmin::class,'deleteProveedor'])->name('deleteProveedor');
    Route::put('deleteReq/{id}',[controladorAdmin::class, 'deleteReq'])->name('deleteReq');
    Route::put('deleteOrd/{id}',[controladorAdmin::class,'deleteOrd'])->name('deleteOrd');
    Route::post('array-ordenCom',[controladorAdmin::class,'ArrayOrdenComp'])->name('arrayOrdenCom');
    Route::delete('delete-arrayOrden/{index}',[controladorAdmin::class,'deleteArray'])->name('eliminarElemOrden');
    Route::post('ordenCompra',[controladorAdmin::class,'insertOrdenCom'])->name('createOrdenCompra');
});

Route::middleware(['check.role:Direccion'])->group(function () {
    //------------------------RUTAS DE LAS VISTAS------------------------//

    //RUTAS DIRECCION   
    Route::get('inicio/Direccion',[controladorDir::class,'index'])->name('indexDir');
    Route::get('unidades/Direccion', [controladorDir::class, 'tableUnidad'])->name('unidades');
    Route::get('form-Unidad', [controladorDir::class, 'createUnidad'])->name('CreateUnidad');
    Route::get('edit-Unidad/{id}', [controladorDir::class, 'editUnidad'])->name('editUnidad');
    Route::get('activ-Unidad',[controladorDir::class,'activarUnidad'])->name('actUnui');
    Route::get('entradas/Direccion',[controladorDir::class,'tableEntradas'])->name('entradasDir');
    Route::get('salidas/Direccion', [controladorDir::class, 'tableSalidas'])->name('salidasDir');
    Route::get('proveedores/Direccion',[controladorDir::class,'tableProveedores'])->name('proveedoresDir');
    Route::get('edit-proveedorD/{id}',[controladorDir::class,'editProveedor'])->name('editProveedorDir');
    Route::get('almacen/Direccion', [controladorDir::class, 'tableRefaccion'])->name('refaccionesDir');
    Route::get('solicitudes/Direccion',[controladorDir::class,'tableSolicitud'])->name('solicitudesDir');
    Route::get('usuarios/Direccion', [controladorDir::class, 'tableEncargado'])->name('encargados');
    Route::get('form-user',[controladorDir::class,'createUser'])->name('createUser');
    Route::get('edit-user/{id}', [controladorDir::class, 'editUser'])->name('editUser');
    Route::get('cotizaciones/{id}',[controladorDir::class,'cotizaciones'])->name('verCotiza');

    //------------------------RUTAS CON ACCIONES EN BD------------------------//

    //RUTAS DIRECCION
    Route::post('insert-unidad',[controladorDir::class,'insertUnidad'])->name('insertUnidad');
    Route::put('update-unidad/{id}',[controladorDir::class, 'updateUnidad'])->name('updateUnidad');
    Route::put('delete-Unidad/{id}',[controladorDir::class,'deleteUnidad'])->name('deleteUnidad');
    Route::put('baja-Unidad/{id}',[controladorDir::class,'bajaUnidad'])->name('bajaUnidad');
    Route::put('activ-unidad/{id}',[controladorDir::class,'activateUnidad'])->name('activateUnidad');
    Route::put('update-proveedorD/{id}',[controladorDir::class,'updateProveedor'])->name('updateProveedorDir');
    Route::put('delete-proveedorD/{id}',[controladorDir::class,'deleteProveedor'])->name('deleteProveedorDir');
    Route::post('insert-User',[controladorDir::class,'insertUser'])->name('insertUser');
    Route::put('update-user/{id}', [controladorDir::class, 'updateUser'])->name('updateUser');
    Route::put('delete-user/{id}',[controladorDir::class,'deleteUser'])->name('deleteUser');
    Route::put('select-cotiza/{id}/{sid}',[controladorDir::class,'selectCotiza'])->name('selectCotiza');    
});

Route::middleware(['check.role:Almacen'])->group(function () {
    //------------------------RUTAS DE LAS VISTAS------------------------//

    //RUTAS ALMACEN
    Route::get('inicio/Almacen',[controladorAlm::class,'index'])->name('indexAlm');
    Route::get('solicitudes/Almacen',[controladorAlm::class,'requisiciones'])->name('requisicionesAlm');
    Route::get('regEntrada/Almacen/{id}',[controladorAlm::class, 'createEntrada'])->name('createEntrada');
    Route::get('entradas/Almacen',[controladorAlm::class,'entradas'])->name('entradasAlm');
    Route::get('almacen/Almacen',[controladorAlm::class, 'almacen'])->name('almacenAlm');
    Route::get('refaccion',[controladorAlm::class,'createRefaccion'])->name('createRefaccion');
    Route::get('edit-refaccion/{id}',[controladorAlm::class,'editRefaccion'])->name('editRefaccion');
    Route::get('salidas/Almacen',[controladorAlm::class, 'salidas'])->name('salidasAlm');
    Route::get('crearSalida/{id}',[controladorAlm::class,'crearSalida'])->name('crearSalida');
    Route::get('solicitudesAlm/Almacen',[controladorAlm::class,'requisicionesAlm'])->name('requisicionesAlma');
    Route::get('crearSalidaAlm/{id}',[controladorAlm::class,'crearSalidaAlm'])->name('crearSalidaAlm'); 

    //------------------------RUTAS CON ACCIONES EN BD------------------------//

    //RUTAS ALMACEN
    Route::post('insert-refaccion',[controladorAlm::class,'insertRefaccion'])->name('insertRefaccion');
    Route::put('update-refaccion/{id}',[controladorAlm::class,'updateRefaccion'])->name('updateRefaccion');
    Route::put('delete-refaccion/{id}',[controladorAlm::class,'deleteRefaccion'])->name('deleteRefaccion');
    Route::post('array-Entrada',[controladorAlm::class,'ArrayRefaccion'])->name('arrayEntrada');
    Route::delete('delete-arrayEnt/{index}',[controladorAlm::class,'deleteArrayRef'])->name('deleteArrayRef');
    Route::post('entradaAlmacen/{id}',[controladorAlm::class,'entradaAlm'])->name('entradaAlm');
    Route::post('array-Salida',[controladorAlm::class,'ArraySalida'])->name('ArraySalida');
    Route::delete('delete-ArraySal/{index}',[controladorAlm::class,'deleteArraySal'])->name('deleteArraySal');
    Route::post('array-SalidaAlm',[controladorAlm::class,'ArryaSalidaAlm'])->name('ArraySalidaAlm');
    Route::delete('delete-ArraySalAlm/{index}',[controladorAlm::class,'deleteArraySalAlm'])->name('deleteArraySalAlm');
    Route::get('createSalida/{id}',[controladorAlm::class,'createSalida'])->name('createSalida');
    Route::get('createSalidaAlm/{id}',[controladorAlm::class,'createSalidaAlm'])->name('createSalidaAlm');
});

Route::middleware(['check.role:General'])->group(function () {
    //------------------------RUTAS DE LAS VISTAS------------------------//

    Route::get('inicio',[controladorSolic::class,'index'])->name('indexSoli');
    Route::get('graficasSoli', [controladorSolic::class, 'charts'])->name('chartsEnc');
    Route::get('almacen', [controladorSolic::class, 'almacen'])->name('almacenSoli');
    Route::get('salidas', [controladorSolic::class, 'tableSalidas'])->name('salidasSoli');
    Route::get('solicitud', [controladorSolic::class, 'tableRequisicion'])->name('solicitudesSoli');
    Route::get('solicitud/form', [controladorSolic::class, 'createSolicitud'])->name('createSolicitud');
    Route::get('solicitud/almacen',[controladorSolic::class,'solicitudAlm'])->name('solicitudAlm');

    //------------------------RUTAS CON ACCIONES EN BD------------------------//
    Route::post('array-solicitud',[controladorSolic::class,'ArraySolicitud'])->name('arraySoli');
    Route::delete('delete-array/{index}',[controladorSolic::class,'deleteArray'])->name('eliminarElemento');
    Route::post('solicitud',[controladorSolic::class,'insertSolicitud'])->name('insertSolicitud');
    Route::delete('delete-solici/{id}',[controladorSolic::class,'deleteSolicitud'])->name('deleteSolicitud');
    Route::post('array-solicitudAlm',[controladorSolic::class,'ArraySolicitudAlm'])->name('arraySoliAlm');
    Route::delete('delete-arraySolicAl/{index}',[controladorSolic::class,'deleteArraySolAlm'])->name('eliminarElementoSolic');
    Route::post('requisicion', [controladorSolic::class, 'requisicion'])->name('requisicion');
    Route::post('requisicion-Alm',[controladorSolic::class,'requisicionAlm'])->name('requisicionAlm');
});
