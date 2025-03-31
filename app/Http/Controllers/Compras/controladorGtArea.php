<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\Controller; // Asegúrate de incluir esta línea correctamente
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
use App\Models\CamionServicioPreventivo;
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
            ->where('estado','Pagado')
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
            ->where('estado','Pagado')
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
            ->where('estado','Pagado')
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
            ->where('estado','Pagado')
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
            ->where('estado','Pagado')
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
            ->where('estado','Pagado')
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
            ->where('estado','Pagado')
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
            ->where('estado','Pagado')
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
            ->where('estado','Pagado')
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
            ->where('estado','Pagado')
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
            ->where('estado','Pagado')
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
            ->where('estado','Pagado')
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
     TODO: Método del controlador para recuperar las requisiciones y su información relacionada para el usuario autenticado.

      Este método realiza una consulta a la base de datos para obtener las requisiciones del usuario autenticado,
      uniéndolas (LEFT JOIN) con las tablas 'comentarios' y 'users' para obtener detalles adicionales
      sobre los comentarios hechos en cada requisición y la información del rol del usuario que hizo el comentario.
      Las requisiciones son ordenadas de manera descendente por su fecha de creación.

      Retorna la vista 'Solicitante.requisiciones' con las solicitudes (requisiciones y su información relacionada) obtenidas.
    */
    public function tableRequisicion(){
        // Realiza la consulta a la base de datos para obtener las requisiciones del usuario y la información relacionada de las tablas 'comentarios' y 'users'
        $solicitudes = Requisiciones::where('requisiciones.usuario_id',session('loginId'))
        ->select('requisiciones.id_requisicion','requisiciones.unidad_id','requisiciones.estado','requisiciones.created_at','requisiciones.pdf', 'comentarios.detalles','users.rol',DB::raw('MAX(comentarios.created_at) as fechaCom'))
        ->leftJoin('comentarios','requisiciones.id_requisicion','=','comentarios.requisicion_id')
        ->leftJoin('users','users.id','=','comentarios.usuario_id')
        ->select('requisiciones.id_requisicion','requisiciones.unidad_id','requisiciones.estado','requisiciones.created_at','requisiciones.pdf', 'comentarios.detalles','users.rol','comentarios.created_at as fechaCom')
        ->orderBy('requisiciones.created_at','desc') //* Ordena las requisiciones por fecha de creación de manera descendente
        ->groupBy('requisiciones.id_requisicion')
        ->get();

        // Formatear fechas
        $solicitudes->transform(function ($solicitud) {
            $solicitud->fecha_creacion = Carbon::parse($solicitud->created_at)->format('d/m/Y');
            return $solicitud;
        });

        // Retorna la vista 'Solicitante.requisiciones', pasando las solicitudes obtenidas
        return view('GtArea.requisiciones',compact('solicitudes'));
    }

    /*
     TODO: Método del controlador para mostrar la vista de creación de una nueva solicitud.

      Este método recupera cualquier dato previamente almacenado en la sesión bajo la clave 'datos',
      permitiendo reutilizar información en caso de que el usuario regrese a esta vista sin completar una solicitud.
      Adicionalmente, consulta y recupera todas las unidades activas con estatus '1' de la base de datos,
      lo cual es necesario para que el usuario pueda seleccionar una unidad al crear una solicitud.

      Retorna la vista 'Solicitante.crearSolicitud', pasando las unidades activas y los datos de sesión (si los hay) a la vista.
    */
    public function createSolicitud(){
        // Recupera datos de sesión previamente guardados, si existen
        $datos = session()->get('datos', []);

        // Consulta y recupera todas las unidades con estado 'Activo' y estatus '1'
        $unidades = Unidades::where('estado','Activo')
        ->where('estatus','1')
        ->get();

        // Retorna la vista para crear una nueva solicitud, pasando las unidades y los datos de sesión a la vista
        return view('GtArea.crearSolicitud',compact('unidades','datos'));
    }

    /*
     TODO: Agrega los datos de una solicitud a un array en la sesión y redirige al usuario a la página anterior.

      Este método recibe datos de un formulario a través de la petición HTTP, especificamente la cantidad, unidad,
      y descripción de un item solicitado. Opcionalmente, puede recibir notas adicionales sobre la solicitud.
      Los datos recibidos son agregados a un array 'datos' almacenado en la sesión, permitiendo la persistencia
      temporal de la información durante la sesión del usuario.

      @param $req  La petición HTTP entrante con los datos del formulario.

      Redirige al usuario a la página anterior, manteniendo la información de la solicitud en la sesión.
    */
    public function ArraySolicitud(Request $req){
        // Recupera el array de datos de la sesión, o inicializa uno nuevo si no existe
        $datos = session()->get('datos', []);

        // Extrae los valores de los campos del formulario de la petición HTTP
        $cantidad = $req->input('Cantidad');
        if ($req->input('Unidad') === "Otro"){
            $unidad = $req->input('otro');
        } else {
            $unidad = $req->input('Unidad');
        }
        $descripcion = $req->input('Descripcion');

        // Agrega los nuevos datos del formulario al array de 'datos' en la sesión
        $datos[] = [
            'cantidad' => $cantidad,
            'unidad'=>$unidad,
            'descripcion' => $descripcion,
        ];

        // Almacena el array actualizado de 'datos' en la sesión
        session()->put('datos', $datos);

        // Redirige al usuario a la página anterior
        return back();
    }

    /*
     TODO: Actualiza un elemento específico dentro de un array de 'datos' almacenado en la sesión.

      Este método permite editar los detalles de una solicitud específica, identificada por un índice,
      con los nuevos valores proporcionados a través del formulario. Después de actualizar estos valores en el array de sesión,
      el método redirige al usuario a la página anterior.

      @param  $req   La petición HTTP entrante con los datos del formulario editado.
      @param  $index El índice del elemento en el array de 'datos' que se va a actualizar.

      Redirige al usuario a la página anterior después de actualizar el elemento en la sesión.
    */
    public function editArray(Request $req, $index){
        // Recupera el array de 'datos' de la sesión, o inicializa uno nuevo si no existe
        $datos = session()->get('datos', []);

        // Extrae los valores editados de los campos del formulario de la petición HTTP
        $cantidadEditada = $req->input('editCantidad');
        $unidadEditada = $req->input('editUnidad');
        $descripcionEditada = $req->input('editDescripcion');

        // Verifica si el índice especificado existe en el array de 'datos'
        if (isset($datos[$index])) {
            // Actualiza los valores del elemento en el índice especificado con los nuevos datos
            $datos[$index]['cantidad'] = $cantidadEditada;
            $datos[$index]['unidad'] = $unidadEditada;
            $datos[$index]['descripcion'] = $descripcionEditada;
        }

        // Almacena el array actualizado de 'datos' en la sesión
        session()->put('datos', $datos);

        // Redirige al usuario a la página anterior
        return back();
    }

    /*
      TODO: Elimina un elemento específico del array 'datos' almacenado en la sesión.

      Este método se encarga de eliminar un elemento del array de 'datos' basado en el índice proporcionado.
      El índice representa la posición del elemento en el array que se desea eliminar. Si el elemento existe,
      es eliminado del array. Después de realizar la eliminación, los datos actualizados se almacenan de nuevo
      en la sesión para reflejar los cambios. Finalmente, el usuario es redirigido a la página anterior,
      permitiendo una experiencia de usuario fluida y continua.

      @param  int  $index El índice del elemento en el array 'datos' que se desea eliminar.

      Redirige al usuario a la página anterior después de eliminar el elemento de la sesión.
    */
    public function deleteArray($index){
        // Recupera el array de 'datos' de la sesión, o inicializa uno nuevo si no existe
        $datos = session()->get('datos', []);

        // Verifica si el índice especificado existe en el array de 'datos'
        if (isset($datos[$index])) {
            // Elimina el elemento del array en el índice especificado
            unset($datos[$index]);
        }

        // Almacena el array actualizado de 'datos' en la sesión
        session()->put('datos', $datos);

        // Redirige al usuario a la página anterior
        return back();
    }

    /*
      TODO: Procesa la creación de una nueva requisición basada en los datos proporcionados por el usuario y almacenados en la sesión.

      Este método primero verifica si hay datos de requisición almacenados en la sesión. Si no hay datos, redirige
      al usuario a la página anterior con un mensaje indicando que no hay datos. Si hay datos, procede a procesar
      estos datos para crear una nueva requisición. Los pasos incluyen:
      - Recolectar notas adicionales y datos del empleado de la sesión.
      - Determinar el ID de la próxima requisición basándose en la última entrada en la base de datos.
      - En el caso de que el departamento del usuario sea 'Mantenimiento', se busca una unidad específica.
      - Serializar los datos del empleado y almacenarlos en un archivo.
      - Crear un PDF para la requisición utilizando los datos proporcionados.
      - Insertar la nueva requisición en la base de datos.
      - Agregar los artículos detallados en la sesión a la base de datos como parte de la nueva requisición.
      - Limpiar los datos de la sesión relacionados con la requisición para evitar duplicaciones.

      @param  \Illuminate\Http\Request  $req La petición HTTP entrante con los datos necesarios para la requisición.

      Finalmente, redirige al usuario a una página de confirmación de la solicitud.
    */
    public function requisicion (Request $req){
        // Inicialización y verificación de los datos de la sesión
        $datosRequisicion = session()->get('datos', []);

        if (empty($datosRequisicion)){
            return back()->with('vacio','vacio');
        }else {
            // Procesamiento de los datos de la solicitud y del empleado
            if (!empty($req->urgencia)) {
                $urgencia = 'Requisición urgente';
                try {
                    // Le da formato dd/mm/aaaa a la fecha creacion de la requisición
                    $fechaProgramada = date('d/m/Y', strtotime($req->dias));
                } catch (\Exception $e) {
                    // Manejar el error si el formato es incorrecto
                    $fechaProgramada = null;

                    return $e;
                    // Puedes lanzar una excepción o registrar el error si es necesario
                    // throw new \Exception('Formato de fecha no válido');
                }
            } else {
                $urgencia = null;
                $fechaProgramada = null;
            }

            $Nota = $req->input('Notas');

            $mantenimiento = $req->input('mantenimiento');

            // Definición y serialización de los datos del empleado
            $datosEmpleado[] = [
                'idEmpleado' => session('loginId'),
                'nombres' => session('loginNombres'),
                'apellidoP' => session('loginApepat'),
                'apellidoM' => session('loginApemat'),
                'rol' => session('rol'),
                'dpto' =>session('departamento')
            ];

            // Determinación del ID de la nueva requisición y preparación del PDF
            $ultimarequisicion = Requisiciones::select('id_requisicion')->latest('id_requisicion')->first();
            if (empty($ultimarequisicion)){
                $idcorresponde = 1;
            } else {
                $idcorresponde = $ultimarequisicion->id_requisicion + 1;
            }

            // Validar si la requisicion esta relacionada a una unidad, en caso de pertenecer a mantenimiento
            if(session('departamento') === "Mantenimiento"){
                $unidad = Unidades::where('id_unidad',$req->input('unidad'))->first();
            }else{
                $unidad = null;
            }

            // Serializar los datos del empleado y almacenarlos en un archivo para pasarlos al PDF
            $datosSerializados = serialize($datosEmpleado);
            $rutaArchivo = storage_path('app/datos_empleado.txt');
            file_put_contents($rutaArchivo, $datosSerializados);

            // Se genera el nombre y ruta para guardar PDF
            $nombreArchivo = 'requisicion_' . $idcorresponde . '.pdf';
            $rutaDescargas = 'requisiciones/' . $nombreArchivo;

            // Incluir el archivo Requisicion.php y pasar la ruta del archivo como una variable
            ob_start(); //* Iniciar el búfer de salida para pasar las variables al PDF
            include(public_path('/pdf/TCPDF-main/examples/Requisicion.php'));
            ob_end_clean();

            // Creación de la nueva requisición en la base de datos validando el departamento para así agregar o ignorar unidad
            if(session('departamento') === "Mantenimiento"){
                DB::table('requisiciones')->insert([
                    "id_requisicion"=>$idcorresponde,
                    "usuario_id"=>session('loginId'),
                    "unidad_id" => $req->input('unidad'),
                    "pdf" => $rutaDescargas,
                    "urgencia"=>$req->urgencia,
                    "fecha_programada"=>$req->dias,
                    "notas" => $Nota,
                    "mantenimiento"=>$req->input('mantenimiento'),
                    "estado"=> "Solicitado",
                    "created_at"=>Carbon::now(),
                    "updated_at"=>Carbon::now(),
                ]);
            } else{
                Requisiciones::create([
                    "id_requisicion"=>$idcorresponde,
                    "usuario_id"=>session('loginId'),
                    "pdf" => $rutaDescargas,
                    "urgencia"=>$req->urgencia,
                    "fecha_programada"=>$req->dias,
                    "estado"=> "Solicitado",
                    "notas"=> $Nota,
                    "created_at"=>Carbon::now(),
                    "updated_at"=>Carbon::now(),
                ]);
            }

            // Obtener el id de la rrequisicion creada
            $ultimaReq = Requisiciones::where('usuario_id',session('loginId'))
            ->orderBy('created_at','desc')
            ->limit(1)
            ->first();

            // Agregar los artículos de la sesión a la tabla de artículos de la base de datos
            foreach ($datosRequisicion as $dato) {
                Articulos::create([
                    'requisicion_id' => $ultimaReq->id_requisicion,
                    'cantidad' => $dato['cantidad'],
                    'unidad' => $dato['unidad'],
                    'descripcion' => $dato['descripcion'],
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ]);
            }

            // Limpieza de la sesión
            session()->forget('datos');

            // Redirige al usuario a la ruta solicitudes con un mensaje de éxito
            return redirect('requisicionesDir')->with('solicitado','solicitado');
        }
    }

    /*
      TODO: Carga la vista de edición para una requisición específica, incluyendo datos relevantes para su modificación.

      Este método realiza varias consultas a la base de datos para obtener todos los artículos asociados a la requisición
      identificada por el ID proporcionado. Luego, recupera los detalles de la unidad asociada a la requisición, incluyendo
      su ID, marca, modelo, y notas, si la requisición está vinculada a una unidad específica. Adicionalmente, recopila un
      listado de todas las unidades que están actualmente activas (estado 'Activo' y estatus '1') para ofrecer opciones
      de selección en el formulario de edición.

      @param  int  $id  El ID de la requisición que se va a editar.

      Retorna la vista 'Solicitante.editRequisicion', pasando los artículos, las unidades disponibles, los detalles de la
      unidad asociada, y el ID de la requisición para su visualización y edición.
    */
    public function editReq($id){
        // Recuperación de artículos asociados a la requisición
        $articulos = Articulos::where('requisicion_id',$id)->get();

        // Recuperación de detalles de la unidad asociada a la requisición
        $unidad = Requisiciones::select('id_unidad','marca','n_de_serie','modelo','notas','requisiciones.mantenimiento as mant','urgencia','fecha_programada')
        ->leftJoin('unidades','requisiciones.unidad_id','=','unidades.id_unidad')
        ->where('requisiciones.id_requisicion',$id)
        ->first();

        // Recuperación de todas las unidades activas
        $unidades = Unidades::where('estado','Activo')->where('estatus','1')->get();

        // Carga de la vista de edición con los datos recopilados
        return view('GtArea.editRequisicion',compact('articulos','unidades','unidad','id'));
    }

    /*
      TODO: Añade un nuevo artículo a una requisición específica.

      Este método maneja la inserción de un nuevo artículo en la base de datos, asociado a una requisición específica.
      Los datos del artículo, incluyendo la cantidad, la unidad (con la opción de especificar una unidad diferente en
      un campo 'otro'), y la descripción, son recogidos de un formulario enviado por el usuario. Dependiendo de si el usuario
      ha seleccionado 'Otro' como unidad y ha especificado un valor diferente, este valor es utilizado; de lo contrario,
      se utiliza el valor de la unidad seleccionada en el formulario.

      @param int $id El ID de la requisición a la cual se añadirá el nuevo artículo.

      Redirige al usuario a la página anterior tras la inserción exitosa del artículo.
    */
    public function createArt(Request $req, $id){
        // Determina la unidad basada en la entrada del usuario
        if ($req->input('Unidad') === "Otro"){
            $unidad = $req->input('otro');
        } else {
            $unidad = $req->input('Unidad');
        }

        // Crea el nuevo artículo con los datos proporcionados
        Articulos::create([
            "requisicion_id"=>$id,
            "cantidad"=>$req->Cantidad,
            "unidad"=>$unidad,
            "descripcion"=>$req->Descripcion,
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now(),
        ]);

        // Redirige al usuario a la página anterior
        return back();
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
    public function updateArt (Request $req, $id){
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
    public function deleteArt($id,$rid){
        // Elimina el artículo específico por su ID
        Articulos::where('id',$id)->delete();

        //Valida el numero de articulos que se han registrado al, hacer una requisición
        $N_articulos = Articulos::where('requisicion_id',$rid)->count();

        //Valida si la cantidad de articulos registrados al editar una requisición sea 0
        if($N_articulos == 0){
            //Si no hay ningun articulo automaticamente se cambia el estatus a incompleta.
            Requisiciones::where('id_requisicion',$rid)->update([
                "estado"=>"Incompleta",
                "updated_at"=>Carbon::now(),
            ]);
        }
        // Redirige al usuario a la página anterior tras la eliminación
        return back();
    }

    /*
      Actualiza la información de una solicitud específica y regenera su documento PDF asociado.

      Este método es responsable de verificar si la solicitud especificada contiene artículos. Si no hay artículos,
      retorna al usuario a la página anterior con un mensaje de error. Si hay artículos, procede a regenerar el PDF
      de la solicitud con la información actualizada, elimina el PDF anterior si existe, y actualiza la base de datos
      con los nuevos detalles de la solicitud, incluyendo notas y el estado 'Solicitado'. Finalmente, redirige al usuario
      a una página con un mensaje de éxito.

      @param int $id El ID de la requisición que se está actualizando.

     * Devuelve una redirección al usuario a la página de la solicitud con una notificación que indica si la operación fue exitosa o no.
    */
    public function updateSolicitud (Request $req, $id){
        // Contar los artículos asociados a la requisición
        $N_articulos = Articulos::where('requisicion_id',$id)->count();

        if($N_articulos == 0 ){
            // Si no hay artículos, retorna un mensaje de error
            return back ()->with('error','error');
        } else {
            // Recopilación de información de la requisición y generación del nuevo PDF
            $notas = $req->Notas;

            // Procesamiento de los datos de la solicitud y del empleado
            if (!empty($req->urgencia)) {
                $urgencia = 'Requisición urgente';
                try {
                    // Le da formato dd/mm/aaaa a la fecha creacion de la requisición
                    $fechaProgramada = date('d/m/Y', strtotime($req->dias));
                } catch (\Exception $e) {
                    // Manejar el error si el formato es incorrecto
                    $fechaProgramada = null;

                    return $e;
                    // Puedes lanzar una excepción o registrar el error si es necesario
                    // throw new \Exception('Formato de fecha no válido');
                }
            } else {
                $urgencia = null;
                $fechaProgramada = null;
            }

            $mantenimiento = $req->mantenimiento;
            $datos = Requisiciones::select('requisiciones.id_requisicion','requisiciones.unidad_id','requisiciones.created_at','requisiciones.pdf','requisiciones.notas','requisiciones.usuario_id','users.nombres','users.apellidoP','users.apellidoM','users.rol','users.departamento')
            ->join('users','requisiciones.usuario_id','=','users.id')
            ->where('requisiciones.id_requisicion',$id)
            ->first();

            $datos->unidad_id = $req->unidad;
            // Eliminar el archivo PDF anterior si existe
            $fileToDelete = public_path($datos->pdf);

            //Si existe el archivo lo elimina
            if (file_exists($fileToDelete)) {
                unlink($fileToDelete);
            }

            // Recuperar artículos por requisición
            $articulos = Articulos::where('requisicion_id',$id)->get();

            //Valida si la requisicion tiene una unidad asignada y recupera su información
            if(!empty($datos->unidad_id)){
                $unidad = Unidades::where('id_unidad',$datos->unidad_id)->first();
            }

            // Generar el nombre y ruta del nuevo archivo PDF
            $nombreArchivo = 'requisicion_' . $datos->id_requisicion . '.pdf';
            $rutaDescargas = 'requisiciones/' . $nombreArchivo;

            // Incluir el archivo Requisicion.php y pasar la ruta del archivo como una variable
            ob_start(); // Iniciar el búfer de salida
            include(public_path('/pdf/TCPDF-main/examples/RequisicionEditada.php'));
            ob_end_clean();

            // Actualización del estado de la requisición a 'Solicitado'

            if(session('departamento') === "Mantenimiento"){
                Requisiciones::where('id_requisicion',$id)->update([
                    "unidad_id"=>$req->unidad,
                    "pdf"=>$rutaDescargas,
                    "urgencia"=>$req->urgencia,
                    "fecha_programada"=>$req->dias,
                    "notas"=>$notas,
                    "estado"=>'Solicitado',
                    "mantenimiento"=>$mantenimiento,
                    "updated_at"=>Carbon::now(),
                ]);
            } else{
                Requisiciones::where('id_requisicion',$id)->update([
                    "estado"=>'Solicitado',
                    "pdf"=>$rutaDescargas,
                    "urgencia"=>$req->urgencia,
                    "fecha_programada"=>$req->dias,
                    "notas"=>$notas,
                    "updated_at"=>Carbon::now(),
                ]);
            }

            // Redirección al usuario con mensaje de éxito
            return redirect('requisiciones/GtArea')->with('editado','editado');
        }
    }

    public function tablePagosFijos(){
        // Obtener los pagos fijos y detalles asociados específicos del usuario logueado
        $pagos = Pagos_Fijos::select('pagos_fijos.*','servicios.id_servicio','servicios.nombre_servicio','proveedores.nombre','pagos_fijos.comprobante_pago')
        ->where('pagos_fijos.usuario_id',session('loginId'))
        ->join('servicios','pagos_fijos.servicio_id','servicios.id_servicio')
        ->join('proveedores','servicios.proveedor_id','proveedores.id_proveedor')
        ->orderBy('id_pago','desc')
        ->get();

        // Obtener todos los servicios activos asociados al usuario logueado
        $servicios = Servicios::select('servicios.id_servicio','servicios.nombre_servicio','proveedores.id_proveedor','proveedores.nombre')
        ->join('proveedores','servicios.proveedor_id','=','proveedores.id_proveedor')
        ->orderBy('servicios.nombre_servicio','asc')
        ->where('servicios.estatus','1')
        ->where('servicios.usuario_id',session('loginId'))
        ->get();

        // Obtener todos los proveedores activos
        $proveedores = Proveedores::where('estatus','1')
        ->orderBy('nombre','asc')
        ->get();

        // Cargar y mostrar la vista con los datos necesarios
        return view('GtArea.vPagos',compact('pagos','servicios','proveedores'));
    }

    /*
      Prepara y muestra la vista para la creación de un nuevo pago.

      Este método recupera todos los servicios activos y sus proveedores correspondientes para que los usuarios puedan seleccionar
      de una lista al momento de crear un nuevo pago fijo. La selección de servicios y proveedores solo incluye aquellos que están activos,
      asegurando que los datos presentados estén actualizados y sean relevantes. Estos datos se utilizan para llenar los campos en el formulario
      de creación de pago, simplificando el proceso de entrada de datos por parte del usuario.

      Devuelve la vista 'GtArea.crearPago', pasando las listas de servicios y proveedores activos para su visualización y selección.
    */
    public function createPago(){
        // Obtener todos los servicios activos y sus proveedores
        $servicios = Servicios::select('servicios.id_servicio','servicios.nombre_servicio','proveedores.nombre')
        ->join('proveedores','servicios.proveedor_id','=','proveedores.id_proveedor')
        ->orderBy('servicios.nombre_servicio','asc')
        ->where('servicios.estatus','1')
        ->get();

        // Obtener todos los proveedores activos
        $proveedores = Proveedores::where('estatus','1')
        ->orderBy('nombre','asc')
        ->get();

        // Cargar y mostrar la vista con los datos necesarios para la creación de un nuevo pago
        return view('GtArea.crearPago',compact('servicios','proveedores'));
    }

    /*
      Crea un nuevo servicio y lo asocia con un proveedor específico y el usuario actual.

      Este método recoge datos desde un formulario enviado a través de una solicitud HTTP, incluyendo el nombre del servicio,
      el ID del proveedor asociado y asocia automáticamente el servicio con el ID del usuario actual en sesión.
      El servicio se marca como activo ('estatus' = 1) y se registra la fecha y hora de creación y actualización. Tras registrar
      el servicio correctamente, el usuario es redirigido a la página anterior con una notificación de éxito.

      Redirige al usuario a la página anterior con una notificación que indica que el servicio ha sido creado exitosamente.
    */
    public function createServicio(Request $req){
        // Crea un nuevo registro de servicio en la base de datos con los datos proporcionados en la solicitud
        Servicios::create([
            "nombre_servicio"=>$req->nombre,
            "proveedor_id"=>$req->proveedor,
            "usuario_id"=>session('loginId'),
            "estatus"=>1,
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        // Redirige al usuario a la página anterior con un mensaje de éxito
        return back()->with('servicio','servicio');
    }

    /*
      Actualiza los detalles de un servicio existente basado en la entrada del usuario.

      Este método recibe datos a través de una solicitud HTTP, que incluyen el nuevo nombre del servicio y el ID del proveedor asociado.
      Utiliza estos datos para actualizar el registro correspondiente del servicio en la base de datos. El método también registra
      la fecha y hora de la actualización.

      @param int $id El ID del servicio que se está editando.

      Redirige al usuario a la página anterior con una notificación que indica que el servicio ha sido editado exitosamente.
    */
    public function editServicio(Request $req, $id){
        // Actualiza el registro del servicio en la base de datos con los datos proporcionados
        Servicios::where('id_servicio',$id)->update([
            "nombre_servicio"=>$req->input('nombre'),
            "proveedor_id"=>$req->input('proveedor'),
            "updated_at"=>Carbon::now(),
        ]);

        // Redirige al usuario a la página anterior con un mensaje de éxito
        return back()->with('servEditado','servEditado');
    }

    /*
      Desactiva un servicio existente cambiando su estado a inactivo.

      Este método actualiza el estatus de un servicio específico a "0", lo cual se utiliza para indicar que el servicio está
      desactivado o inactivo. Este enfoque mantiene la integridad de los datos al evitar la eliminación completa de registros,
      lo que puede ser útil para mantener un historial o para posibles activaciones futuras. La fecha de la última actualización
      también se registra automáticamente.

      @param int $id El ID del servicio que se va a desactivar.

      Redirige al usuario a la página anterior con una notificación que indica que el servicio ha sido desactivado exitosamente.
    */
    public function deleteServicio ($id){
        // Actualiza el registro del servicio para marcarlo como inactivo
        Servicios::where('id_servicio',$id)->update([
            "estatus"=>"0",
            "updated_at"=>Carbon::now(),
        ]);

        // Redirige al usuario a la página anterior con un mensaje de éxito
        return back()->with('servDelete','servDelete');
    }

    public function insertPago (Request $req){
        $servicio_id = $req->input('servicio');
        $Nota = $req->input('Notas');
        $importe = $req->input('importe');
        // Definición y serialización de los datos del empleado
        $datosEmpleado[] = [
            'idEmpleado' => session('loginId'),
            'nombres' => session('loginNombres'),
            'apellidoP' => session('loginApepat'),
            'apellidoM' => session('loginApemat'),
            'rol' => session('rol'),
            'dpto' =>session('departamento')
        ];

        // Determinación del ID de la nueva requisición y preparación del PDF
        $ultimoPago = Pagos_Fijos::select('id_pago')->latest('id_pago')->first();
        if (empty($ultimoPago)){
            $idcorresponde = 1;
        } else {
            $idcorresponde = $ultimoPago->id_pago + 1;
        }

        $servicio = Servicios::select('servicios.nombre_servicio','proveedores.*')
        ->join('proveedores','servicios.proveedor_id','=','proveedores.id_proveedor')
        ->where('servicios.id_servicio',$servicio_id)
        ->first();

        // Serializar los datos del empleado y almacenarlos en un archivo para pasarlos al PDF
        $datosSerializados = serialize($datosEmpleado);
        $rutaArchivo = storage_path('app/datos_empleado.txt');
        file_put_contents($rutaArchivo, $datosSerializados);

        // Se genera el nombre y ruta para guardar PDF
        $nombreArchivo = 'pagoFijo_' . $idcorresponde . '.pdf';
        $rutaDescargas = 'pagosFijos/' . $nombreArchivo;

        // Incluir el archivo Requisicion.php y pasar la ruta del archivo como una variable
        ob_start(); //* Iniciar el búfer de salida para pasar las variables al PDF
        include(public_path('/pdf/TCPDF-main/examples/orden_pago.php'));
        ob_end_clean();

        Pagos_Fijos::create([
            "id_pago"=>$idcorresponde,
            "servicio_id"=>$servicio_id,
            "usuario_id"=>session('loginId'),
            "costo_total"=>$importe,
            "pdf"=>$rutaDescargas,
            "estado"=>'Solicitado',
            "notas"=>$Nota,
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        return redirect()->route('pagosFDir')->with('pago','pago');
    }

    public function updatePago(Request $req, $id){
        $servicio_id = $req->input('servicio');
        $Nota = $req->input('Notas');
        $importe = $req->input('importe');
        // Definición y serialización de los datos del empleado
        $datosEmpleado[] = [
            'idEmpleado' => session('loginId'),
            'nombres' => session('loginNombres'),
            'apellidoP' => session('loginApepat'),
            'apellidoM' => session('loginApemat'),
            'rol' => session('rol'),
            'dpto' =>session('departamento')
        ];

        $idcorresponde = $id;
        $pdf = Pagos_Fijos::select('pdf')
        ->where('id_pago',$id)
        ->first();

        $servicio = Servicios::select('servicios.nombre_servicio','proveedores.*')
        ->join('proveedores','servicios.proveedor_id','=','proveedores.id_proveedor')
        ->where('servicios.id_servicio',$servicio_id)
        ->first();

        //Guarda la ruta del archivo PDF de la requisicion
        $fileToDelete = public_path($pdf->pdf);

        //Si existe el archivo lo elimina
        if (file_exists($fileToDelete)) {
            unlink($fileToDelete);
        }

        // Serializar los datos del empleado y almacenarlos en un archivo para pasarlos al PDF
        $datosSerializados = serialize($datosEmpleado);
        $rutaArchivo = storage_path('app/datos_empleado.txt');
        file_put_contents($rutaArchivo, $datosSerializados);

        // Se genera el nombre y ruta para guardar PDF
        $nombreArchivo = 'pagoFijo_' . $idcorresponde . '.pdf';
        $rutaDescargas = 'pagosFijos/' . $nombreArchivo;

        // Incluir el archivo Requisicion.php y pasar la ruta del archivo como una variable
        ob_start(); //* Iniciar el búfer de salida para pasar las variables al PDF
        include(public_path('/pdf/TCPDF-main/examples/orden_pago.php'));
        ob_end_clean();

        Pagos_Fijos::where('id_pago',$id)->update([
            "servicio_id"=>$servicio_id,
            "usuario_id"=>session('loginId'),
            "costo_total"=>$importe,
            "pdf"=>$rutaDescargas,
            "estado"=>'Solicitado',
            "notas"=>$Nota,
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        return back()->with('editado','editado');
    }

    public function deletePagos($id){
        //Consulta la orden de pago seleccionada
        $pago = Pagos_fijos::where('id_pago',$id)->first();

        //Guarda la ruta del archivo PDF de la orden
        $fileToDelete = public_path($pago->pdf);

        //Si existe el archivo lo elimina
        if (file_exists($fileToDelete)) {
            unlink($fileToDelete);
        }

        Pagos_Fijos::where('id_pago',$id)->delete();

        return back()->with('eliminado','eliminado');
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
        $solicitudes = Requisiciones::where('requisiciones.estado','!=','Rechazado')->where('requisiciones.estado','!=','Finalizado')
        ->select('requisiciones.id_requisicion','requisiciones.created_at','requisiciones.unidad_id','requisiciones.estado','us.departamento','us.nombres','requisiciones.created_at','requisiciones.pdf', DB::raw('MAX(comentarios.detalles) as detalles'),'users.rol',DB::raw('MAX(comentarios.created_at) as fechaCom'))
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
        $datos = Requisiciones::select('requisiciones.id_requisicion','requisiciones.unidad_id','requisiciones.mantenimiento as mant','requisiciones.created_at','requisiciones.pdf','requisiciones.notas','requisiciones.usuario_id','users.nombres','users.apellidoP','users.apellidoM','users.rol','users.departamento')
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
    public function deleteCotiza($id, $rid){
        // Elimina la cotización específica por su ID
        Cotizaciones::where('id_cotizacion', $id)->delete();

        $n_cotizaciones = Cotizaciones::where('requisicion_id',$rid)->count();

        if ($n_cotizaciones == 0){
            Requisiciones::where('id_requisicion',$rid)->update([
                "estado"=>"Aprobado",
                "updated_at"=>Carbon::now(),
            ]);
        }

        // Redirige al usuario a la página anterior con un mensaje de confirmación
        return back()->with('eliminado','eliminado');
    }

    /*
      Recupera y muestra una tabla detallada de pagos fijos, excluyendo aquellos que han sido rechazados.

      Este método consulta la base de datos para obtener un listado completo de los pagos fijos activos, incluyendo
      detalles relevantes como el ID del pago, el servicio asociado, el nombre del proveedor, y el nombre del usuario
      que gestionó el pago. La información de los servicios y proveedores activos también se recopila para facilitar
      la filtración y la gestión dentro de la interfaz de usuario.

      NOTA: Esta función unicamente está habilitada para el jefe de área de Finanzas ya que es quien puede visualizar las
      solicitudes de pagos fijos.

      Devuelve la vista 'GtArea.pagos', pasando los datos de los pagos, servicios y proveedores para su visualización en forma de tabla.
    */
    public function tablePagos(){
        // Obtener los pagos fijos y detalles asociados
        $pagos = Pagos_Fijos::select('pagos_fijos.*','servicios.id_servicio','servicios.nombre_servicio','users.nombres as usuario','proveedores.nombre')
        ->join('servicios','pagos_fijos.servicio_id','servicios.id_servicio')
        ->join('proveedores','servicios.proveedor_id','proveedores.id_proveedor')
        ->join('users','pagos_fijos.usuario_id','=','users.id')
        ->orderBy('id_pago','desc')
        ->where('pagos_fijos.estado','!=','Rechazado')
        ->get();

        // Obtener todos los servicios activos y sus proveedores
        $servicios = Servicios::select('servicios.id_servicio','servicios.nombre_servicio','proveedores.id_proveedor','proveedores.nombre')
        ->join('proveedores','servicios.proveedor_id','=','proveedores.id_proveedor')
        ->orderBy('servicios.nombre_servicio','asc')
        ->where('servicios.estatus','1')
        ->get();

        // Obtener todos los proveedores activos
        $proveedores = Proveedores::where('estatus','1')
        ->orderBy('nombre','asc')
        ->get();

        // Cargar y mostrar la vista con los datos necesarios para la revisión de pagos
        return view('GtArea.pagos',compact('pagos','servicios','proveedores'));
    }

    /*
      Registra un comprobante de pago para un pago fijo específico y actualiza su estado a "Pagado".

      Este método maneja la carga de un comprobante de pago en formato PDF para un pago fijo determinado por el ID proporcionado.
      Valida que el archivo haya sido cargado correctamente y cumpla con los criterios especificados (como el tipo de archivo y tamaño máximo).
      Si se carga un archivo válido, se almacena en el sistema de archivos y se actualiza el registro del pago para incluir la ruta del archivo
      y cambiar el estado del pago a "Pagado". Si no se carga un archivo válido, el estado del pago se actualiza a "Pagado", pero sin
      almacenar ningún comprobante. Finalmente, el usuario es redirigido a la página anterior con una notificación del resultado del proceso.

      @param int $id El ID del pago que se está actualizando.

      NOTA: Esta función unicamente está habilitada para el jefe de área de Finanzas ya que es quien puede registrar los pagos de las
      solicitudes de pagos fijos.

      Redirige al usuario a la página anterior con una notificación que indica si el pago fue registrado exitosamente.
    */
    public function registrarPago(Request $req, $id){
        // Verifica que se haya subido un archivo y que sea válido
        if ($req->hasFile('comprobante_pago') && $req->file('comprobante_pago')->isValid()){

            // Validar el archivo subido
            $req->validate([
                'comprobante_pago' => 'required|file|max:10240', // Ajusta el tamaño máximo según tus necesidades
            ]);

            // Se genera el nombre y ruta para guardar PDF
            $nombreArchivo = 'comprobantePago_' . $id . '.pdf';
            $rutaDescargas = 'comprobantesPagos/' . $nombreArchivo;

            // Almacenar el archivo en el sistema de archivos
            $archivo = $req->file('comprobante_pago');
            $archivo->storeAs('comprobantesPagos', $nombreArchivo, 'public');

            // Actualizar el registro del pago con la ruta del comprobante y cambiar el estado a "Pagado"
            Pagos_Fijos::where('id_pago',$id)->update([
                "comprobante_pago"=>$rutaDescargas,
                "estado"=>"Pagado",
                "updated_at"=>Carbon::now()
            ]);

            // Redirige al usuario a la página anterior con un mensaje de confirmación
            return back()->with('pagado','pagado');

        } else{
            // Si no se existe un archivo y no es válido, solo actualizar el estado a "Pagado"
            Pagos_Fijos::where('id_pago',$id)->update([
                "estado"=>"Pagado",
                "updated_at"=>Carbon::now()
            ]);

            // Redirige al usuario a la página anterior con un mensaje de confirmación
            return back()->with('pagado','pagado');
        }
    }

    /*
      Cambia el estado de un pago fijo a "Rechazado" y actualiza la información en la base de datos.

      Este método se utiliza para manejar situaciones en las cuales un pago fijo necesita ser marcado como rechazado,
      generalmente debido a errores en el proceso de pago, problemas con la validación del pago, o cualquier otra
      razón administrativa. Al cambiar el estado a "Rechazado", el sistema efectivamente invalida el pago,
      permitiendo acciones correctivas o adicionales según sea necesario. El método también registra el momento
      exacto en que se realiza esta acción para mantener una trazabilidad adecuada.

      @param int $id El ID del pago fijo que se está actualizando.

      Redirige al usuario a la página anterior con una notificación de que el pago ha sido marcado como rechazado.
    */
    public function deletePago($id){
        // Actualiza el estado del pago a "Rechazado" en la base de datos
        Pagos_Fijos::where('id_pago',$id)->update([
            "estado"=>'Rechazado',
            "updated_at"=>Carbon::now(),
        ]);

        // Redirige al usuario a la página anterior con un mensaje de confirmación
        return back()->with('eliminado','eliminado');
    }

    /*
      Recupera y muestra una lista detallada de todas las órdenes de compra activas que no están asociadas a requisiciones rechazadas.

      Este método consulta la base de datos para obtener un listado completo de las órdenes de compra en el sistema,
      excluyendo aquellas relacionadas con requisiciones que han sido rechazadas. Los datos recopilados incluyen el ID de la orden,
      detalles de la requisición asociada, información del administrador que gestionó la orden, datos del proveedor, PDFs de las
      cotizaciones, comprobantes de pago, y otros documentos relevantes. Esta información es vital para permitir una gestión
      eficaz y una revisión detallada de todas las compras realizadas dentro de la organización. Los datos son luego presentados
      en una vista específica, permitiendo a los usuarios de la gerencia tener acceso fácil y ordenado a la información financiera.

      NOTA: Esta función unicamente está habilitada para el jefe de área de Finanzas ya que es quien puede consultar las ordenes
      de compra de las requisiciones, esto con fines de registrar pagos y revisar cantidades, proveedores, etc.

      Devuelve la vista 'GtArea.ordenesCompras', pasando los datos de las órdenes de compra para su visualización.
    */
    public function tableOrdenesCompras(){
        // Obtener las órdenes de compra con información relevante de varias tablas relacionadas
        $ordenes = Orden_compras::select('orden_compras.id_orden','requisiciones.id_requisicion','requisiciones.estado','users.nombres','cotizaciones.pdf as cotPDF','proveedores.nombre as proveedor','orden_compras.costo_total','orden_compras.estado as estadoComp','orden_compras.pdf as ordPDF', 'orden_compras.created_at','orden_compras.comprobante_pago')
        ->join('users','orden_compras.admin_id','=','users.id')
        ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
        ->join('proveedores','orden_compras.proveedor_id','=','proveedores.id_proveedor')
        ->where('requisiciones.estado','!=','Rechazado')
        ->orderBy('orden_compras.created_at','desc')
        ->get();

        // Cargar y mostrar la vista con los datos necesarios
        return view ('GtArea.ordenesCompras',compact('ordenes'));
    }

    /*
      Finaliza una orden de compra subiendo un comprobante de pago y actualizando su estado a "Pagado".

      Este método permite a los usuarios cargar un comprobante de pago en formato PDF para una orden de compra específica.
      Si el archivo se carga correctamente y cumple con los criterios especificados (tipo de archivo y tamaño máximo), el archivo se almacena,
      y se actualiza el registro de la orden de compra para incluir la ruta del archivo y cambiar el estado de la orden a "Pagado".
      Si no se carga un archivo, el método maneja esta situación proporcionando un mensaje de error adecuado y actualizando
      el estado de la orden a "Pagado" sin almacenar ningún comprobante.

      @param int $id El ID de la orden de compra que se está actualizando.

      NOTA: Esta función unicamente está habilitada para el jefe de área de Finanzas ya que es quien puede registrar los pagos

      Devuelve una redirección a la página anterior con una notificación que indica si la orden fue finalizada exitosamente o
      devuelve un mensaje de error si el archivo no es reconocido.
    */
    public function FinalizarC(Request $req, $id){
        // Verifica que se haya subido un archivo y que sea válido
        if ($req->hasFile('comprobante_pago') && $req->file('comprobante_pago')->isValid()){

            // Validar el archivo subido
            $req->validate([
                'comprobante_pago' => 'required|file|max:10240', // Ajusta el tamaño máximo según tus necesidades
            ]);

            // Se genera el nombre y ruta para guardar PDF
            $nombreArchivo = 'comprobantePagoOrden_' . $id . '.pdf';
            $rutaDescargas = 'comprobantesPagosOrden/' . $nombreArchivo;

            // Almacenar el archivo en el sistema de archivos
            $archivo = $req->file('comprobante_pago');
            $archivo->storeAs('comprobantesPagosOrden', $nombreArchivo, 'public');

            // Actualizar el registro de la orden con la ruta del comprobante y cambiar el estado a "Pagado"
            Orden_compras::where('id_orden',$id)->update([
                "comprobante_pago"=>$rutaDescargas,
                "estado"=>"Pagado",
                "updated_at"=>Carbon::now()
            ]);

            // Redirige al usuario a la página anterior con un mensaje de confirmación
            return back()->with('pagado','pagado');

        } else{
            //Registra el pago sin coprobante en caso de que no se cargue archivo.
            Orden_compras::where('id_orden',$id)->update([
                "estado"=>"Pagado",
                "updated_at"=>Carbon::now()
            ]);

            // Redirige al usuario a la página anterior con un mensaje de confirmación
            return back()->with('pagado','pagado');
        }
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
        $unidades = Unidades::leftJoin('camion_servicios_preventivos as servicios','unidades.id_unidad','=','servicios.unidad_id')
        ->where('estatus','1')
        ->where('id_unidad','!=','1')
        ->where('estado','Activo')->orderBy('id_unidad','asc')->get();

        $unidades->each(function ($unidad) {
            $filtro_aireG = 100-((($unidad->kilometraje-$unidad->filtro_aire_grande)/30000)*100);
            $filtro_aireC = 100-((($unidad->kilometraje-$unidad->filtro_aire_chico)/45000)*100);
            $filtro_diesel = 100-((($unidad->kilometraje-$unidad->filtro_diesel)/15000)*100);
            $filtro_aceite = 100-((($unidad->kilometraje-$unidad->filtro_aceite)/15000)*100);
            $wk1060_trampa = 100-((($unidad->kilometraje-$unidad->wk1016_trampa)/45000)*100);
            $aceite_motor = 100-((($unidad->kilometraje-$unidad->aceite_motor)/15000)*100);
            $filtro_urea = 100-((($unidad->kilometraje-$unidad->filtro_urea)/45000)*100);
            $anticongelante = 100-((($unidad->kilometraje-$unidad->anticongelante)/100000)*100);
            $aceite_direccion = 100-((($unidad->kilometraje-$unidad->aceite_direccion)/150000)*100);
            $banda_poles = 100-((($unidad->kilometraje-$unidad->banda_poles)/90000)*100);
            $ajuste_frenos = 100-((($unidad->kilometraje-$unidad->ajuste_frenos)/15000)*100);
            $engrasado_chasis = 100-((($unidad->kilometraje-$unidad->engrasado_chasis)/15000)*100);

            $promedio = ($filtro_aireG + $filtro_aireC + $filtro_diesel + $filtro_aceite + $wk1060_trampa +
            $aceite_motor + $filtro_urea + $anticongelante + $aceite_direccion + $banda_poles +
            $ajuste_frenos + $engrasado_chasis) / 12; // Dividiendo por 12 para obtener el promedio de 12 valores

            $unidad->tiempo = $promedio; // Agregar el promedio como un nuevo atributo
        });

        // Carga y muestra la vista con el listado de unidades activas
        return view('GtArea.mantenimiento',compact('unidades'));
    }

    /*
      Calcula y muestra el estado del mantenimiento preventivo de una unidad específica.

      Este método se encarga de obtener los datos de kilometraje de una unidad y combinarlos con registros históricos de mantenimiento
      para calcular la vida útil restante de varios componentes según su uso y los intervalos de mantenimiento recomendados.
      Calcula el porcentaje de vida útil restante para cada componente clave como filtros de aire, de diesel, de aceite, trampas de WK,
      aceite del motor, entre otros. Estos cálculos permiten a los administradores y técnicos de mantenimiento entender mejor cuándo se
      requiere mantenimiento preventivo, optimizando la gestión y mantenimiento de la flota.

      @param int $id El ID de la unidad para la que se realiza el cálculo de mantenimiento.

      Devuelve la vista 'GtArea.infoMantenimiento', pasando los datos calculados y los detalles de la unidad para su visualización.
    */
    public function infoMantenimiento ($id){
        // Obtener los detalles de la unidad y el último servicio registrado
        $unidad = Unidades::where('id_unidad',$id)->first();
        $kmInicial = $unidad->kilometraje;
        $servicio = CamionServicioPreventivo::where('unidad_id',$id)->first();

        // Cálculos para determinar el porcentaje de vida útil restante de cada componente
        $filtro_aireG = 100-((($kmInicial-$servicio->filtro_aire_grande)/30000)*100);
        $filtro_aireC = 100-((($kmInicial-$servicio->filtro_aire_chico)/45000)*100);
        $filtro_diesel = 100-((($kmInicial-$servicio->filtro_diesel)/15000)*100);
        $filtro_aceite = 100-((($kmInicial-$servicio->filtro_aceite)/15000)*100);
        $wk1060_trampa = 100-((($kmInicial-$servicio->wk1016_trampa)/45000)*100);
        $aceite_motor = 100-((($kmInicial-$servicio->aceite_motor)/15000)*100);
        $filtro_urea = 100-((($kmInicial-$servicio->filtro_urea)/45000)*100);
        $anticongelante = 100-((($kmInicial-$servicio->anticongelante)/100000)*100);
        $aceite_direccion = 100-((($kmInicial-$servicio->aceite_direccion)/150000)*100);
        $banda_poles = 100-((($kmInicial-$servicio->banda_poles)/90000)*100);
        $ajuste_frenos = 100-((($kmInicial-$servicio->ajuste_frenos)/15000)*100);
        $engrasado_chasis = 100-((($kmInicial-$servicio->engrasado_chasis)/15000)*100);

        // Preparar los datos para la vista
        $datos [] = [
            "filtro_aireC" =>$filtro_aireG,
            "filtro_aireG" =>$filtro_aireC,
            "filtro_diesel" =>$filtro_diesel,
            "filtro_aceite" =>$filtro_aceite,
            "wk1060_trampa" =>$wk1060_trampa,
            "aceite_motor" =>$aceite_motor,
            "filtro_urea" =>$filtro_urea,
            "anticongelante" =>$anticongelante,
            "aceite_direccion" =>$aceite_direccion,
            "banda_poles" =>$banda_poles,
            "ajuste_frenos" =>$ajuste_frenos,
            "engrasado_chasis" =>$engrasado_chasis,
        ];

        // Carga y muestra la vista con los calculos de mantenimiento.
        return view('GtArea.infoMantenimiento',compact('unidad','datos'));
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
