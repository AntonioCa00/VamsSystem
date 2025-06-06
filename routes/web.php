<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login;
use App\Http\Controllers\Compras\controladorGerenciaGen;
use App\Http\Controllers\Compras\controladorCompras;
use App\Http\Controllers\Compras\controladorSolic;
use App\Http\Controllers\Compras\controladorGtArea;
use App\Http\Controllers\Compras\controladorAlm;
use App\Http\Controllers\Mantenimiento\controladorMante;

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
        Route::get('cotizaciones/Finanzas/{id}',[controladorGerenciaGen::class,'cotizacionesFin'])->name('verCotizaF');
        Route::get('unidades/GerenciaGen',[controladorGerenciaGen::class,'unidadesGerGen'])->name('unidadesGerGen');
        Route::get('consulta/Cotizaciones/{id}',[controladorGerenciaGen::class,'cotizaciones'])->name('verCotizaciones');
        Route::get('reportesGerencia',[controladorGerenciaGen::class,'reportes'])->name('reportes');
        Route::get('compras/GerenciaGen',[controladorGerenciaGen::class,'compras'])->name('comprasGerGen');
        Route::get('pagos/GerenciaGen',[controladorGerenciaGen::class,'pagos'])->name('pagosGerGen');
        Route::get('reportesGerencia/Unidades',[controladorGerenciaGen::class,'reporteUnidades'])->name('reporteUnidadesGer');

        //------------------------RUTAS CON ACCIONES EN BD------------------------//

        Route::put('select-cotizaF/{id}/{sid}',[controladorGerenciaGen::class,'selectCotizaF'])->name('selectCotizaF');
        Route::delete('delete-cotizacionF/{id}/{rid}',[controladorGtArea::class,'deleteCotizaF'])->name('deleteCotizacionF');
        Route::post('insert-User',[controladorGerenciaGen::class,'insertUser'])->name('insertUser');
        Route::put('update-user/{id}', [controladorGerenciaGen::class, 'updateUser'])->name('updateUser');
        Route::put('delete-user/{id}',[controladorGerenciaGen::class,'deleteUser'])->name('deleteUser');
        Route::delete('delete-solicitud/{id}',[controladorGerenciaGen::class,'deleteSolicitud'])->name('deleteSolicitudGG');
        Route::post('reportesGerencia/Requisiciones',[controladorGerenciaGen::class,'reporteReq'])->name('reportesReqGer');
        Route::post('reportesGerencia/OrdenesCompra',[controladorGerenciaGen::class,'reporteOrd'])->name('reportesOrdGer');
    });

    Route::middleware(['check.role:Compras'])->group(function () {
        //------------------------RUTAS DE LAS VISTAS------------------------//

        //RUTAS ADMIN-COMPRAS
        Route::get('corte/Compras',[controladorCompras::Class,'corteSemanal'])->name('corte');
        Route::get('editarArticulos/Compras/{id}',[controladorCompras::class,'editarArti'])->name('editarArtComp');
        Route::get('inicio/Compras', [controladorCompras::class, 'index'])->name('index');
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
        Route::get('ordenCompra/{id}',[controladorCompras::class,'createOrdenCompra'])->name('ordenCompra');
        Route::get('pagosFijos/Compras',[controladorCompras::class,'tablePagosFijos'])->name('pagosFijos');
        Route::get('ordenesCompras',[controladorCompras::class,'tableordenesCompras'])->name('ordenesCompras');
        Route::get('reportesCompras',[controladorCompras::class,'reportes'])->name('reportesAdm');
        Route::get('reportesCompras/Unidades',[controladorCompras::class,'reporteUnidades'])->name('reporteUnidadesCom');
        Route::get('ordenPago/Crear',[controladorCompras::class,'crearOrdenPago'])->name('crearOrdenPago');

        //------------------------RUTAS CON ACCIONES EN BD------------------------//

        //RUTAS ADMIN-COMPRAS
        Route::post('corte-compras',[controladorCompras::class,'createCorte'])->name('createCorte');
        Route::put('editArticulo/Compras/{id}',[controladorCompras::class,'editarArt'])->name('editarArtCompras');
        Route::delete('rechazarArticulo/Compras/{id}/{rid}',[controladorCompras::class,'rechazaArt'])->name('rechazaArtCompras');
        Route::put('aprobarArt/Compras/{rid}',[controladorCompras::class,'aprobar'])->name('aprobarCompras');
        Route::post('insert-unidad',[controladorCompras::class,'insertUnidad'])->name('insertUnidad');
        Route::put('update-unidad/{id}',[controladorCompras::class, 'updateUnidad'])->name('updateUnidad');
        Route::put('delete-unidad/{id}',[controladorCompras::class,'deleteUnidad'])->name('deleteUnidad');
        Route::put('baja-unidad/{id}',[controladorCompras::class,'bajaUnidad'])->name('bajaUnidad');
        Route::put('activ-unidad/{id}',[controladorCompras::class,'activateUnidad'])->name('activateUnidad');
        Route::put('validar-soli/{id}',[controladorCompras::class,'validarSoli'])->name('validSoli');
        Route::post('insert-compra',[controladorCompras::class,'insertCompra'])->name('insertCompra');
        Route::post('insert-cotiza',[controladorCompras::class,'insertCotiza'])->name('insertCotiza');
        Route::delete('delete-cotiza/{id}/{rid}',[controladorCompras::class,'deleteCotiza'])->name('deleteCotiza');
        Route::post('insert-proveedor',[controladorCompras::class,'insertProveedor'])->name('insertProveedor');
        Route::put('update-proveedor/{id}',[controladorCompras::class,'updateProveedor'])->name('updateProveedor');
        Route::put('delete-proveedor/{id}',[controladorCompras::class,'deleteProveedor'])->name('deleteProveedor');
        Route::put('deleteOrd/{id}/{sid}',[controladorCompras::class,'deleteOrd'])->name('deleteOrd');
        Route::post('array-ordenCom',[controladorCompras::class,'ArrayOrdenComp'])->name('arrayOrdenCom');
        Route::delete('delete-arrayOrden/{index}',[controladorCompras::class,'deleteArray'])->name('eliminarElemOrden');
        Route::post('ordenCompra/{cid}/{rid}',[controladorCompras::class,'insertOrdenCom'])->name('createOrdenCompra');
        Route::put('finalizarRequisicion/{id}',[controladorCompras::class,'FinalizarReq'])->name('FinalizarReq');
        Route::post('solicitudes/Compras/{filt}',[controladorCompras::class,'filtrarSolicitudes'])->name('filtrarSolic');
        Route::post('servicio/create',[controladorCompras::class,'createServicio'])->name('createServicioC');
        Route::put('update/servicioC/{id}',[controladorCompras::class,'editServicio'])->name('editServicioC');
        Route::delete('delete/servicioC/{id}',[controladorCompras::class,'deleteServicioC'])->name('deleteServicioC');
        Route::post('update/pagoC/{id}',[controladorCompras::class,'updatePagp'])->name('updatePagoC');
        Route::post('pago/create',[controladorCompras::class,'createPago'])->name('createPagoC');
        Route::put('pago/update/{id}',[controladorCompras::class,'updatePago'])->name('updatePagoC');
        Route::delete('pago/delete/{id}',[controladorCompras::class,'deletePago'])->name('deletePagoC');
        Route::post('reporteEncAdm',[controladorCompras::class,'reporteEnc'])->name('reporteEncargadoAdm');
        Route::post('reporteUniAdm',[controladorCompras::class,'reporteUnid'])->name('reporteUnidadAdm');
        Route::post('reportesCompras/Requisiciones',[controladorCompras::class,'reporteReq'])->name('reportesReqCom');
        Route::post('reportesCompras/OrdenesCompra',[controladorCompras::class,'reporteOrd'])->name('reportesOrdCom');
        Route::post('reportesCompras/OrdenesPago',[controladorCompras::class,'reportePagos'])->name('reportesPagos');
        Route::get('reportesCompras/Proveedores',[controladorCompras::class, 'reporteProveedores'])->name('reportesProveedores');
        Route::put('actuaizar/proveedores',[controladorCompras::class,'actualizarProveedores'])->name('actualizarProveedores');
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
        Route::get('requisiciones/GtArea',[controladorGtArea::class,'tableRequisicion'])->name('requisicionesDir');
        Route::get('solicitud/GtArea/form', [controladorGtArea::class, 'createSolicitud'])->name('createSolicitudDir');
        Route::get('solicitud/edit/Dir/{id}',[controladorGtArea::class,'editReq'])->name('editReqDir');
        Route::get('pagosF/GtArea',[controladorGtArea::class,'tablePagosFijos'])->name('pagosFDir');
        Route::get('pagos/GtArea/form',[controladorGtArea::class,'createPago'])->name('crearPagosDir');
        Route::get('solicitudes/GtArea',[controladorGtArea::class,'tableSolicitud'])->name('solicitudesDir');
        Route::get('pagos/GtArea',[controladorGtArea::class,'tablePagos'])->name('pagosDir');
        Route::get('aprobar/articulos/{id}',[controladorGtArea::class,'aprobarArt'])->name('aprobarArt');
        Route::get('cotizaciones/{id}',[controladorGtArea::class,'cotizaciones'])->name('verCotiza');
        Route::get('aprobarCotizacion/{id}',[controladorGtArea::class,'aprobCotiza'])->name('aprobCotiza');
        Route::get('ordenesCompras/GtArea',[controladorGtArea::class,'tableOrdenesCompras'])->name('ordenesComprasDir');

        //------------------------MODULO DE MANTENIMIENTO--------------------------//
        Route::get('mantenimiento/GtArea',[controladorGtArea::class,'mantenimiento'])->name('manteni');
        Route::get('mantenimiento/informacion/{id}',[controladorGtArea::class,'infoMantenimiento'])->name('infoMantenimiento');

        //------------------------RUTAS CON ACCIONES EN BD------------------------//

        //RUTAS GERENTES DE AREA
        Route::post('array-solicitud/Dir',[controladorGtArea::class,'ArraySolicitud'])->name('arraySoliDir');
        Route::post('edit-array/Dir/{index}',[controladorGtArea::class,'editArray'])->name('editArrayDir');
        Route::delete('delete-array/Dir/{index}',[controladorGtArea::class,'deleteArray'])->name('eliminarElementoDir');
        Route::post('requisicion/Dir', [controladorGtArea::class, 'requisicion'])->name('requisicionDir');
        Route::put('update-solici/Dir/{id}',[controladorGtArea::class,'updateSolicitud'])->name('updateSolicitudDir');
        Route::post('create-articulo/Dir/{id}',[controladorGtArea::class,'createArt'])->name('createArtDir');
        Route::put('update-articulo/Dir/{id}',[controladorGtArea::class,'updateArt'])->name('updateArtDir');
        Route::delete('delete-Articulo/Dir/{id}/{rid}',[controladorGtArea::class,'deleteArt'])->name('deleteArtDir');
        Route::post('servicio',[controladorGtArea::class,'createServicio'])->name('createServicio');
        Route::put('edit-servicio/{id}',[controladorGtArea::class,'editServicio'])->name('editServicio');
        Route::delete('delete-servicio/{id}',[controladorGtArea::class,'deleteServicio'])->name('deleteServicio');
        Route::post('pago',[controladorGtArea::class,'insertPago'])->name('createPago');
        Route::put('update-pago/{id}',[controladorGtArea::class,'updatePago'])->name('updatePago');
        Route::post('servicio/Dir',[controladorGtArea::class,'createServicio'])->name('createServicioDir');
        Route::post('pago/dir',[controladorGtArea::class,'insertPago'])->name('createPagoDir');
        Route::put('update-pago/Dir/{id}',[controladorGtArea::class,'updatePago'])->name('updatePagoDir');
        Route::put('update-proveedorD/{id}',[controladorGtArea::class,'updateProveedor'])->name('updateProveedorDir');
        Route::put('delete-proveedorD/{id}',[controladorGtArea::class,'deleteProveedor'])->name('deleteProveedorDir');
        Route::put('deleteReq/{id}',[controladorGtArea::class, 'deleteReq'])->name('deleteReq');
        Route::put('validar/{id}',[controladorGtArea::class,'validarRequisicion'])->name('validar');
        Route::put('select-cotiza/{id}/{sid}',[controladorGtArea::class,'selectCotiza'])->name('selectCotiza');
        Route::post('reporteEnc',[controladorGtArea::class,'reporteEnc'])->name('reporteEncargado');
        Route::post('reporteUni',[controladorGtArea::class,'reporteUnid'])->name('reporteUnidad');
        Route::get('reporteGen',[controladorGtArea::class,'reporteGen'])->name('reporteGeneral');
        Route::put('editArticulo/{id}',[controladorGtArea::class,'editarArt'])->name('editarArt');
        Route::delete('rechazarArticulo/{id}',[controladorGtArea::class,'rechazaArt'])->name('rechazaArt');
        Route::put('aprobarArt/{rid}',[controladorGtArea::class,'aprobar'])->name('aprobar');
        Route::put('rechazaFinanzas/{id}/{sid}',[controladorGtArea::class,'rechazarFin'])->name('rechazaFin');
        Route::delete('delete-cotizacion/{id}/{rid}',[controladorGtArea::class,'deleteCotiza'])->name('deleteCotizacion');
        Route::put('registrar-pago/{id}',[controladorGtArea::class,'registrarPago'])->name('registrarPago');
        Route::put('delete-pago/Dir/{id}',[controladorGtArea::class,'deletePagos'])->name('deletePagoDir');
        Route::put('finalizarCompra/{id}',[controladorGtArea::class,'finalizarC'])->name('FinalizarC');
        Route::put('deleteComprobante-pago/{id}',[controladorGtArea::class,'deleteComprobantePago'])->name('deleteComprobantePago');

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
        Route::get('pagos',[controladorSolic::class,'tablePagosFijos'])->name('pagos');
        Route::get('pagos/form',[controladorSolic::class,'createPago'])->name('crearPagos');
        Route::get('solicitud', [controladorSolic::class, 'tableRequisicion'])->name('solicitudesSoli');
        Route::get('requisiciones/consulta',[controladorSolic::class,'tableSolicitudes'])->name('requsicionesConta');
        Route::get('aprobarCotizaciones/{id}',[controladorSolic::class,'cotizaciones'])->name('cotizacionesSolic');
        Route::get('solicitud/form', [controladorSolic::class, 'createSolicitud'])->name('createSolicitud');
        Route::get('solicitud/edit/{id}',[controladorSolic::class,'editReq'])->name('editReq');
        Route::get('unidades',[controladorSolic::class,'tableUnidades'])->name('unidadesSoli');
        Route::get('/solicitud/form-Unidad', [controladorSolic::class, 'createUnidad'])->name('CreateUnidadSoli');
        Route::get('solicitud/edit-Unidad/{id}/{from}', [controladorSolic::class, 'editUnidad'])->name('editUnidadSoli');
        Route::get('solicitud/activ-Unidad',[controladorSolic::class,'activarUnidad'])->name('actUnuiSoli');
        Route::get('validaciones',[controladorSolic::class,'validaciones'])->name('validaciones');
        Route::get('ordenes/Compras',[controladorSolic::class,'tableOrdenes'])->name('ordenesdecompras');
        Route::get('ordenes/Pagos',[controladorSolic::class,'tablePagos'])->name('ordenesdePago');

        //------------------------VISTAS MODULO DE MANTENIMIENTO--------------------------//
        Route::get('mantenimiento',[controladorMante::class,'mantenimiento'])->name('manteniento');
        Route::get('mantenimiento/informacion/{id}',[controladorMante::class,'infoMantenimiento'])->name('infoMantenimiento');
        Route::get('calendario', [controladorMante::class, 'calendario'])->name('calendar');
        Route::get('calendario/programacion', [controladorMante::class, 'getEvents'])->name('programa');

        //------------------------RUTAS CON ACCIONES EN BD------------------------//
        Route::post('array-solicitud',[controladorSolic::class,'ArraySolicitud'])->name('arraySoli');
        Route::post('edit-array/{index}',[controladorSolic::class,'editArray'])->name('editArray');
        Route::delete('delete-array/{index}',[controladorSolic::class,'deleteArray'])->name('eliminarElemento');
        Route::post('solicitud',[controladorSolic::class,'insertSolicitud'])->name('insertSolicitud');
        Route::delete('delete-solici/{id}',[controladorSolic::class,'deleteSolicitud'])->name('deleteSolicitud');
        Route::put('update-solici/{id}',[controladorSolic::class,'updateSolicitud'])->name('updateSolicitud');
        Route::post('create-articulo/{id}',[controladorSolic::class,'createArt'])->name('createArt');
        Route::put('update-articulo/{id}',[controladorSolic::class,'updateArt'])->name('updateArt');
        Route::delete('delete-Articulo/{id}/{rid}',[controladorSolic::class,'deleteArt'])->name('deleteArt');
        Route::post('servicio',[controladorSolic::class,'createServicio'])->name('createServicio');
        Route::put('edit-servicio/{id}',[controladorSolic::class,'editServicio'])->name('editServicio');
        Route::delete('delete-servicio/{id}',[controladorSolic::class,'deleteServicio'])->name('deleteServicio');
        Route::post('pago',[controladorSolic::class,'insertPago'])->name('createPago');
        Route::put('update-pago/{id}',[controladorSolic::class,'updatePago'])->name('updatePago');
        Route::delete('delete-pago/{id}',[controladorSolic::class,'deletePago'])->name('deletePago');
        Route::post('array-solicitudAlm',[controladorSolic::class,'ArraySolicitudAlm'])->name('arraySoliAlm');
        Route::delete('delete-arraySolicAl/{index}',[controladorSolic::class,'deleteArraySolAlm'])->name('eliminarElementoSolic');
        Route::post('requisicion', [controladorSolic::class, 'requisicion'])->name('requisicion');
        Route::post('requisicion-Alm',[controladorSolic::class,'requisicionAlm'])->name('requisicionAlm');
        Route::post('insert-unidadSol',[controladorSolic::class,'insertUnidad'])->name('insertUnidadSoli');
        Route::put('update-unidadSol/{id}',[controladorSolic::class, 'updateUnidad'])->name('updateUnidadSoli');
        Route::put('delete-UnidadSol/{id}',[controladorSolic::class,'deleteUnidad'])->name('deleteUnidadSoli');
        Route::put('baja-Unidad/{id}',[controladorSolic::class,'bajaUnidad'])->name('bajaUnidadSoli');
        Route::put('activ-unidadSoli/{id}',[controladorSolic::class,'activateUnidad'])->name('activateUnidadSoli');
        Route::put('select-cotiza/Solic/{id}/{sid}',[controladorSolic::class,'selectCotiza'])->name('selectCotizaSolic');
        Route::put('rechazaContabilidad/{id}/{sid}',[controladorSolic::class,'rechazarCont'])->name('rechazarCont');
        Route::post('validaciones',[controladorSolic::class,'crearValidacion'])->name('crearValidacion');

        //------------------------RUTAS MANTENIMIENTO CON ACCIONES EN BD------------------------//
        Route::post('actualizar-kms',[controladorMante::class,'actualizarkms'])->name('kilometrajes');
        Route::put('actualiza-km/{id}',[controladorMante::class,'updateKilom'])->name('updateKilom');
        Route::post('programarMant/{id}',[controladorMante::class,'programMant'])->name('programar');
        Route::post('programarMantC',[controladorMante::class,'programMantC'])->name('programarC');
        Route::post('reprogramarMant/{unidad}/{progra}',[controladorMante::class,'reprogramMant'])->name('reprogramar');
        Route::post('registrarMant/{progra}',[controladorMante::class,'registrarMant'])->name('registrarM');
    });
});
