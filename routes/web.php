<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login;
use App\Http\Controllers\controladorGerenciaGen;
use App\Http\Controllers\controladorCompras;
use App\Http\Controllers\controladorSolic;
use App\Http\Controllers\controladorGtArea;
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
Route::post('validar-login',[Login::class,'loginUser'])->name('validate');
Route::get('logout',[Login::class,'logout'])->name('logout');
Route::middleware(['alreadyLoggedIn'])->group(function () {
    Route::get('/',[Login::class,'login'])->name('login');
});
Route::middleware(['authcheck'])->group(function () {

    Route::middleware(['check.role:Gerencia General'])->group(function () {
        //------------------------RUTAS DE LAS VISTAS------------------------//
    
        //RUTAS GERENCIA GENERAL
        Route::get('inicio/GerenciaGeneral',[controladorGerenciaGen::class,'index'])->name('indexGerenciaGen');
        Route::get('usuarios', [controladorGerenciaGen::class, 'tableEncargado'])->name('encargados');
        Route::get('form-user',[controladorGerenciaGen::class,'createUser'])->name('createUser');
        Route::get('edit-user/{id}', [controladorGerenciaGen::class, 'editUser'])->name('editUser');
        Route::get('solicitud/GerenciaGen', [controladorGerenciaGen::class, 'tableSolicitud'])->name('solicitudesGerGen');
        Route::get('unidades/GerenciaGen',[controladorGerenciaGen::class,'unidadesGerGen'])->name('unidadesGerGen');
        Route::get('consulta/Cotizaciones/{id}',[controladorGerenciaGen::class,'cotizaciones'])->name('verCotizaciones');
        Route::get('ordenesCompra/GerenciaGen',[controladorGerenciaGen::class,'ordenesCompras'])->name('ordenesComprasGerGen');

        //------------------------RUTAS CON ACCIONES EN BD------------------------//

        Route::post('insert-User',[controladorGerenciaGen::class,'insertUser'])->name('insertUser');
        Route::put('update-user/{id}', [controladorGerenciaGen::class, 'updateUser'])->name('updateUser');
        Route::put('delete-user/{id}',[controladorGerenciaGen::class,'deleteUser'])->name('deleteUser');
        Route::delete('delete-solicitud/{id}',[controladorGerenciaGen::class,'deleteSolicitud'])->name('deleteSolicitudGG');
    });
    
    Route::middleware(['check.role:Compras'])->group(function () {
        //------------------------RUTAS DE LAS VISTAS------------------------//
    
        //RUTAS ADMIN-COMPRAS
        Route::get('inicio/Compras', [controladorCompras::class, 'index'])->name('index');
        Route::get('graficas', [controladorCompras::class, 'charts'])->name('charts');
        Route::get('almacen/Compras', [controladorCompras::class, 'tableRefaccion'])->name('refacciones');
        Route::get('entradas/Compras',[controladorCompras::class,'tableEntradas'])->name('entradas');
        Route::get('salidas/Compras', [controladorCompras::class, 'tableSalidas'])->name('salidas');
        Route::get('ordenesC/Compras', [controladorCompras::class, 'tableCompras'])->name('compras');
        Route::get('solicitud/Compras', [controladorCompras::class, 'tableSolicitud'])->name('solicitudes');
        Route::get('proveedores/Compras',[controladorCompras::class, 'tableProveedor'])->name('proveedores');
        Route::get('unidades/Compras',[controladorCompras::class,'tableUnidad'])->name('unidades');
        Route::get('form-Unidad', [controladorCompras::class, 'createUnidad'])->name('CreateUnidad');
        Route::get('edit-Unidad/{id}', [controladorCompras::class, 'editUnidad'])->name('editUnidad');
        Route::get('activ-Unidad',[controladorCompras::class,'activarUnidad'])->name('actUnui');
        Route::get('form-proveedor',[controladorCompras::class, 'createProveedor'])->name('createProveedor');
        Route::get('edit-proveedor/{id}',[controladorCompras::class,'editProveedor'])->name('editProveedor');
        Route::get('form-compra',[controladorCompras::class,'createCompra'])->name('createCompra');
        Route::get('form/{id}/cotizar',[controladorCompras::class,'createCotiza'])->name('createCotiza');
        Route::get('ordenCompra/{id}',[controladorCompras::class,'ordenCompra'])->name('ordenCompra');
        Route::get('ordenesCompras',[controladorCompras::class,'ordenesCompras'])->name('ordenesCompras');
        Route::get('reportesAdm',[controladorCompras::class,'reportes'])->name('reportesAdm');
        
        //------------------------RUTAS CON ACCIONES EN BD------------------------//
    
        //RUTAS ADMIN-COMPRAS
        Route::post('insert-unidad',[controladorCompras::class,'insertUnidad'])->name('insertUnidad');
        Route::put('update-unidad/{id}',[controladorCompras::class, 'updateUnidad'])->name('updateUnidad');
        Route::put('delete-Unidad/{id}',[controladorCompras::class,'deleteUnidad'])->name('deleteUnidad');
        Route::put('baja-Unidad/{id}',[controladorCompras::class,'bajaUnidad'])->name('bajaUnidad');
        Route::put('activ-unidad/{id}',[controladorCompras::class,'activateUnidad'])->name('activateUnidad');
        Route::put('validar-soli/{id}',[controladorCompras::class,'validarSoli'])->name('validSoli');
        Route::post('insert-compra',[controladorCompras::class,'insertCompra'])->name('insertCompra');
        Route::post('insert-cotiza',[controladorCompras::class,'insertCotiza'])->name('insertCotiza');
        Route::delete('delete-cotiza/{id}',[controladorCompras::class,'deleteCotiza'])->name('deleteCotiza');
        Route::post('insert-proveedor',[controladorCompras::class,'insertProveedor'])->name('insertProveedor');
        Route::put('update-proveedor/{id}',[controladorCompras::class,'updateProveedor'])->name('updateProveedor');
        Route::put('delete-proveedor/{id}',[controladorCompras::class,'deleteProveedor'])->name('deleteProveedor');
        Route::put('deleteOrd/{id}/{sid}',[controladorCompras::class,'deleteOrd'])->name('deleteOrd');
        Route::post('array-ordenCom',[controladorCompras::class,'ArrayOrdenComp'])->name('arrayOrdenCom');
        Route::delete('delete-arrayOrden/{index}',[controladorCompras::class,'deleteArray'])->name('eliminarElemOrden');
        Route::post('ordenCompra/{cid}/{rid}',[controladorCompras::class,'insertOrdenCom'])->name('createOrdenCompra');
        Route::post('reporteEncAdm',[controladorCompras::class,'reporteEnc'])->name('reporteEncargadoAdm');
        Route::post('reporteUniAdm',[controladorCompras::class,'reporteUnid'])->name('reporteUnidadAdm');
        Route::get('reporteGenAdm',[controladorCompras::class,'reporteGen'])->name('reporteGeneralAdm');
    });
    
    Route::middleware(['check.role:Gerente Area'])->group(function () {
        //------------------------RUTAS DE LAS VISTAS------------------------//
    
        //RUTAS GERENTES DE AREA  
        Route::get('inicio/GtArea',[controladorGtArea::class,'index'])->name('indexDir');
        Route::get('unidades/GtArea', [controladorGtArea::class, 'tableUnidad'])->name('unidadesDir');        
        Route::get('entradas/GtArea',[controladorGtArea::class,'tableEntradas'])->name('entradasDir');
        Route::get('salidas/GtArea', [controladorGtArea::class, 'tableSalidas'])->name('salidasDir');
        Route::get('proveedores/GtArea',[controladorGtArea::class,'tableProveedores'])->name('proveedoresDir');
        Route::get('edit-proveedorD/{id}',[controladorGtArea::class,'editProveedor'])->name('editProveedorDir');
        Route::get('almacen/GtArea', [controladorGtArea::class, 'tableRefaccion'])->name('refaccionesDir');
        Route::get('solicitudes/GtArea',[controladorGtArea::class,'tableSolicitud'])->name('solicitudesDir');
        Route::get('aprobar/articulos/{id}',[controladorGtArea::class,'aprobarArt'])->name('aprobarArt');        
        Route::get('cotizaciones/{id}',[controladorGtArea::class,'cotizaciones'])->name('verCotiza');
        Route::get('aprobarCotizacion/{id}',[controladorGtArea::class,'aprobCotiza'])->name('aprobCotiza');
        Route::get('reportes',[controladorGtArea::class,'reportes'])->name('reportes');
    
        //------------------------RUTAS CON ACCIONES EN BD------------------------//
    
        //RUTAS GERENTES DE AREA
        Route::put('update-proveedorD/{id}',[controladorGtArea::class,'updateProveedor'])->name('updateProveedorDir');
        Route::put('delete-proveedorD/{id}',[controladorGtArea::class,'deleteProveedor'])->name('deleteProveedorDir');        
        Route::put('deleteReq/{id}',[controladorGtArea::class, 'deleteReq'])->name('deleteReq');
        Route::put('validar/{id}',[controladorGtArea::class,'validarRequisicion'])->name('validar');
        Route::put('select-cotiza/{id}',[controladorGtArea::class,'selectCotiza'])->name('selectCotiza');    
        Route::post('reporteEnc',[controladorGtArea::class,'reporteEnc'])->name('reporteEncargado');
        Route::post('reporteUni',[controladorGtArea::class,'reporteUnid'])->name('reporteUnidad');
        Route::get('reporteGen',[controladorGtArea::class,'reporteGen'])->name('reporteGeneral');
        Route::put('editArticulo/{id}',[controladorGtArea::class,'editarArt'])->name('editarArt');
        Route::delete('rechazarArticulo/{id}',[controladorGtArea::class,'rechazaArt'])->name('rechazaArt');
        Route::put('aprobarArt/{rid}',[controladorGtArea::class,'aprobar'])->name('aprobar');
        Route::put('rechazaFinanzas/{id}',[controladorGtArea::class,'rechazarFin'])->name('rechazaFin');
        Route::delete('delete-cotizacion/{id}',[controladorGtArea::class,'deleteCotiza'])->name('deleteCotizacion');
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
        Route::post('createSalida/{id}',[controladorAlm::class,'createSalida'])->name('createSalida');
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
        Route::post('edit-array/{index}',[controladorSolic::class,'editArray'])->name('editArray');
        Route::delete('delete-array/{index}',[controladorSolic::class,'deleteArray'])->name('eliminarElemento');
        Route::post('solicitud',[controladorSolic::class,'insertSolicitud'])->name('insertSolicitud');
        Route::delete('delete-solici/{id}',[controladorSolic::class,'deleteSolicitud'])->name('deleteSolicitud');
        Route::post('array-solicitudAlm',[controladorSolic::class,'ArraySolicitudAlm'])->name('arraySoliAlm');
        Route::delete('delete-arraySolicAl/{index}',[controladorSolic::class,'deleteArraySolAlm'])->name('eliminarElementoSolic');
        Route::post('requisicion', [controladorSolic::class, 'requisicion'])->name('requisicion');
        Route::post('requisicion-Alm',[controladorSolic::class,'requisicionAlm'])->name('requisicionAlm');
    });
});