<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Unidades;
use App\Models\Proveedores;
use App\Models\Comentarios;
use App\Models\Entradas;
use App\Models\Salidas;
use App\Models\Orden_compras;
use App\Models\Requisiciones;
use App\Models\cotizaciones;
use App\Models\Almacen;
use App\Models\Logs;
use App\Models\Articulos;
use App\Models\Pagos_Fijos;
use App\Models\Servicios;
use Carbon\Carbon;
use DB;

class controladorGtArea extends Controller
{

    /*
      TODO: Recopila datos para la visualización de informes de gestión en el área correspondiente.
     
      Este método se encarga de compilar datos detallados de operaciones de compra por mes y totales anuales,
      así como el conteo de requisiciones completas y pendientes. Utiliza la clase Orden_compras para sumar los costos totales
      de las compras realizadas en cada mes del año actual y calcula los totales de compras para el mes y año en curso.
      Además, cuenta las requisiciones en estado 'Comprado' y las requisiciones pendientes que no están 'Compradas' ni 'Rechazadas'
      para el departamento del usuario actual.
     
      Retorna la vista 'GtArea.index' con los datos compilados para informes de gestión.
    */
    public function index(){
        // Datos actuales y preparación de sumas de costos de orden de compra por mes y totales anuales
        $anio_actual = date('Y');

        // Consultas para sumar los costos totales por cada mes del año actual
        $EneroPagos = Pagos_Fijos::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as enero"))
            ->whereBetween('created_at', ["$anio_actual-01-01 00:00:00", "$anio_actual-01-31 23:59:59"])
            ->where('estado','Pagado')
            ->first();
        $EneroCompras = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as enero"))
            ->whereBetween('created_at', ["$anio_actual-01-01 00:00:00", "$anio_actual-01-31 23:59:59"])
            ->first();
        $Enero = $EneroPagos->enero + $EneroCompras->enero;

        $FebreroPagos = Pagos_Fijos::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as febrero"))
            ->whereBetween('created_at', ["$anio_actual-02-01 00:00:00", "$anio_actual-02-28 23:59:59"])
            ->where('estado','Pagado')
            ->first();
        $FebreroCompras = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as febrero"))
            ->whereBetween('created_at', ["$anio_actual-02-01 00:00:00", "$anio_actual-02-28 23:59:59"])
            ->first();
        $Febrero = $FebreroPagos->febrero + $FebreroCompras->febrero;

        $MarzoPagos = Pagos_Fijos::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as marzo"))
            ->whereBetween('created_at', ["$anio_actual-03-01 00:00:00", "$anio_actual-03-31 23:59:59"])
            ->where('estado','Pagado')
            ->first();
        $MarzoCompras = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as marzo"))
            ->whereBetween('created_at', ["$anio_actual-03-01 00:00:00", "$anio_actual-03-31 23:59:59"])
            ->first();
        $Marzo = $MarzoPagos->marzo + $MarzoCompras->marzo;

        $AbrilPagos = Pagos_Fijos::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as abril"))
            ->whereBetween('created_at', ["$anio_actual-04-01 00:00:00", "$anio_actual-04-30 23:59:59"])
            ->where('estado','Pagado')
            ->first();
        $AbrilCompras = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as abril"))
            ->whereBetween('created_at', ["$anio_actual-04-01 00:00:00", "$anio_actual-04-30 23:59:59"])
            ->first();
        $Abril = $AbrilPagos->abril + $AbrilCompras->abril;

        $MayoPagos = Pagos_Fijos::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as mayo"))
            ->whereBetween('created_at', ["$anio_actual-05-01 00:00:00", "$anio_actual-05-31 23:59:59"])
            ->where('estado','Pagado')
            ->first();
        $MayoCompras = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as mayo"))
            ->whereBetween('created_at', ["$anio_actual-05-01 00:00:00", "$anio_actual-05-31 23:59:59"])
            ->first();
        $Mayo = $MayoPagos->mayo + $MayoCompras->mayo;

        $JunioPagos = Pagos_Fijos::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as junio"))
            ->whereBetween('created_at', ["$anio_actual-06-01 00:00:00", "$anio_actual-06-30 23:59:59"])
            ->where('estado','Pagado')
            ->first();
        $JunioCompras = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as junio"))
            ->whereBetween('created_at', ["$anio_actual-06-01 00:00:00", "$anio_actual-06-30 23:59:59"])
            ->first();
        $Junio = $JunioPagos->junio + $JunioCompras->junio;

        $JulioPagos = Pagos_Fijos::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as julio"))
            ->whereBetween('created_at', ["$anio_actual-07-01 00:00:00", "$anio_actual-07-31 23:59:59"])
            ->where('estado','Pagado')
            ->first();
        $JulioCompras = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as julio"))
            ->whereBetween('created_at', ["$anio_actual-07-01 00:00:00", "$anio_actual-07-31 23:59:59"])
            ->first();
        $Julio = $JulioPagos->julio + $JulioCompras->julio;

        $AgostoPagos = Pagos_Fijos::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as agosto"))
            ->whereBetween('created_at', ["$anio_actual-08-01 00:00:00", "$anio_actual-08-31 23:59:59"])
            ->where('estado','Pagado')
            ->first();
        $AgostoCompras = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as agosto"))
            ->whereBetween('created_at', ["$anio_actual-08-01 00:00:00", "$anio_actual-08-31 23:59:59"])
            ->first();
        $Agosto = $AgostoPagos->agosto + $AgostoCompras->agosto;

        $SeptiembrePagos = Pagos_Fijos::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as septiembre"))
            ->whereBetween('created_at', ["$anio_actual-09-01 00:00:00", "$anio_actual-09-30 23:59:59"])
            ->where('estado','Pagado')
            ->first();
        $SeptiembreCompras = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as septiembre"))
            ->whereBetween('created_at', ["$anio_actual-09-01 00:00:00", "$anio_actual-09-30 23:59:59"])
            ->first();
        $Septiembre = $SeptiembrePagos->septiembre + $SeptiembreCompras->septiembre;

        $OctubrePagos = Pagos_Fijos::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as octubre"))
            ->whereBetween('created_at', ["$anio_actual-10-01 00:00:00", "$anio_actual-10-31 23:59:59"])
            ->where('estado','Pagado')
            ->first();
        $OctubreCompras = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as octubre"))
            ->whereBetween('created_at', ["$anio_actual-10-01 00:00:00", "$anio_actual-10-31 23:59:59"])
            ->first();
        $Octubre = $OctubrePagos->octubre + $OctubreCompras->octubre;

        $NoviembrePagos = Pagos_Fijos::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as noviembre"))
            ->whereBetween('created_at', ["$anio_actual-11-01 00:00:00", "$anio_actual-11-30 23:59:59"])
            ->where('estado','Pagado')
            ->first();
        $NoviembreCompras = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as noviembre"))
            ->whereBetween('created_at', ["$anio_actual-11-01 00:00:00", "$anio_actual-11-30 23:59:59"])
            ->first();
        $Noviembre = $NoviembrePagos->noviembre + $NoviembreCompras->noviembre;

        $DiciembrePagos = Pagos_Fijos::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as diciembre"))
            ->whereBetween('created_at', ["$anio_actual-12-01 00:00:00", "$anio_actual-12-31 23:59:59"])
            ->where('estado','Pagado')  
            ->first();
        $DiciembreCompras = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as diciembre"))
            ->whereBetween('created_at', ["$anio_actual-12-01 00:00:00", "$anio_actual-12-31 23:59:59"])
            ->first();
        $Diciembre = $DiciembrePagos->diciembre + $DiciembreCompras->diciembre;        

        // Suma total de costos para el mes actual
        $mesActual = now()->format('m'); 
        $totalRequisicionesMes = Orden_compras::whereMonth('created_at', $mesActual)->sum('costo_total');
        $totalPagosMes = Pagos_Fijos::whereMonth('created_at', $mesActual)->where('estado','Pagado')->sum('costo_total');
        $TotalMes = $totalRequisicionesMes + $totalPagosMes;

        // Suma total de costos para el año en curso
        $anioActual = now()->year;
        $totalRequisicionesAnio =Orden_compras::whereYear('created_at', $anioActual)->sum('costo_total');
        $totalPagosAnio= Pagos_Fijos::whereYear('created_at', $anioActual)->where('estado','Pagado')->sum('costo_total');
        $TotalAnio = $totalRequisicionesAnio + $totalPagosAnio;

        // Conteo de requisiciones completas
        $completas = Requisiciones::join('users','requisiciones.usuario_id','=','users.id')
        ->where('requisiciones.estado', 'Finalizado')
        ->count();

        // Conteo de requisiciones pendientes
        $pendiente = Requisiciones::join('users','requisiciones.usuario_id','=','users.id')
        ->where('users.departamento',session('departamento'))
        ->where('requisiciones.estado','!=', 'Finalizado')
        ->where('requisiciones.estado','!=','Rechazado')
        ->count();

        // Pasar los datos a la vista
        return view("GtArea.index",[
            'pendientes'=>$pendiente,
            'completas'=>$completas,
            'TotalMes'=>$TotalMes,
            'TotalAnio'=>$TotalAnio,
            // Datos de costos por mes
            'enero'      => $Enero,
            'febrero'    => $Febrero,
            'marzo'      => $Marzo,
            'abril'      => $Abril,
            'mayo'       => $Mayo,
            'junio'      => $Junio,
            'julio'      => $Julio,
            'agosto'     => $Agosto,
            'septiembre' => $Septiembre,
            'octubre'    => $Octubre,
            'noviembre'  => $Noviembre,
            'diciembre'  => $Diciembre,]);
    }

    /*
      TODO: Obtiene todas las refacciones activas del almacén y las muestra en una vista específica.
     
      Este método consulta la tabla 'Almacen' para recuperar todas las entradas de refacciones
      que tienen un estatus activo (estatus = 1). Se asume que el estatus '1' indica que las refacciones
      están disponibles o activas para uso o asignación. 
     
      Retorna la vista 'GtArea.refaccion', pasando los datos de las refacciones activas.
    */
    public function tableRefaccion(){
        // Obtiene todas las refacciones del almacén que están activas (estatus = 1)
        $refacciones = Almacen::get()->where("estatus",1);

        // Retorna la vista 'GtArea.refaccion', pasando la lista de refacciones activas
        return view('GtArea.refaccion',compact('refacciones'));
    }

    /*
      TODO: Recupera y muestra las unidades activas con ciertos criterios de filtrado.
     
      Este método realiza una consulta a la tabla 'Unidades' para obtener las unidades que cumplen con
      los siguientes criterios: tener un estatus '1' (que indica unidades activas), no ser la unidad con 'id_unidad' igual a 1,
      y tener un estado 'Activo'. Las unidades recuperadas son ordenadas en orden ascendente por su 'id_unidad'.
     
      Retorna la vista 'GtArea.unidad', pasando los datos de las unidades filtradas.
    */
    public function tableUnidad()
    {   
        // Recupera las unidades que cumplen con los criterios especificados y las ordena por 'id_unidad'
        $unidades = Unidades::where('estatus','1')
        ->where('id_unidad','!=','1')
        ->where('estado','Activo')->orderBy('id_unidad','asc')->get();

        // Retorna la vista 'GtArea.unidad', pasando la lista de unidades filtradas
        return view('GtArea.unidad',compact('unidades'));
    }

    //! ESTE REPORTE AUN NO SE IMPLEMENTA
    public function tableEntradas(){
        $entradas = Entradas::select('entradas.id_entrada','requisiciones.pdf as reqPDF','orden_compras.pdf as ordPDF','entradas.factura','entradas.created_at')
        ->join('orden_compras','entradas.orden_id','=','orden_compras.id_orden')
        ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
        ->get();
        return view('GtArea.entradas',compact('entradas'));
    }

    //! ESTE REPORTE AUN NO SE IMPLEMENTA
    public function tableSalidas(){
        $salidas = Salidas::select('salidas.id_salida','requisiciones.pdf as reqPDF','salidas.cantidad','users.nombres','almacen.clave','almacen.ubicacion','almacen.descripcion','salidas.created_at')
        ->join('almacen','salidas.refaccion_id','=','almacen.clave')
        ->join('requisiciones','salidas.requisicion_id','=','requisiciones.id_requisicion')
        ->join('users','requisiciones.usuario_id','=','users.id')
        ->get();
        return view('GtArea.salidas',compact('salidas'));
    }

    /*
      TODO: Recupera y muestra todos los proveedores activos.
     
      Este método consulta la tabla 'Proveedores' para obtener todos los registros de proveedores que tienen un estatus '1',
      indicando que están activos. La selección de proveedores activos es crucial para operaciones de negocio,
      ya que solo se deberían realizar transacciones con proveedores que están actualmente activos y disponibles.      
     
      Retorna la vista 'GtArea.proveedores', pasando la lista de proveedores activos.
    */    
    public function tableProveedores(){
        // Obtiene todos los proveedores activos (estatus = 1) de la base de datos
        $proveedores = Proveedores::where('estatus','1')->get();

        // Retorna la vista 'GtArea.proveedores', pasando la lista de proveedores activos
        return view('GtArea.proveedores',compact('proveedores'));
    }

    /*
      TODO: Recupera los detalles de un proveedor específico para su edición.
     
      Este método busca en la tabla 'Proveedores' un registro específico utilizando el ID del proveedor proporcionado como parámetro.
      La búsqueda tiene como objetivo encontrar los detalles del proveedor que luego serán mostrados en la vista de edición,
      permitiendo así que los usuarios actualicen la información del proveedor según sea necesario.
     
      @param  int  $id  El ID del proveedor que se desea editar.
      Retorna la vista 'GtArea.editarProveedor', pasando los detalles del proveedor específico para su edición.
    */
    public function editProveedor($id){
        // Busca el proveedor específico por su ID
        $proveedor = Proveedores::where('id_proveedor',$id)->first();

        // Retorna la vista de edición del proveedor, pasando los detalles del proveedor encontrado
        return view('GtArea.editarProveedor',compact('proveedor'));
    }

    /*
      TODO: Actualiza la información de un proveedor específico en la base de datos.
     
      Este método recibe datos de un formulario a través de una petición HTTP y utiliza estos datos para actualizar
      la información de un proveedor específico identificado por su ID. Los campos actualizables incluyen el nombre,
      teléfono y correo electrónico del proveedor. Además, se actualiza el campo 'updated_at' para reflejar
      el momento de la actualización.
     
      @param  \Illuminate\Http\Request  $req  La petición HTTP que contiene los datos del formulario.
      @param  int  $id  El ID del proveedor a actualizar.
      Redirige al usuario a la lista de proveedores con una sesión flash que indica que la actualización fue exitosa.
    */
    public function updateProveedor(Request $req,$id){
        // Actualiza el registro del proveedor específico con los datos proporcionados
        Proveedores::where('id_proveedor',$id)->update([
            "nombre"=>$req->input('nombre'),
            "telefono"=>$req->input('telefono'),
            "correo"=>$req->input('correo'),
            "updated_at"=>Carbon::now(),
        ]);

        // Redirige al usuario a la lista de proveedores con un mensaje de éxito
        return redirect('proveedores/GtArea')->with('update','update');
    }

    /*
      TODO: Desactiva un proveedor específico marcándolo como inactivo en la base de datos.
     
      En lugar de eliminar el registro del proveedor, este método actualiza el campo 'estatus' a 0,
      indicando que el proveedor está inactivo. Esto es útil para mantener la integridad de los datos y permitir
      la recuperación del registro en el futuro si es necesario. Además, se actualiza el campo 'updated_at'
      para reflejar el momento de la desactivación.
     
      @param  int  $id  El ID del proveedor a desactivar.
      Redirige al usuario a la página anterior con una sesión flash que indica que el proveedor ha sido desactivado exitosamente.
    */
    public function deleteProveedor($id){
        // Actualiza el registro del proveedor específico para marcarlo como inactivo
        Proveedores::where('id_proveedor',$id)->update([
            "estatus"=>0,
            "updated_at"=>Carbon::now()
        ]);

        // Redirige al usuario a la página anterior con un mensaje de confirmación
        return back()->with('delete','delete');
    }

    /*
      TODO: Recupera y muestra un listado de solicitudes, excluyendo las rechazadas, con información adicional.
     
      Este método consulta la base de datos para obtener un listado de todas las solicitudes que no han sido rechazadas.
      Para cada solicitud, se recopila información detallada incluyendo el ID de la requisición, fecha de creación,
      unidad asociada, estado de la solicitud, departamento y nombres del usuario solicitante, el archivo PDF asociado
      a la requisición, los detalles del último comentario realizado, y el rol del usuario que hizo el último comentario.      
      Las solicitudes se ordenan de manera descendente por su fecha de creación y se agrupan por el ID de la requisición
      para evitar duplicados en los resultados. Los datos recopilados se pasan a la vista 'GtArea.solicitudes' para su visualización.
     
      Retorna la vista 'GtArea.solicitudes', pasando el listado de solicitudes recopiladas.
    */
    public function tableSolicitud(){
        // Recupera las solicitudes de la base de datos
        $solicitudes = Requisiciones::where('requisiciones.estado','!=','Rechazado')
        ->select('requisiciones.id_requisicion','requisiciones.created_at','requisiciones.unidad_id','requisiciones.estado','us.departamento','us.nombres','requisiciones.created_at','requisiciones.pdf', 'comentarios.detalles','users.rol',DB::raw('MAX(comentarios.created_at) as fechaCom'))
        ->join('users as us','requisiciones.usuario_id','us.id')
        ->leftJoin('comentarios','requisiciones.id_requisicion','=','comentarios.requisicion_id')
        ->leftJoin('users','users.id','=','comentarios.usuario_id')        
        ->orderBy('requisiciones.created_at','desc')
        ->groupBY('requisiciones.id_requisicion')
        ->get();

        //Redirige al usuario a la página para visualizar la consulta
        return view('GtArea.solicitudes',compact('solicitudes'));
    }

    /*
      TODO: Recupera los artículos asociados a una solicitud específica para su aprobación.
     
      Este método busca en la base de datos todos los artículos vinculados a una requisición específica,
      identificada por su ID. La intención es obtener una lista de artículos que necesitan ser revisados
      y posiblemente aprobados. Esta funcionalidad permie a los usuarios con los permisos adecuados revisar 
      los detalles de los artículos solicitados antes de proceder con la aprobación o el rechazo de la solicitud.
     
      @param  int  $id  El ID de la requisición cuyos artículos se van a recuperar para aprobación.    

      Retorna la vista 'GtArea.aprobarSolicitud', pasando la lista de artículos asociados a la solicitud.
    */
    public function aprobarArt($id){
        // Busca todos los artículos vinculados a la ID de la requisición proporcionada
        $articulos = Articulos::where('requisicion_id',$id)->get();

        // Retorna la vista para la aprobación de la solicitud, pasando los artículos recuperados
        return view('GtArea.aprobarSolicitud',compact('articulos'));
    }

    /*
      TODO: Actualiza la información de un artículo específico basado en los datos proporcionados por el usuario.
     
      Este método recibe datos de un formulario a través de una petición HTTP y utiliza estos datos para actualizar
      los detalles de un artículo específico en la base de datos. Los campos actualizables incluyen la cantidad,
      unidad y descripción del artículo. Además, se actualiza el campo 'updated_at' para reflejar el momento
      de la actualización.
     
      @param  int  $id  El ID del artículo que se va a actualizar.

      Redirige al usuario a la página anterior tras la actualización exitosa del artículo.
    */
    public function editarArt(Request $req, $id){
        // Actualiza el registro del artículo específico con los datos proporcionados
        Articulos::where('id',$id)->update([
            "cantidad"=>$req->editCantidad,
            "unidad"=>$req->editUnidad,
            "descripcion"=>$req->editDescripcion,
            "updated_at"=>Carbon::now()
        ]);

        // Redirige al usuario a la página anterior tras la actualización
        return back();
    }

    /*
     TODO:Elimina un artículo específico de la base de datos.
     
      Este método se encarga de eliminar de forma permanente un registro de artículo específico,
      identificado por su ID, de la base de datos. Es útil en situaciones donde un artículo ha sido rechazado
      o ya no es necesario en una solicitud o requisición, permitiendo así mantener la base de datos limpia
      y actualizada. 

      @param  int  $id  El ID del artículo que se va a eliminar.

      Redirige al usuario a la página anterior tras la eliminación exitosa del artículo.
    */
    public function rechazaArt($id){
        // Elimina el artículo específico por su ID
        Articulos::where('id',$id)->delete();

        // Redirige al usuario a la página anterior tras la eliminación
        return back();
    }

    /*
      TODO: Aprueba una solicitud o requisición específica y actualiza su estado en la base de datos.

      1. Recopila información detallada de la requisición y del usuario solicitante para su uso en la generación
        del PDF de la solicitud aprobada.
      2. Elimina el archivo PDF anterior asociado a la requisición, si existe.
      3. Genera un nuevo PDF para la requisición aprobada utilizando una plantilla específica.
      4. Actualiza el estado de la requisición a 'Aprobado' y guarda la ruta del nuevo PDF en la base de datos.
      5. Crea un registro de comentario, si se proporcionan comentarios.
     
      @param  int  $rid El ID de la requisición que se va a aprobar.
     
      Redirige al usuario a la lista de solicitudes con una sesión flash indicando que la aprobación fue exitosa.
    */
    public function aprobar(Request $req,$rid){

        // Recopilación de información de la requisición y generación del nuevo PDF
        $notas = $req->Comentarios;
        $datos = Requisiciones::select('requisiciones.id_requisicion','requisiciones.unidad_id','requisiciones.created_at','requisiciones.pdf','requisiciones.notas','requisiciones.usuario_id','users.nombres','users.apellidoP','users.apellidoM','users.rol','users.departamento')
        ->join('users','requisiciones.usuario_id','=','users.id')
        ->where('requisiciones.id_requisicion',$rid)
        ->first();

        //Guarda la ruta del archivo PDF de la requisicion
        $fileToDelete = public_path($datos->pdf);

        //Si existe el archivo lo elimina 
        if (file_exists($fileToDelete)) {
            unlink($fileToDelete);
        }

        //Recupera los articulos por requisicion
        $articulos = Articulos::where('requisicion_id',$rid)->get();

        //Valida si la requisicion tiene una unidad asignada y recupera su información
        if(!empty($datos->unidad_id)){
            $unidad = Unidades::where('id_unidad',$datos->unidad_id)->first();
        }

        // Nombre y ruta del archivo en laravel
        $nombreArchivo = 'requisicion_' . $datos->id_requisicion . '.pdf';
        $rutaDescargas = 'requisiciones/' . $nombreArchivo;

        // Incluir el archivo Requisicion.php y pasar la ruta del archivo como una variable
        ob_start(); // Iniciar el búfer de salida
        include(public_path('/pdf/TCPDF-main/examples/RequisicionAprobada.php'));
        ob_end_clean();    

        // Actualización del estado de la requisición a 'Aprobado'
        Requisiciones::where('id_requisicion',$rid)->update([
            "estado"=>'Aprobado',
            "pdf"=>$rutaDescargas,
            "updated_at"=>Carbon::now(),
        ]);

        // Creación de comentario si se proporciona
        if (!empty($req->input('Comentarios'))){
            Comentarios::create([
                "requisicion_id"=>$rid,
                "usuario_id"=>session('loginId'),
                "detalles"=>$req->input('Comentarios'),
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);       
        }

        // Redirección al usuario con mensaje de éxito
        return redirect('solicitudes/GtArea')->with('aprobado','aprobado');
    }
    
    /*
      TODO: Recupera y muestra todas las cotizaciones activas asociadas a una requisición específica.
     
      Este método consulta la base de datos para obtener un listado de cotizaciones activas (estatus '1')
      que están asociadas a una requisición específica, identificada por su ID. Para cada cotización, se recopilan
      detalles como el ID de la cotización, el ID de la requisición asociada, el nombre del usuario que realizó
      la cotización, y las rutas a los archivos PDF de la requisición y de la cotización.
     
      @param  int  $id El ID de la requisición para la cual se recuperarán las cotizaciones.

      Retorna la vista 'GtArea.cotizaciones', pasando el listado de cotizaciones y el ID de la requisición.
    */
    public function cotizaciones($id){
        //Recupera las cotizaciones basandose en el estatus 1 y segun la requisicion
        $cotizaciones = Cotizaciones::select('cotizaciones.id_cotizacion','requisiciones.id_requisicion as requisicion_id','users.nombres as usuario','requisiciones.pdf as reqPDF','cotizaciones.pdf as cotPDF')
        ->join('requisiciones','cotizaciones.requisicion_id', '=', 'requisiciones.id_requisicion')
        ->join('users','cotizaciones.usuario_id', '=', 'users.id')
        ->where('requisicion_id', $id)
        ->where('cotizaciones.estatus','1')->get();

        // Redirige al usuario a la página para visualizar las cotizaciones
        return view('GtArea.cotizaciones',compact('cotizaciones','id'));
    }

    /*
      TODO: Registra el rechazo de una requisición específica, actualizando su estado y guardando un comentario y un log del evento.
      
      1. Crea un nuevo registro de comentario asociado a la requisición, utilizando el comentario proporcionado
        por el usuario a través del formulario. Esto permite mantener un registro de la razón detrás del rechazo.
      2. Actualiza el estado de la requisición a "Rechazado" y actualiza la fecha de última modificación. Esto marca la
        requisición específicamente como rechazada sin eliminarla de la base de datos, preservando así el registro histórico.
      3. Crea un registro en el log para documentar la acción de rechazo, incluyendo el ID del usuario que realizó la acción
        y el ID de la requisición afectada, lo que facilita el seguimiento de cambios y decisiones importantes sobre las solicitudes.
     
      @param  \Illuminate\Http\Request  $req La petición HTTP que contiene el comentario del formulario.
      @param  int  $id El ID de la requisición que se va a rechazar.

      Redirige al usuario a la página anterior con una sesión flash indicando que la requisición ha sido rechazada.
    */
    public function deleteReq(Request $req, $id){
        // Creación de comentario asociado a la requisición rechazada
        Comentarios::create([
            "requisicion_id"=>$id,
            "usuario_id"=>session('loginId'),
            "detalles"=>$req->comentario,
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]); 

        // Actualización del estado de la requisición a "Rechazado"
        Requisiciones::where('id_requisicion',$id)->update([
            "estado"=>"Rechazado",
            "updated_at"=>Carbon::now(),
        ]);    
        
        // Creación de registro en el log para documentar la acción de rechazo
        Logs::create([
            "user_id"=>session('loginId'),
            "requisicion_id"=>$id,
            "table_name"=>"Solicitudes",
            "action"=>"Se ha rechazado la solicitud: ".$id,
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        // Redirige al usuario a la página anterior tras el rechazo
        return back()->with('eliminada','eliminada');
    }

    /*
      TODO: Actualiza el estado de una requisición específica a "Aprobado" y registra la acción en el log.
     
      1. Actualiza el estado de la requisición indicada por el ID proporcionado a "Aprobado", marcando así la requisición
        como validada y lista para proceder a las siguientes etapas del proceso de gestión de solicitudes.
        La fecha de actualización también se registra para mantener un seguimiento preciso de cuándo se aprobó la requisición.
      2. Crea un nuevo registro en el log de acciones para documentar la aprobación de la requisición. Este log incluye
        el ID del usuario que realizó la acción, el ID de la requisición afectada, el nombre de la tabla afectada, y la acción
        específica realizada, proporcionando así un registro auditado de las operaciones importantes realizadas en el sistema.      
     
      @param  int  $id El ID de la requisición que se va a validar.
    
      Redirige al usuario a la página anterior con una sesión flash indicando que la requisición ha sido validada exitosamente.
    */
    public function validarRequisicion($id){
        // Actualización del estado de la requisición a "Aprobado"
        Requisiciones::where('id_requisicion',$id)->update([
            "estado"=>"Aprobado",
            "updated_at"=>Carbon::now(),
        ]);

        // Creación de registro en el log para documentar la aprobación de la requisición
        Logs::create([
            "user_id"=>session('loginId'),
            "requisicion_id"=>$id,
            "table_name"=>"Requisiciones",
            "action"=>"Se ha aprobado su solicitud".$id,
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        // Redirige al usuario a la página anterior tras la aprobación
        return back()->with('validado','validado');
    }

    /*
      TODO: Selecciona y pre-valida una cotización específica para una solicitud, actualizando el estado de la solicitud y las cotizaciones relacionadas.
     
      Este método primero verifica el estado actual de la solicitud: si ya está "Pre Validado", actualiza su estado a "Validado".
      Si no, actualiza todas las cotizaciones relacionadas con la solicitud, excepto la seleccionada, marcándolas como inactivas (estatus "0"),
      y cambia el estado de la solicitud a "Pre Validado". Este proceso es crucial para gestionar adecuadamente las etapas de validación
      de las cotizaciones y asegurar que solo una cotización sea seleccionada y avanzada en el proceso de aprobación de la solicitud.
     
      @param int $id  El ID de la cotización que se selecciona y pre-valida.
      @param int $sid El ID de la solicitud asociada a la cotización.

      Redirige al usuario a la lista de solicitudes con una sesión flash indicando que la cotización ha sido pre-validada.
    */
    public function selectCotiza($id,$sid){
        // Verificar el estado actual de la solicitud y actualizarlo según corresponda
        $req = Requisiciones::where('id_requisicion',$sid)->first();
        if ($req->estado === "Pre Validado"){
            // Actualizar el estado de la solicitud a "Validado" si ya estaba pre-validada
            Requisiciones::where('id_requisicion',$sid)->update([
                "estado" => "Validado",
                "updated_at" => Carbon::now()
            ]);

            // Registrar la acción en el log
            Logs::create([
                "user_id"=>session('loginId'),
                "table_name"=>"Solicitudes",
                "requisicion_id"=>$sid,
                "action"=>"Se ha pre validado una cotizacion de la solicitud: ".$sid,
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);

        } else{
            // Marcar todas las cotizaciones relacionadas, excepto la seleccionada, como inactivas
            Cotizaciones::where('id_cotizacion', '!=', $id)
                ->where('requisicion_id', $sid)
                ->update([
                "estatus" => "0",
                "updated_at" => Carbon::now()
            ]);

            // Actualizar el estado de la solicitud a "Pre Validado"
            Requisiciones::where('id_requisicion',$sid)->update([
                "estado" => "Pre Validado",
                "updated_at" => Carbon::now()
            ]);

            // Registrar la acción en el log
            Logs::create([
                "user_id"=>session('loginId'),
                "table_name"=>"Solicitudes",
                "requisicion_id"=>$sid,
                "action"=>"Se ha pre validado una cotizacion de la solicitud: ".$sid,
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);
        }
        // Redirige al usuario a la lista de solicitudes con una sesión flash indicando que la cotización ha sido pre-validada o validada.
        return redirect('solicitudes/GtArea')->with('validacion','validacion');
    }

    /*
      TODO: Recupera la cotización validada y todas las cotizaciones no validadas asociadas a una requisición específica.
     
      1. Obtiene la cotización validada (estatus '1') para la requisición especificada, junto con información adicional
        como el ID de la cotización, el ID de la requisición, el nombre del usuario que realizó la cotización,
        y las rutas a los archivos PDF tanto de la requisición como de la cotización.
      2. Recupera todas las demás cotizaciones (estatus '0') para la misma requisición, recopilando la misma información
        que en la consulta anterior.
      Estos datos son esenciales para facilitar el proceso de revisión y aprobación de cotizaciones por parte de los usuarios,
      permitiéndoles comparar la cotización validada con otras opciones disponibles. 
     
      @param  int  $id El ID de la requisición para la cual se recuperarán las cotizaciones.

      Retorna la vista 'GtArea.aprobCotizaciones', pasando las cotizaciones recuperadas, el ID de la requisición, y la cotización validada.
    */
    public function aprobCotiza($id){
        // Recuperar la cotización validada (estatus '1') para la requisición especificada
        $validada = Cotizaciones::select('cotizaciones.id_cotizacion','requisiciones.id_requisicion as requisicion_id','users.nombres as usuario','requisiciones.pdf as reqPDF','cotizaciones.pdf as cotPDF')
        ->join('requisiciones','cotizaciones.requisicion_id', '=', 'requisiciones.id_requisicion')
        ->join('users','cotizaciones.usuario_id', '=', 'users.id')
        ->where('requisicion_id', $id)
        ->where('cotizaciones.estatus','1')->first();

        // Recuperar todas las cotizaciones no validadas (estatus '0') para la misma requisición
        $cotizaciones = Cotizaciones::select('cotizaciones.id_cotizacion','requisiciones.id_requisicion as requisicion_id','users.nombres as usuario','requisiciones.pdf as reqPDF','cotizaciones.pdf as cotPDF')
        ->join('requisiciones','cotizaciones.requisicion_id', '=', 'requisiciones.id_requisicion')
        ->join('users','cotizaciones.usuario_id', '=', 'users.id')
        ->where('requisicion_id', $id)
        ->where('cotizaciones.estatus','0')->get();

        //Redirecciona a la vista para visualizar las cotizaciones recopiladas
        return view('GtArea.aprobCotizaciones',compact('cotizaciones','id','validada'));
    }

    /*
      TODO: Procesa el rechazo final de una cotización para una solicitud específica y actualiza el estado correspondiente.
     
      1. Crea un nuevo registro de comentario con los detalles proporcionados por el usuario, asociado a la solicitud específica,
        para documentar la razón del rechazo.
      2. Actualiza el estatus de todas las cotizaciones asociadas a la solicitud indicada a "1", indicando que se pueden consultar
        nuevamente.
      3. Cambia el estado de la solicitud a "Cotizado", indicando que se deberá pre validar cotizaciones del conjunto cargado.
     
      @param int $id El ID de la cotización específica que se rechaza (no usado directamente en la actualización).
      @param int $sid El ID de la solicitud asociada a la cotización rechazada.

      Redirige al usuario a la lista de solicitudes con una sesión flash indicando que la cotización ha sido rechazada.
    */
    public function rechazarFin(Request $req, $id,$sid){
        // Registro del comentario proporcionado por el usuario
        Comentarios::create([
            "requisicion_id"=>$sid,
            "usuario_id"=>session('loginId'),
            "detalles"=>$req->input('comentario'),
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        // Actualización del estatus de todas las cotizaciones asociadas a la solicitud
        Cotizaciones::where('requisicion_id', $sid)
            ->update([
            "estatus" => "1",
            "updated_at" => Carbon::now()
        ]);

        // Cambio del estado de la solicitud a "Cotizado"
        Requisiciones::where('id_requisicion',$sid)->update([
            "estado"=>"Cotizado",
            "updated_at"=>Carbon::now()
        ]);

        // Redirige al usuario a la lista de solicitudes con un mensaje de confirmación del rechazo.
        return redirect('solicitudes/GtArea')->with('rechazaC','rechazaC');
    }

    /*
      TODO: Elimina una cotización específica de la base de datos.
     
      Este método permite a los usuarios con los permisos adecuados eliminar una cotización específica,
      identificada por su ID, de la base de datos. La eliminación de una cotización puede ser necesaria
      en varias circunstancias, como cuando una cotización ha sido ingresada por error, ya no es relevante,
      o ha sido reemplazada por otra más actualizada.
     
      @param int $id El ID de la cotización que se va a eliminar.

      Redirige al usuario a la página anterior con una sesión flash que indica que la cotización ha sido eliminada exitosamente.
    */
    public function deleteCotiza($id){
        // Elimina la cotización específica por su ID
        Cotizaciones::where('id_cotizacion', $id)->delete();

        // Redirige al usuario a la página anterior con un mensaje de confirmación
        return back()->with('eliminado','eliminado');    
    }

    public function tablePagos(){
        $pagos = Pagos_Fijos::select('pagos_fijos.*','servicios.id_servicio','servicios.nombre_servicio','users.nombres as usuario','proveedores.nombre')
        ->join('servicios','pagos_fijos.servicio_id','servicios.id_servicio')
        ->join('proveedores','servicios.proveedor_id','proveedores.id_proveedor')
        ->join('users','pagos_fijos.usuario_id','=','users.id')
        ->orderBy('id_pago','desc')
        ->where('pagos_fijos.estado','!=','Rechazado')
        ->get();

        $servicios = Servicios::select('servicios.id_servicio','servicios.nombre_servicio','proveedores.id_proveedor','proveedores.nombre')
        ->join('proveedores','servicios.proveedor_id','=','proveedores.id_proveedor')
        ->orderBy('servicios.nombre_servicio','asc')
        ->where('servicios.estatus','1')
        ->get();

        $proveedores = Proveedores::where('estatus','1')
        ->orderBy('nombre','asc')
        ->get();

        return view('GtArea.pagos',compact('pagos','servicios','proveedores'));
    }

    public function registrarPago(Request $req, $id){
        // Verifica que se haya subido un archivo y que sea válido
        if ($req->hasFile('comprobante_pago') && $req->file('comprobante_pago')->isValid()){
                
            // Se genera el nombre y ruta para guardar PDF
            $nombreArchivo = 'comprobantePago_' . $id . '.pdf';
            $rutaDescargas = 'comprobantesPagos/' . $nombreArchivo;

            $archivo = $req->file('comprobante_pago');
            $archivo->storeAs('comprobantesPagos', $nombreArchivo, 'public');

            Pagos_Fijos::where('id_pago',$id)->update([
                "comprobante_pago"=>$rutaDescargas,
                "estado"=>"Pagado",
                "updated_at"=>Carbon::now()
            ]);

            return back()->with('pagado','pagado');

        } else{
            // Manejo del caso en que no se sube un archivo válido
            Pagos_Fijos::where('id_pago',$id)->update([
                "estado"=>"Pagado",
                "updated_at"=>Carbon::now()
            ]);
        }
    }

    public function deletePago($id){
        Pagos_Fijos::where('id_pago',$id)->update([
            "estado"=>'Rechazado',
            "updated_at"=>Carbon::now(),
        ]);

        return back()->with('eliminado','eliminado');
    }

    /*
      TODO: Recupera y muestra un listado de unidades activas elegibles para mantenimiento.
     
      Este método consulta la base de datos para obtener un listado de todas las unidades que están marcadas como activas
      (estatus '1'), excluyendo una unidad específica por su ID (en este caso, la unidad con ID '1') para razones de filtrado
      específicas de la aplicación. Las unidades activas se ordenan en orden ascendente por su ID para facilitar su visualización
      y gestión, especialmente en lo que respecta a las tareas de mantenimiento.
     
      Retorna la vista 'GtArea.mantenimiento', pasando el listado de unidades activas para su visualización y gestión.
    */
    public function mantenimiento (){
        // Recupera las unidades que cumplen con los criterios especificados y las ordena por 'id_unidad'
        $unidades = Unidades::where('estatus','1')
        ->where('id_unidad','!=','1')
        ->where('estado','Activo')->orderBy('id_unidad','asc')->get();

        // Carga y muestra la vista con el listado de unidades activas
        return view('GtArea.mantenimiento',compact('unidades'));    
    }

    //! ESTE REPORTE AUN NO SE IMPLEMENTA
    public function reporteEnc(Request $req){

        $idEncargado = $req->encargado;
        
        $encargado = User::where('id',$idEncargado)->first();
        $solicitudes = Requisiciones::where('usuario_id',$idEncargado)->count();
        $completas = Requisiciones::where('estado','Entregado')->where('usuario_id',$idEncargado)->count();
        $Requisiciones = Requisiciones::where('usuario_id',$idEncargado)->get();
        $salidas = Salidas::select('salidas.id_salida','salidas.created_at','salidas.cantidad','requisiciones.unidad_id','almacen.nombre')
        ->join('almacen','salidas.refaccion_id','=','almacen.clave')
        ->join('requisiciones','salidas.requisicion_id','=','id_requisicion')    
        ->where('requisiciones.usuario_id',$idEncargado)
        ->get();

        $datosEmpleado[] = [
            'idEmpleado' => session('loginId'),
            'nombre' => session('loginNombre'),
            'rol' => session('rol'),
        ];

        // Serializar los datos del empleado y almacenarlos en un archivo
        $datosSerializados = serialize($datosEmpleado);
        $rutaArchivo = storage_path('app/datos_empleados.txt');
        file_put_contents($rutaArchivo, $datosSerializados);

        // Incluir el archivo Requisicion.php y pasar la ruta del archivo como una variable
        ob_start();
        include(public_path('/pdf/TCPDF-main/examples/Reporte_encargado.php'));
        $pdfContent = ob_get_clean();
        header('Content-Type: application/pdf');
        echo $pdfContent;
    }

    //! ESTE REPORTE AUN NO SE IMPLEMENTA
    public function reporteUnid(Request $req){

        $idUnidad = $req->unidad;

        $unidad = Unidades::where('id_unidad',$idUnidad)->first();

        $datosEmpleado[] = [
            'idEmpleado' => session('loginId'),
            'nombre' => session('loginNombre'),
            'rol' => session('rol'),
        ];

        // Serializar los datos del empleado y almacenarlos en un archivo
        $datosSerializados = serialize($datosEmpleado);
        $rutaArchivo = storage_path('app/datos_empleados.txt');
        file_put_contents($rutaArchivo, $datosSerializados);

        // Incluir el archivo Requisicion.php y pasar la ruta del archivo como una variable
        ob_start();
        include(public_path('/pdf/TCPDF-main/examples/Reporte_por_unidad.php'));
        $pdfContent = ob_get_clean();
        header('Content-Type: application/pdf');
        echo $pdfContent;
    }

    //! ESTE REPORTE AUN NO SE IMPLEMENTA
    public function reporteGen(){
        $datosEmpleado[] = [
            'idEmpleado' => session('loginId'),
            'nombre' => session('loginNombre'),
            'rol' => session('rol'),
        ];

        $compras = Orden_compras::select('orden_compras.id_orden','users.nombres','orden_compras.created_at','requisiciones.unidad_id','orden_compras.costo_total')
        ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
        ->join('users','orden_compras.admin_id','users.id')
        ->get();
        $unidades = Unidades::where('estatus','1')->get();
        $usuarios = User::where('estatus','1')->get();
        $refacciones = Almacen::where('estatus','1')->get();

        $datosEmpleado[] = [
            'idEmpleado' => session('loginId'),
            'nombre' => session('loginNombre'),
            'rol' => session('rol'),
        ];

        // Serializar los datos del empleado y almacenarlos en un archivo
        $datosSerializados = serialize($datosEmpleado);
        $rutaArchivo = storage_path('app/datos_empleados.txt');
        file_put_contents($rutaArchivo, $datosSerializados);

        // Incluir el archivo Requisicion.php y pasar la ruta del archivo como una variable
        ob_start();
        include(public_path('/pdf/TCPDF-main/examples/Reporte_General2.php'));
        $pdfContent = ob_get_clean();
        header('Content-Type: application/pdf');
        echo $pdfContent;
    }    
}