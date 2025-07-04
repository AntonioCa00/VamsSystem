<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\Controller; // Asegúrate de incluir esta línea correctamente
use Illuminate\Support\Str;
use Illuminate\Http\Request;
//----------MODELOS-------------
use App\Models\Almacen;
use App\Models\Entradas;
use App\Models\Salidas;
use App\Models\User;
use App\Models\Unidades;
use App\Models\Proveedores;
use App\Models\Articulos;
use App\Models\Requisiciones;
use App\Models\Cotizaciones;
use App\Models\Orden_Compras;
use App\Models\Servicios;
use App\Models\Logs;
use App\Models\Pagos_Fijos;
use App\Models\unidadServicio;
//-------PHPOFFICE---------
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use PhpOffice\PhpSpreadsheet\IOFactory;
//-------DATABASE---------
use DB;
//-------FECHAS-----------
use Carbon\Carbon;

class controladorCompras extends Controller
{
    /*
      TODO: Recopila datos para la visualización de informes de gestión en el área correspondiente.

      Este método se encarga de compilar datos detallados de operaciones de compra por mes y totales anuales,
      así como el conteo de requisiciones completas y pendientes. Utiliza la clase Orden_compras para sumar los costos totales
      de las compras realizadas en cada mes del año actual y calcula los totales de compras para el mes y año en curso.
      Además, cuenta las requisiciones en estado 'Comprado' y las requisiciones pendientes que no están 'Compradas' ni 'Rechazadas'.

      Retorna la vista 'Compras.index' con los datos compilados para informes de gestión.
    */
    public function index(){
        // Datos actuales y preparación de sumas de costos de orden de compra por mes y totales anuales
        $anio_actual = date('Y');

        // Consultas para sumar los costos totales por cada mes del año actual
        $EneroPagos = Pagos_Fijos::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as enero"))
            ->whereBetween('created_at', ["$anio_actual-01-01 00:00:00", "$anio_actual-01-31 23:59:59"])
            ->first();
        $EneroCompras = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as enero"))
            ->whereBetween('created_at', ["$anio_actual-01-01 00:00:00", "$anio_actual-01-31 23:59:59"])
            ->first();
        $Enero = $EneroPagos->enero + $EneroCompras->enero;

        $FebreroPagos = Pagos_Fijos::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as febrero"))
            ->whereBetween('created_at', ["$anio_actual-02-01 00:00:00", "$anio_actual-02-28 23:59:59"])
            ->first();
        $FebreroCompras = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as febrero"))
            ->whereBetween('created_at', ["$anio_actual-02-01 00:00:00", "$anio_actual-02-28 23:59:59"])
            ->first();
        $Febrero = $FebreroPagos->febrero + $FebreroCompras->febrero;

        $MarzoPagos = Pagos_Fijos::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as marzo"))
            ->whereBetween('created_at', ["$anio_actual-03-01 00:00:00", "$anio_actual-03-31 23:59:59"])
            ->first();
        $MarzoCompras = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as marzo"))
            ->whereBetween('created_at', ["$anio_actual-03-01 00:00:00", "$anio_actual-03-31 23:59:59"])
            ->first();
        $Marzo = $MarzoPagos->marzo + $MarzoCompras->marzo;

        $AbrilPagos = Pagos_Fijos::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as abril"))
            ->whereBetween('created_at', ["$anio_actual-04-01 00:00:00", "$anio_actual-04-30 23:59:59"])
            ->first();
        $AbrilCompras = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as abril"))
            ->whereBetween('created_at', ["$anio_actual-04-01 00:00:00", "$anio_actual-04-30 23:59:59"])
            ->first();
        $Abril = $AbrilPagos->abril + $AbrilCompras->abril;

        $MayoPagos = Pagos_Fijos::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as mayo"))
            ->whereBetween('created_at', ["$anio_actual-05-01 00:00:00", "$anio_actual-05-31 23:59:59"])
            ->first();
        $MayoCompras = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as mayo"))
            ->whereBetween('created_at', ["$anio_actual-05-01 00:00:00", "$anio_actual-05-31 23:59:59"])
            ->first();
        $Mayo = $MayoPagos->mayo + $MayoCompras->mayo;

        $JunioPagos = Pagos_Fijos::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as junio"))
            ->whereBetween('created_at', ["$anio_actual-06-01 00:00:00", "$anio_actual-06-30 23:59:59"])
            ->first();
        $JunioCompras = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as junio"))
            ->whereBetween('created_at', ["$anio_actual-06-01 00:00:00", "$anio_actual-06-30 23:59:59"])
            ->first();
        $Junio = $JunioPagos->junio + $JunioCompras->junio;

        $JulioPagos = Pagos_Fijos::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as julio"))
            ->whereBetween('created_at', ["$anio_actual-07-01 00:00:00", "$anio_actual-07-31 23:59:59"])
            ->first();
        $JulioCompras = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as julio"))
            ->whereBetween('created_at', ["$anio_actual-07-01 00:00:00", "$anio_actual-07-31 23:59:59"])
            ->first();
        $Julio = $JulioPagos->julio + $JulioCompras->julio;

        $AgostoPagos = Pagos_Fijos::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as agosto"))
            ->whereBetween('created_at', ["$anio_actual-08-01 00:00:00", "$anio_actual-08-31 23:59:59"])
            ->first();
        $AgostoCompras = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as agosto"))
            ->whereBetween('created_at', ["$anio_actual-08-01 00:00:00", "$anio_actual-08-31 23:59:59"])
            ->first();
        $Agosto = $AgostoPagos->agosto + $AgostoCompras->agosto;

        $SeptiembrePagos = Pagos_Fijos::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as septiembre"))
            ->whereBetween('created_at', ["$anio_actual-09-01 00:00:00", "$anio_actual-09-30 23:59:59"])
            ->first();
        $SeptiembreCompras = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as septiembre"))
            ->whereBetween('created_at', ["$anio_actual-09-01 00:00:00", "$anio_actual-09-30 23:59:59"])
            ->first();
        $Septiembre = $SeptiembrePagos->septiembre + $SeptiembreCompras->septiembre;

        $OctubrePagos = Pagos_Fijos::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as octubre"))
            ->whereBetween('created_at', ["$anio_actual-10-01 00:00:00", "$anio_actual-10-31 23:59:59"])
            ->first();
        $OctubreCompras = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as octubre"))
            ->whereBetween('created_at', ["$anio_actual-10-01 00:00:00", "$anio_actual-10-31 23:59:59"])
            ->first();
        $Octubre = $OctubrePagos->octubre + $OctubreCompras->octubre;

        $NoviembrePagos = Pagos_Fijos::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as noviembre"))
            ->whereBetween('created_at', ["$anio_actual-11-01 00:00:00", "$anio_actual-11-30 23:59:59"])
            ->first();
        $NoviembreCompras = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as noviembre"))
            ->whereBetween('created_at', ["$anio_actual-11-01 00:00:00", "$anio_actual-11-30 23:59:59"])
            ->first();
        $Noviembre = $NoviembrePagos->noviembre + $NoviembreCompras->noviembre;

        $DiciembrePagos = Pagos_Fijos::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as diciembre"))
            ->whereBetween('created_at', ["$anio_actual-12-01 00:00:00", "$anio_actual-12-31 23:59:59"])
            ->first();
        $DiciembreCompras = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as diciembre"))
            ->whereBetween('created_at', ["$anio_actual-12-01 00:00:00", "$anio_actual-12-31 23:59:59"])
            ->first();
        $Diciembre = $DiciembrePagos->diciembre + $DiciembreCompras->diciembre;

        // Suma total de costos para el mes actual
        $mesActual = now()->format('m');
        $anioActual = now()->year;
        $totalRequisicionesMes = Orden_compras::whereMonth('created_at', $mesActual)->whereYear('created_at', $anioActual)->sum('costo_total');
        $totalPagosMes = Pagos_Fijos::whereMonth('created_at', $mesActual)->sum('costo_total');
        $TotalMes = $totalRequisicionesMes + $totalPagosMes;

        // Suma total de costos para el año en curso
        $totalRequisicionesAnio =Orden_compras::whereYear('created_at', $anioActual)->sum('costo_total');
        $totalPagosAnio= Pagos_Fijos::whereYear('created_at', $anioActual)->sum('costo_total');
        $TotalAnio = $totalRequisicionesAnio + $totalPagosAnio;

        $mantenimiento = Orden_compras::join('cotizaciones','Orden_Compras.cotizacion_id','cotizaciones.id_cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','requisiciones.id_requisicion')
        ->join('users','requisiciones.usuario_id','users.id')
        ->where('users.departamento','Mantenimiento')
        ->whereMonth('Orden_compras.created_at', $mesActual)->whereYear('Orden_compras.created_at',$anio_actual)->sum('costo_total');

        $almacen = Orden_compras::join('cotizaciones','Orden_Compras.cotizacion_id','cotizaciones.id_cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','requisiciones.id_requisicion')
        ->join('users','requisiciones.usuario_id','users.id')
        ->where('users.departamento','Almacen')
        ->whereMonth('Orden_compras.created_at', $mesActual)->whereYear('Orden_compras.created_at',$anio_actual)->sum('costo_total');

        $logistica = Orden_compras::join('cotizaciones','Orden_Compras.cotizacion_id','cotizaciones.id_cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','requisiciones.id_requisicion')
        ->join('users','requisiciones.usuario_id','users.id')
        ->where('users.departamento','Logistica')
        ->whereMonth('Orden_compras.created_at', $mesActual)->whereYear('Orden_compras.created_at',$anio_actual)->sum('costo_total');

        $rh = Orden_compras::join('cotizaciones','Orden_Compras.cotizacion_id','cotizaciones.id_cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','requisiciones.id_requisicion')
        ->join('users','requisiciones.usuario_id','users.id')
        ->where('users.departamento','RH')
        ->whereMonth('Orden_compras.created_at', $mesActual)->whereYear('Orden_compras.created_at',$anio_actual)->sum('costo_total');

        $gestoria = Orden_compras::join('cotizaciones','Orden_Compras.cotizacion_id','cotizaciones.id_cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','requisiciones.id_requisicion')
        ->join('users','requisiciones.usuario_id','users.id')
        ->where('users.departamento','Gestoria')
        ->whereMonth('Orden_compras.created_at', $mesActual)->whereYear('Orden_compras.created_at',$anio_actual)->sum('costo_total');

        $contabilidad = Orden_compras::join('cotizaciones','Orden_Compras.cotizacion_id','cotizaciones.id_cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','requisiciones.id_requisicion')
        ->join('users','requisiciones.usuario_id','users.id')
        ->where('users.departamento','Contabilidad')
        ->whereMonth('Orden_compras.created_at', $mesActual)->whereYear('Orden_compras.created_at',$anio_actual)->sum('costo_total');

        $sistemas = Orden_compras::join('cotizaciones','Orden_Compras.cotizacion_id','cotizaciones.id_cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','requisiciones.id_requisicion')
        ->join('users','requisiciones.usuario_id','users.id')
        ->where('users.departamento','Sistemas')
        ->whereMonth('Orden_compras.created_at', $mesActual)->whereYear('Orden_compras.created_at',$anio_actual)->sum('costo_total');

        $ventas = Orden_compras::join('cotizaciones','Orden_Compras.cotizacion_id','cotizaciones.id_cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','requisiciones.id_requisicion')
        ->join('users','requisiciones.usuario_id','users.id')
        ->where('users.departamento','Ventas')
        ->whereMonth('Orden_compras.created_at', $mesActual)->whereYear('Orden_compras.created_at',$anio_actual)->sum('costo_total');

        // Conteo de requisiciones completas
        $completas = Requisiciones::join('users','requisiciones.usuario_id','=','users.id')
        ->where('requisiciones.estado', 'Finalizado')
        ->count();

        // Conteo de requisiciones pendientes
        $pendiente = Requisiciones::join('users','requisiciones.usuario_id','=','users.id')
        ->where('requisiciones.estado','!=', 'Finalizado')
        ->where('requisiciones.estado','!=','Rechazado')
        ->count();

        // Pasar los datos a la vista
        return view("Compras.index",[
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
            'diciembre'  => $Diciembre,
            //Por departamentos
            'mantenimiento'=>$mantenimiento,
            'almacen'=>$almacen,
            'logistica'=>$logistica,
            'rh'=>$rh,
            'gestoria'=>$gestoria,
            'contabilidad'=>$contabilidad,
            'sistemas'=>$sistemas,
            'ventas'=>$ventas,
        ]);
    }

    //VISTAS DE LAS TABLAS
    /*
      TODO: Recupera y muestra todas las refacciones activas en el almacen.

      Este método consulta la base de datos para obtener un listado de todas las refacciones que tienen un estatus '1',
      indicando que están activas. La intención es proporcionar una visión general de las refacciones disponibles en el
      almacen para su gestión o asignación a tareas específicas.

      Retorna la vista 'Compras.refaccion', pasando el listado de refacciones activas para su visualización.
    */
    public function tableRefaccion(){
        // Recupera todas las refacciones del almacen que están activas (estatus = 1)
        $refacciones = Almacen::get()->where("estatus",1);

        // Carga y muestra la vista con el listado de refacciones activas
        return view('Compras.refaccion',compact('refacciones'));
    }

    //! ESTA FUNCION NO ESTA SIRVIENDO ACTUALMENTE
    public function tableEntradas(){
        $entradas = Entradas::select('entradas.id_entrada','requisiciones.pdf as reqPDF','orden_compras.pdf as ordPDF','entradas.factura','entradas.created_at')
        ->join('orden_compras','entradas.orden_id','=','orden_compras.id_orden')
        ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
        ->get();
        return view('Compras.entradas',compact('entradas'));
    }

    /*
      TODO: Recupera y muestra un listado de unidades activas, excluyendo una unidad específica.

      Este método consulta la base de datos para obtener un listado de todas las unidades que están marcadas como activas
      (estatus '1'), excluyendo la unidad con ID '1' por razones específicas de negocio o de la aplicación. Además, las unidades
      activas se ordenan en orden ascendente por su ID para facilitar su visualización y gestión.

      Retorna la vista 'Compras.unidad', pasando el listado de unidades activas para su visualización.
    */
    public function tableUnidad(){
        // Recupera las unidades activas, excluyendo la unidad con ID '1' y ordenándolas por ID de manera ascendente
        $unidades = Unidades::where('estatus','1')
        ->where('id_unidad','!=','1')
        ->where('estado','Activo')->orderBy('id_unidad','asc')
        ->get();

        // Carga y muestra la vista con el listado de unidades activas
        return view('Compras.unidad',compact('unidades'));
    }

    /*
      TODO: Muestra la vista para la creación de una nueva unidad.

      Este método se encarga de cargar y presentar la vista que contiene el formulario utilizado para la creación
      de nuevas unidades dentro del sistema. La vista proporcionará los campos necesarios para capturar la información
      esencial de la nueva unidad.

      Retorna la vista 'Compras.crearUnidad', que contiene el formulario para la creación de una nueva unidad.
    */
    public function CreateUnidad(){
        // Cargar y mostrar la vista con el formulario de creación de unidad
        return view('Compras.crearUnidad');
    }

    /*
      TODO: Inserta una nueva unidad en la base de datos con la información proporcionada a través de un formulario.

      Este método recibe datos de un formulario a través de una petición HTTP, incluyendo el ID de la unidad, tipo, estado,
      año de la unidad, marca, modelo, características, número de serie, y número de permiso. Utiliza estos datos para
      crear una nueva entrada en la base de datos para la unidad, asignándole un estatus '1' para marcarla como activa.

      @param  \Illuminate\Http\Request  $req La petición HTTP que contiene los datos del formulario.

      Redirige al usuario a la lista de unidades con una sesión flash que indica que la nueva unidad ha sido registrada exitosamente.
    */
    public function insertUnidad(Request $req){
        // Crea la nueva unidad con los datos proporcionados
        Unidades::create([
        "id_unidad"=>$req->input('id_unidad'),
        "tipo"=>$req->input('tipo'),
        "estado"=>$req->input('estado'),
        "anio_unidad"=>$req->input('anio_unidad'),
        "marca"=>$req->input('marca'),
        "modelo"=>$req->input('modelo'),
        "caracteristicas"=>$req->input('caracteristicas'),
        "n_de_serie"=>$req->input('serie'),
        "n_de_permiso"=>$req->input('permiso'),
        "estatus"=>"1",
        "kilometraje"=>$req->input('kilometraje'),
        "created_at"=>Carbon::now(),
        "updated_at"=>Carbon::now()
        ]);

        unidadServicio::create([
            "unidad_id" => $req->input('id_unidad'),
            "km_mantenimiento"=> $req->input('kilometraje'),
            "contador"=> 0,
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        // Redirige al usuario a la vista de unidades
        return redirect()->route('unidades')->with('regis','regis');
    }

    /*
      TODO: Muestra la vista para editar los detalles de una unidad específica.

      Este método se encarga de recuperar los detalles de una unidad específica, identificada por su ID, de la base de datos.
      La recuperación de esta información es crucial para pre-rellenar el formulario de edición en la vista con los datos actuales
      de la unidad, permitiendo así que los administradores o los usuarios con los permisos adecuados realicen cambios en la información
      de la unidad como tipo, estado, año, marca, modelo, características, número de serie, y número de permiso.

      @param  int  $id  El ID de la unidad cuyos detalles se van a editar.

      Retorna la vista 'Compras.editarUnidad', pasando los detalles de la unidad específica para su edición.
    */
    public function editUnidad($id){
        // Recupera los detalles de la unidad específica por su ID
        $unidad = Unidades::where('id_unidad',$id)->first();

        // Carga y muestra la vista con el formulario de edición de unidad, pasando los detalles de la unidad
        return view('Compras.editarUnidad',compact('unidad'));
    }

    /*
      TODO: Actualiza los detalles de una unidad específica en la base de datos con la información proporcionada por el formulario.

      Este método recibe datos de un formulario a través de una petición HTTP, incluyendo el ID de la unidad, tipo, estado,
      año de la unidad, marca, modelo, características, número de serie, y número de permiso. Utiliza estos datos para
      actualizar el registro de la unidad específica en la base de datos, identificado por el ID proporcionado.

      @param  int  $id  El ID de la unidad que se va a actualizar.

      Redirige al usuario a la lista de unidades con una sesión flash que indica que la unidad ha sido actualizada exitosamente.
    */
    public function updateUnidad(Request $req, $id){
        // Actualiza el registro de la unidad específico con los datos proporcionados
        Unidades::where('id_unidad',$id)->update([
            "id_unidad"=>$req->input('id_unidad'),
            "tipo"=>$req->input('tipo'),
            "estado"=>$req->input('estado'),
            "anio_unidad"=>$req->input('anio_unidad'),
            "marca"=>$req->input('marca'),
            "modelo"=>$req->input('modelo'),
            "caracteristicas"=>$req->input('caracteristicas'),
            "n_de_serie"=>$req->input('serie'),
            "n_de_permiso"=>$req->input('permiso'),
            "estatus"=>"1",
            "kilometraje"=>$req->input('kilometraje'),
            "updated_at"=>Carbon::now()
        ]);

        // Redirige al usuario a la lista de unidades con un mensaje de éxito
        return redirect()->route('unidades')->with('update','update');
    }

    /*
      TODO: Desactiva una unidad específica marcándola como inactiva en la base de datos.

      En lugar de eliminar el registro de la unidad, este método actualiza el campo 'estatus' a 0,
      indicando que la unidad está inactiva. Esta operación es crucial para mantener la integridad de los datos
      y permite la recuperación del registro en el futuro si es necesario. Además, se actualiza el campo 'updated_at'
      para reflejar el momento de la desactivación.

      @param  int  $id  El ID de la unidad que se va a desactivar.

      Redirige al usuario a la página anterior con una sesión flash que indica que la unidad ha sido desactivada exitosamente.
    */
    public function deleteUnidad($id){
        // Actualiza el registro de la unidad específica para marcarla como inactiva
        Unidades::where('id_unidad',$id)->update([
            "estatus"=>"0",
            "updated_at"=>Carbon::now()
        ]);

        // Redirige al usuario a la página anterior con un mensaje de confirmación
        return back()->with('eliminado','eliminado');
    }

    /*
      TODO: Marca una unidad específica como inactiva en la base de datos.

      Este método actualiza el estado de una unidad específica, identificada por su ID, a "Inactivo", lo que indica
      que la unidad ya no está en uso activo dentro del sistema. La fecha de la última actualización también se registra mediante el campo
      'updated_at'.

      @param  int  $id  El ID de la unidad que se va a marcar como inactiva.

      Redirige al usuario a la página anterior con una sesión flash que indica que la unidad ha sido marcada como inactiva exitosamente.
    */
    public function bajaUnidad($id){
        // Actualiza el registro de la unidad específica para marcarla como inactiva
        Unidades::where('id_unidad',$id)->update([
            "estado"=>"Inactivo",
            "updated_at"=>Carbon::now()
        ]);
        // Redirige al usuario a la página anterior con un mensaje de confirmación
        return back()->with('baja','baja');
    }

    /*
      TODO: ecupera y muestra todas las unidades inactivas para su potencial activación.

      Este método consulta la base de datos para obtener un listado de todas las unidades que actualmente están marcadas
      como "Inactivo". La intención es proporcionar a los administradores una visión general de las unidades que no están
      en uso activo pero que pueden ser reactivadas según sea necesario.

      Retorna la vista 'Compras.activaUnidad', pasando el listado de unidades inactivas para su visualización y potencial activación.
    */
    public function activarUnidad(){
        // Recupera las unidades marcadas como inactivas
        $unidades = Unidades::where("estado",'Inactivo')->get();

        // Carga y muestra la vista con el listado de unidades inactivas
        return view('Compras.activaUnidad',compact('unidades'));
    }

    /*
       TODO: Reactiva una unidad específica cambiando su estado a "Activo".

      Este método actualiza el estado de una unidad específica, identificada por su ID, a "Activo" en la base de datos.
       La fecha de la última actualización también se registra para mantener un seguimiento adecuado de las modificaciones.

      @param  int  $id  El ID de la unidad que se va a reactivar.

      Redirige al usuario a la lista de unidades con una sesión flash que indica que la unidad ha sido activada exitosamente.
    */
    public function activateUnidad($id){
        // Actualiza el estado de la unidad específica a "Activo"
        Unidades::where('id_unidad',$id)->update([
            "estado"=>"Activo",
            "updated_at"=>Carbon::now()
        ]);

        // Redirige al usuario a la lista de unidades con un mensaje de confirmación
        return redirect()->route('unidades')->with('activado','activado');
    }

    //! ESTA FUNCION NO ESTA EN FUNCIONAMIENTO ACTUALMENTE
    public function tableSalidas(){
        $salidas = Salidas::select('salidas.id_salida','requisiciones.pdf as reqPDF','salidas.cantidad','users.nombres','almacen.clave','almacen.ubicacion','almacen.descripcion','salidas.created_at')
        ->join('almacen','salidas.refaccion_id','=','almacen.clave')
        ->join('requisiciones','salidas.requisicion_id','=','requisiciones.id_requisicion')
        ->join('users','requisiciones.usuario_id','=','users.id')
        ->get();
        return view('Compras.salidas',compact('salidas'));
    }

    /*
      TODO: Recupera y muestra un listado de solicitudes que cumplen con ciertos criterios de estado, junto con información relevante.

      Este método realiza consultas complejas a la base de datos para obtener un listado de solicitudes que están en los estados
      'Aprobado', 'Cotizado', 'Validado', o 'Comprado'. Además de la información básica de la solicitud, se calcula y muestra la
      cantidad de artículos asociados a cada solicitud que tienen un estatus '0'. También se recupera información agrupada por
      departamento y por estados específicos de las solicitudes para posibles filtros o visualizaciones en la vista.

      Retorna la vista 'Compras.solicitudes', pasando el listado de solicitudes, departamentos, y estados de solicitudes para su visualización.
    */
    public function tableSolicitud(){
        // Consulta de solicitudes que cumplen con los criterios de estado especificados y cálculo de cantidad de artículos inactivos.
        $solicitudes = Requisiciones::select(
            'requisiciones.id_requisicion',
            'users.nombres',
            'requisiciones.unidad_id',
            'requisiciones.urgencia',
            'requisiciones.estado',
            'requisiciones.created_at',
            'requisiciones.pdf',
            'requisiciones.created_at as fecha_creacion',
            DB::raw('(SELECT COUNT(*) FROM articulos WHERE articulos.requisicion_id = requisiciones.id_requisicion AND articulos.estatus = 0) as cantidad_articulos'),
            DB::raw('MAX(comentarios.detalles) as detalles'),
            'us.rol',
            DB::raw('MAX(comentarios.created_at) as fechaCom')
        )
        ->leftJoin('comentarios', 'requisiciones.id_requisicion', '=', 'comentarios.requisicion_id')
        ->leftJoin('users as us', 'us.id', '=', 'comentarios.usuario_id')
        ->join('users', 'requisiciones.usuario_id', '=', 'users.id')
        ->where('requisiciones.estado', '!=', 'Finalizado')
        ->where('requisiciones.estado', '!=', 'Rechazado')
        ->where('requisiciones.estado', '!=', 'Solicitado')
        ->groupBy('requisiciones.id_requisicion')
        ->orderBy('requisiciones.created_at', 'desc')
        ->get();

        // Formatear fechas
        $solicitudes->transform(function ($solicitud) {
            $solicitud->fecha_creacion = Carbon::parse($solicitud->created_at)->format('d/m/Y');
            return $solicitud;
        });

        // Redirige al usuario a la lista de solicitudes con los datos recuperados
        return view('Compras.solicitudes',compact('solicitudes'));
    }

    /*
      TODO: Filtra y muestra un listado de solicitudes solicitadas.

      Este método maneja la lógica para consultar un listado de requisiciones que se encuentren en el estado de Solicitadas. 
      Esta consulta busca especificamente las requisiciones que se hayan hecho entre el día de la consulta y 4 meses atrás (idealmente
      es una antiguedad de una semana). Una vez que se obtenga el listado este se muestra en una vista en la cual se procesan las requisiciones
      mostradas.

      Retorna la vista 'Compras.corteSemanal', pasando el listado de requisiciones.
    */
    public function corteSemanal(){

        //Consulta las requisiciones pendientes de ser procesadas (estado 'Solicitado')
        $corte = Requisiciones::select('requisiciones.id_requisicion','users.nombres','users.apellidoP','users.departamento','requisiciones.pdf','requisiciones.urgencia','requisiciones.created_at')
        ->join('users','requisiciones.usuario_id','users.id')
        ->whereBetween('requisiciones.created_at', [Carbon::now()->submonths(4)->startOfMonth(), Carbon::now()])
        ->where('requisiciones.estado','Solicitado')
        ->get();

        // Redirige al usuario a la lista de solicitudes con los datos recuperados
        return view('Compras.corteSemanal',compact('corte'));
    }

    /*
      TODO: Procesa las requisiciones que pasaran de estatus.

      Este método se encarga de obtener las requisiciones que el encargado de compras haya seleccionado y cambia el estado a aprobado
      para dar el seguimiento adecuado dentro del flujo del sistema. Aquellas que no esten seleccionadas o no pertenezcan al arrelgo de las
      seleccionadas se le cambiará el estado a rechazado y esto permitirá que los solicitantes puedan verla y darle el seguimiento correcto.
      También dentro de este método se procesan las urgencias para que unicamente de paso a las requisiciones en urgencia y no afectar las que 
      no deben ser procesadas.


      Retorna la vista ruta de requisiciones con un mensaje de exito.
    */
    public function createCorte(Request $req)
    {
        // Obtener los artículos seleccionados del formulario,
        // o un arreglo vacío si no hay ninguno seleccionado.
        $articulosSeleccionados = $req->input('requisiciones', []);

        //Valida si la requisiciones se debe de procesar como urgencia para solo afectar esas
        if ($req->action === 'Urgencia') {
            // Recorrer cada requisición enviada en el formulario.
            foreach ($articulosSeleccionados as $idRequisicion => $data) {
                // Comprobar si la requisición fue seleccionada.
                $status = array_key_exists('seleccionado', $data) ? 'Aprobado' : 'Solicitado';
                // usando el ID de la requisición y el nuevo estado.
                Requisiciones::where('id_requisicion', $idRequisicion)
                    ->update(['estado' => $status]);
            }

            //Procesa todas por medio de aprobar o rechazar
        } elseif ($req->action === 'Corte'){
            // Recorrer cada requisición enviada en el formulario.
            foreach ($articulosSeleccionados as $idRequisicion => $data) {
                // Comprobar si la requisición fue seleccionada.
                $status = array_key_exists('seleccionado', $data) ? 'Aprobado' : 'Rechazado';

                // Actualizar el estatus de la requisición en la base de datos.
                Requisiciones::where('id_requisicion', $idRequisicion)
                    ->update(['estado' => $status]);
            }
        }
        // Redirigir al usuario de regreso a la página anterior con un mensaje de éxito.
        return redirect('solicitud/Compras')->with('corte','corte');
    }

    public function editarArti($id){
        // Busca todos los artículos vinculados a la ID de la requisición proporcionada
        $articulos = Articulos::where('requisicion_id',$id)->get();

        // Retorna la vista para la aprobación de la solicitud, pasando los artículos recuperados
        return view('Compras.editarSolicitud',compact('articulos'));
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
    public function rechazaArt($id,$rid){
        // Elimina el artículo específico por su ID
        Articulos::where('id',$id)->delete();

        //Valida el numero de articulos que se han registrado al, hacer una requisición
        $N_articulos = Articulos::where('requisicion_id',$rid)->count();

        //Valida si la cantidad de articulos registrados al editar una requisición sea 0
        if($N_articulos == 0){
            //Si no hay ningun articulo automaticamente se cambia el estatus a incompleta.
            Requisiciones::where('id_requisicion',$rid)->update([
                "estado"=>"Rechazado",
                "updated_at"=>Carbon::now(),
            ]);
        }

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
        $datos = Requisiciones::select('requisiciones.id_requisicion','requisiciones.unidad_id','requisiciones.mantenimiento as mant','requisiciones.created_at','requisiciones.pdf','requisiciones.notas','requisiciones.urgencia','requisiciones.fecha_programada','requisiciones.usuario_id','users.nombres','users.apellidoP','users.apellidoM','users.rol','users.departamento')
        ->join('users','requisiciones.usuario_id','=','users.id')
        ->where('requisiciones.id_requisicion',$rid)
        ->first();

        // Procesamiento de los datos de la solicitud y del empleado
        if (!empty($datos->urgencia)) {
            $urgencia = 'Requisición urgente';
            try {
                // Le da formato dd/mm/aaaa a la fecha creacion de la requisición
                $fechaProgramada = date('d/m/Y', strtotime($datos->fecha_programada));
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
        return redirect('corte/Compras')->with('editado','editado');
    }

    /*
      TODO: Valida una solicitud específica cambiando su estado a "Validado" y registra la acción en un log.

      Este método se encarga de actualizar el estado de una solicitud específica, identificada por su ID, a "Validado" en
      la base de datos, indicando que la solicitud ha pasado por un proceso de verificación o aprobación según los criterios
      establecidos. Además, se registra esta acción en un log, incluyendo el ID del usuario que realizó la validación,
      el ID de la solicitud, el nombre de la tabla afectada, y la acción realizada.

      @param  int  $id  El ID de la solicitud que se va a validar.

      Redirige al usuario a la página anterior con una sesión flash que indica que la solicitud ha sido validada exitosamente.
    */
    public function validarSoli($id){
        // Actualiza el estado de la solicitud específica a "Validado"
        Requisiciones::where('id_solicitud', $id)->update([
            "estado" => "Validado",
            "updated_at" => Carbon::now()
        ]);

        //Recarga la página con la un mensaje de validacion correcta
        return back()->with('validado','validado');
    }

    /*
      TODO: Carga la vista para la creación de nuevas cotizaciones asociadas a una solicitud específica.

      Este método recupera todas las cotizaciones activas (estatus '1') asociadas a una solicitud específica,
      identificada por su ID, incluyendo los archivos PDF tanto de la solicitud original como de las cotizaciones
      existentes.

      @param  int  $id El ID de la solicitud para la cual se crearán nuevas cotizaciones.

      Retorna la vista 'Compras.crearCotizacion', pasando las cotizaciones existentes y el ID de la solicitud para su visualización.
    */
    public function createCotiza($id){
        // Recupera las cotizaciones activas asociadas a la solicitud específica
        $cotizaciones = Cotizaciones::select('cotizaciones.id_cotizacion','requisiciones.id_requisicion','requisiciones.pdf as reqPDF','cotizaciones.pdf as cotPDF')
        ->join('requisiciones','cotizaciones.requisicion_id', '=', 'requisiciones.id_requisicion')
        ->where('cotizaciones.requisicion_id', $id)->where('cotizaciones.estatus','1')->get();

        // Carga y muestra la vista con los datos de las cotizaciones existentes
        return view('Compras.crearCotizacion',compact('cotizaciones','id'));
    }

    /*
      TODO: Procesa y almacena una nueva cotización para una requisición específica.

      Este método es responsable de manejar el archivo de cotización enviado a través de un formulario. Verifica que se haya
      subido un archivo válido y, de ser así, procede a almacenar el archivo en el sistema de archivos y a crear un nuevo registro
      de cotización en la base de datos con la información correspondiente, incluyendo la ruta al archivo PDF, el ID de la
      requisición asociada y el ID del usuario que realiza la cotización. Además, actualiza el estado de la requisición a
      "Cotizado" y registra la acción en un log para mantener un historial. Si no se selecciona ningún archivo o si hay un problema
      con el archivo enviado, se redirige al usuario a la página anterior con un mensaje de error.

      Redirige al usuario a la página anterior con un mensaje de éxito o error, dependiendo del resultado de la operación.
    */
    public function insertCotiza(Request $req){
        // Verifica que se haya subido un archivo y que sea válido
        if ($req->hasFile('archivo') && $req->file('archivo')->isValid()){

            // Procesamiento y almacenamiento del archivo
            $archivo = $req->file('archivo');
            $nombreArchivo = uniqid() . '.' . $archivo->getClientOriginalExtension();

            $archivo->storeAs('archivos', $nombreArchivo, 'public');
            $archivo_pdf = 'archivos/' . $nombreArchivo;

            $cotiza = Cotizaciones::where('requisicion_id',$req->requisicion)->first();

            if (empty($cotiza)){
                // Creación del registro de cotización en la base de datos
                Cotizaciones::create([
                    "requisicion_id"=>$req->input('requisicion'),
                    "usuario_id"=>session('loginId'),
                    "pdf"=>$archivo_pdf,
                    "estatus"=>"1",
                    "created_at"=>Carbon::now(),
                    "updated_at"=>Carbon::now()
                ]);

                // Actualización del estado de la requisición asociada
                Requisiciones::where('id_requisicion',$req->input('requisicion'))->update([
                    "estado" => "Cotizado",
                    "updated_at" => Carbon::now()
                ]);

                // Registro de la acción en un log
                Logs::create([
                    "user_id"=>session('loginId'),
                    "requisicion_id"=>$req->input('requisicion'),
                    "table_name"=>"Solicitudes",
                    "action"=>"Se ha hecho una cotizacion en la solicitud:".$req->input('solicitud'),
                    "created_at"=>Carbon::now(),
                    "updated_at"=>Carbon::now()
                ]);

                //Recarga la página con una alerta de exito
                return back()->with('cotizacion','cotizacion');

            } else {
                Cotizaciones::where('id_cotizacion',$cotiza->id_cotizacion)->update([
                    // Creación del registro de cotización en la base de datos
                    "requisicion_id"=>$req->input('requisicion'),
                    "usuario_id"=>session('loginId'),
                    "pdf"=>$archivo_pdf,
                    "estatus"=>"1",
                    "updated_at"=>Carbon::now()
                ]);

                // Actualización del estado de la requisición asociada
                Requisiciones::where('id_requisicion',$req->input('requisicion'))->update([
                    "estado" => "Cotizado",
                    "updated_at" => Carbon::now()
                ]);

                // Registro de la acción en un log
                Logs::create([
                    "user_id"=>session('loginId'),
                    "requisicion_id"=>$req->input('requisicion'),
                    "table_name"=>"Solicitudes",
                    "action"=>"Se ha hecho una cotizacion en la solicitud:".$req->input('solicitud'),
                    "created_at"=>Carbon::now(),
                    "updated_at"=>Carbon::now()
                ]);

                //Recarga la página con una alerta de exito
                return back()->with('actualizacion','actualizacion');
            }
        } else {
            // Manejo del caso en que no se sube un archivo válido
            return back()->with('error', 'No se ha seleccionado ningún archivo.');
        }
    }

    /*
      TODO: Elimina una cotización específica de la base de datos.

      Este método permite a los usuarios con los permisos adecuados eliminar una cotización específica,
      identificada por su ID, de la base de datos.

      @param  int  $id  El ID de la cotización que se va a eliminar.

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
      TODO: Recupera y muestra todos los proveedores activos en la base de datos.

      Este método consulta la base de datos para obtener un listado de todos los proveedores que están marcados como activos
      mediante el estatus '1'. La intención es proporcionar a los administradores y usuarios autorizados una visión general
      de los proveedores disponibles para gestiones de compras, contrataciones o cualquier otro tipo de interacción comercial.

      Retorna la vista 'Compras.proveedores', pasando el listado de proveedores activos para su visualización.
    */
    public function tableProveedor(){
        // Recupera todos los proveedores activos (estatus = 1)
        $proveedores = Proveedores::where('estatus',1)->get();

        // Carga y muestra la vista con el listado de proveedores activos
        return view('Compras.proveedores',compact('proveedores'));
    }

    /*
      TODO: Muestra la vista para la creación de un nuevo proveedor.

      Este método se encarga de cargar y presentar la vista que contiene el formulario utilizado para la creación
      de nuevos proveedores dentro del sistema. La vista proporcionará los campos necesarios para capturar la información
      esencial del nuevo proveedor, como su nombre, teléfono, correo electrónico, dirección, y cualquier otro dato relevante
      según los requisitos específicos de la aplicación. Este método facilita la tarea de administración de proveedores,
      permitiendo a los administradores o usuarios con los permisos adecuados añadir nuevos proveedores al sistema de manera
      sencilla y eficiente.

      Retorna la vista 'Compras.crearProveedor', que contiene el formulario para la creación de un nuevo proveedor.
    */
    public function createProveedor(){
        // Cargar y mostrar la vista con el formulario de creación de proveedor
        return view('Compras.crearProveedor');
    }

    /*
      TODO:Inserta un nuevo proveedor en la base de datos, incluyendo la validación y almacenamiento de archivos PDF.

      Este método recibe datos de un formulario que incluyen información básica del proveedor y archivos PDF
      obligatorios y opcionales (CIF y estado de cuenta bancario). Realiza la validación de los archivos para asegurar
      que se han proporcionado y que son del formato correcto. Luego, almacena los archivos en el sistema de archivos y
      crea un nuevo registro de proveedor en la base de datos con la información proporcionada y las rutas a los archivos
      almacenados. Si se proporcionan detalles bancarios, también se valida y almacena un archivo de estado de cuenta.

      Redirige al usuario a la lista de proveedores con una sesión flash que indica que el nuevo proveedor ha sido insertado exitosamente.
    */
    public function insertProveedor(Request $req){

        if (!empty($req->file('archivo_CIF')) && !empty($req->file('archivo_estadoCuenta'))){

            //Procesamiento y almacenamiento de los archivo CIF
            $nombreEmpresa = str_replace(' ', '', $req->nombre); // Elimina todos los espacios en blanco
            $archivo = $req->file('archivo_CIF');
            $nombreArchivo = 'CIF_' . $nombreEmpresa . '.' . $archivo->getClientOriginalExtension();

            $archivo->storeAs('CIF', $nombreArchivo, 'public');
            $CIF_pdf = 'CIF/' . $nombreArchivo;

            //Procesamiento y almacenamiento del archivo estado de cuenta
            $archivo = $req->file('archivo_estadoCuenta');
            $nombreArchivo = 'estadoCuenta_' . $nombreEmpresa . '.' . $archivo->getClientOriginalExtension();
            $archivo->storeAs('Estado Cuenta', $nombreArchivo, 'public');
            $estadoCuenta_pdf = 'Estado Cuenta/' . $nombreArchivo;

            // Creación del registro de proveedor en la base de datos
            Proveedores::create([
                "nombre"=>$req->input('nombre'),
                "regimen_fiscal"=>$req->input('regimen'),
                "sobrenombre"=>$req->input('sobrenombre'),
                "telefono"=>$req->input('telefono'),
                "telefono2"=>$req->input('telefono2'),
                "contacto"=>$req->input('contacto'),
                "direccion"=>$req->input('direccion'),
                "domicilio"=>$req->input('domicilio'),
                "rfc"=>$req->input('rfc'),
                "correo"=>$req->input('correo'),
                "CIF"=>$CIF_pdf,
                "banco"=>$req->input('banco'),
                "n_cuenta"=>$req->input('n_cuenta'),
                "n_cuenta_clabe"=>$req->input('n_cuenta_clabe'),
                "estado_cuenta"=>"$estadoCuenta_pdf",
                "estatus"=>"1",
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);

        } elseif (!empty($req->file('archivo_CIF')) && empty($req->file('archivo_estadoCuenta'))){

            //Procesamiento y almacenamiento de los archivo CIF
            $nombreEmpresa = str_replace(' ', '', $req->nombre); // Elimina todos los espacios en blanco
            $archivo = $req->file('archivo_CIF');
            $nombreArchivo = 'CIF_' . $nombreEmpresa . '.' . $archivo->getClientOriginalExtension();

            $archivo->storeAs('CIF', $nombreArchivo, 'public');
            $CIF_pdf = 'CIF/' . $nombreArchivo;

            // Creación del registro de proveedor en la base de datos
            Proveedores::create([
                "nombre"=>$req->input('nombre'),
                "regimen_fiscal"=>$req->input('regimen'),
                "sobrenombre"=>$req->input('sobrenombre'),
                "telefono"=>$req->input('telefono'),
                "telefono2"=>$req->input('telefono2'),
                "contacto"=>$req->input('contacto'),
                "direccion"=>$req->input('direccion'),
                "domicilio"=>$req->input('domicilio'),
                "rfc"=>$req->input('rfc'),
                "correo"=>$req->input('correo'),
                "CIF"=>$CIF_pdf,
                "banco"=>$req->input('banco'),
                "n_cuenta"=>$req->input('n_cuenta'),
                "n_cuenta_clabe"=>$req->input('n_cuenta_clabe'),
                "estado_cuenta"=>null,
                "estatus"=>"1",
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);
        } elseif (!empty($req->file('archivo_estadoCuenta')) && empty($req->file('archivo_CIF'))){

            //Procesamiento y almacenamiento del archivo estado de cuenta
            $archivo = $req->file('archivo_estadoCuenta');
            $nombreArchivo = 'estadoCuenta_' . $nombreEmpresa . '.' . $archivo->getClientOriginalExtension();
            $archivo->storeAs('Estado Cuenta', $nombreArchivo, 'public');
            $estadoCuenta_pdf = 'Estado Cuenta/' . $nombreArchivo;

            // Creación del registro de proveedor en la base de datos
            Proveedores::create([
                "nombre"=>$req->input('nombre'),
                "regimen_fiscal"=>$req->input('regimen'),
                "sobrenombre"=>$req->input('Sobrenombre'),
                "telefono"=>$req->input('telefono'),
                "telefono2"=>$req->input('telefono2'),
                "contacto"=>$req->input('contacto'),
                "direccion"=>$req->input('direccion'),
                "domicilio"=>$req->input('domicilio'),
                "rfc"=>$req->input('rfc'),
                "correo"=>$req->input('correo'),
                "CIF"=>$CIF_pdf,
                "banco"=>$req->input('banco'),
                "n_cuenta"=>$req->input('n_cuenta'),
                "n_cuenta_clabe"=>$req->input('n_cuenta_clabe'),
                "estado_cuenta"=>null,
                "estatus"=>"1",
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);
        } else {
            // Creación del registro de proveedor en la base de datos
            Proveedores::create([
                "nombre"=>$req->input('nombre'),
                "regimen_fiscal"=>$req->input('regimen'),
                "sobrenombre"=>$req->input('Sobrenombre'),
                "telefono"=>$req->input('telefono'),
                "telefono2"=>$req->input('telefono2'),
                "contacto"=>$req->input('contacto'),
                "direccion"=>$req->input('direccion'),
                "domicilio"=>$req->input('domicilio'),
                "rfc"=>$req->input('rfc'),
                "correo"=>$req->input('correo'),
                "CIF"=>null,
                "banco"=>$req->input('banco'),
                "n_cuenta"=>$req->input('n_cuenta'),
                "n_cuenta_clabe"=>$req->input('n_cuenta_clabe'),
                "estado_cuenta"=>null,
                "estatus"=>"1",
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);
        }

        // Redirección con mensaje de éxito
        return redirect('proveedores/Compras')->with('insert','insert');
    }

    /*
      TODO: Muestra la vista para editar los detalles de un proveedor específico.

      Este método se encarga de recuperar los detalles de un proveedor específico, identificado por su ID, de la base de datos.
      La recuperación de esta información es crucial para pre-rellenar el formulario de edición en la vista con los datos actuales
      del proveedor, permitiendo así que los administradores o los usuarios con los permisos adecuados realicen cambios en la información
      del proveedor como nombre, contacto, dirección, datos bancarios, y cualquier otro dato relevante.

      @param  int  $id  El ID del proveedor cuyos detalles se van a editar.

      Retorna la vista 'Compras.editarProveedor', pasando los detalles del proveedor específico para su edición.
    */
    public function editProveedor($id){
        // Recupera los detalles del proveedor específico por su ID
        $proveedor = Proveedores::where('id_proveedor',$id)->first();

        // Carga y muestra la vista con el formulario de edición de proveedor, pasando los detalles del proveedor
        return view('Compras.editarProveedor',compact('proveedor'));
    }

    /*
      TODO: Actualiza los detalles de un proveedor existente y gestiona la actualización de sus documentos.

      Este método permite actualizar la información básica de un proveedor, como el nombre, contacto, dirección, RFC,
      y detalles bancarios. Además, maneja la validación, actualización y almacenamiento de documentos críticos como el
      CIF y el estado de cuenta bancario. Si hay cambios en la información bancaria o se suben nuevos documentos, el sistema
      valida y almacena estos archivos, reemplazando los anteriores si existen.

      @param  int  $id  El ID del proveedor que se va a actualizar.

      Redirige al usuario a la lista de proveedores con una sesión flash que indica que el proveedor ha sido actualizado exitosamente.
    */
    public function updateProveedor(Request $req,$id){

        if (!empty($req->file('archivo_CIF')) && !empty($req->file('archivo_estadoCuenta'))){

            //Procesamiento y almacenamiento de los archivo CIF
            $nombreEmpresa = str_replace(' ', '', $req->nombre); // Elimina todos los espacios en blanco
            $archivo = $req->file('archivo_CIF');
            $nombreArchivo = 'CIF_' . $nombreEmpresa . '.' . $archivo->getClientOriginalExtension();

            $archivo->storeAs('CIF', $nombreArchivo, 'public');
            $CIF_pdf = 'CIF/' . $nombreArchivo;

            //Procesamiento y almacenamiento del archivo estado de cuenta
            $archivo = $req->file('archivo_estadoCuenta');
            $nombreArchivo = 'estadoCuenta_' . $nombreEmpresa . '.' . $archivo->getClientOriginalExtension();
            $archivo->storeAs('Estado Cuenta', $nombreArchivo, 'public');
            $estadoCuenta_pdf = 'Estado Cuenta/' . $nombreArchivo;

            //Insertar el archivo CIF y modificaciones junto con los datos bancarios modificados
            Proveedores::where('id_proveedor',$id)->update([
                "nombre"=>$req->input('nombre'),
                "regimen_fiscal"=>$req->input('regimen'),
                "sobrenombre"=>$req->input('sobrenombre'),
                "telefono"=>$req->input('telefono'),
                "telefono2"=>$req->input('telefono2'),
                "contacto"=>$req->input('contacto'),
                "direccion"=>$req->input('direccion'),
                "domicilio"=>$req->input('domicilio'),
                "rfc"=>$req->input('rfc'),
                "correo"=>$req->input('correo'),
                "CIF"=>$CIF_pdf,
                "banco"=>$req->input('banco'),
                "n_cuenta"=>$req->input('n_cuenta'),
                "n_cuenta_clabe"=>$req->input('n_cuenta_clabe'),
                "estado_cuenta"=>$estadoCuenta_pdf
            ]);

        } elseif (!empty($req->file('archivo_CIF')) && empty($req->file('archivo_estadoCuenta'))){

            //Procesamiento y almacenamiento de los archivo CIF
            $nombreEmpresa = str_replace(' ', '', $req->nombre); // Elimina todos los espacios en blanco
            $archivo = $req->file('archivo_CIF');
            $nombreArchivo = 'CIF_' . $nombreEmpresa . '.' . $archivo->getClientOriginalExtension();

            $archivo->storeAs('CIF', $nombreArchivo, 'public');
            $CIF_pdf = 'CIF/' . $nombreArchivo;

            //Insertar el archivo CIF y datos actualizados
            Proveedores::where('id_proveedor',$id)->update([
                "nombre"=>$req->input('nombre'),
                "regimen_fiscal"=>$req->input('regimen'),
                "sobrenombre"=>$req->input('sobrenombre'),
                "telefono"=>$req->input('telefono'),
                "telefono2"=>$req->input('telefono2'),
                "contacto"=>$req->input('contacto'),
                "direccion"=>$req->input('direccion'),
                "domicilio"=>$req->input('domicilio'),
                "rfc"=>$req->input('rfc'),
                "correo"=>$req->input('correo'),
                "CIF"=>$CIF_pdf,
                "banco"=>$req->input('banco'),
                "n_cuenta"=>$req->input('n_cuenta'),
                "n_cuenta_clabe"=>$req->input('n_cuenta_clabe'),
            ]);
        } elseif (!empty($req->file('archivo_estadoCuenta')) && empty($req->file('archivo_CIF'))){

            //Procesamiento y almacenamiento del archivo estado de cuenta
            $archivo = $req->file('archivo_estadoCuenta');
            $nombreArchivo = 'estadoCuenta_' . $nombreEmpresa . '.' . $archivo->getClientOriginalExtension();
            $archivo->storeAs('Estado Cuenta', $nombreArchivo, 'public');
            $estadoCuenta_pdf = 'Estado Cuenta/' . $nombreArchivo;

            //Insertar el archivo CIF y modificaciones junto con los datos bancarios modificados
            Proveedores::where('id_proveedor',$id)->update([
                "nombre"=>$req->input('nombre'),
                "regimen_fiscal"=>$req->input('regimen'),
                "sobrenombre"=>$req->input('sobrenombre'),
                "telefono"=>$req->input('telefono'),
                "telefono2"=>$req->input('telefono2'),
                "contacto"=>$req->input('contacto'),
                "direccion"=>$req->input('direccion'),
                "domicilio"=>$req->input('domicilio'),
                "rfc"=>$req->input('rfc'),
                "correo"=>$req->input('correo'),
                "banco"=>$req->input('banco'),
                "n_cuenta"=>$req->input('n_cuenta'),
                "n_cuenta_clabe"=>$req->input('n_cuenta_clabe'),
                "estado_cuenta"=>$estadoCuenta_pdf
            ]);
        } else {
           //Insertar el archivo CIF y modificaciones junto con los datos bancarios modificados
           Proveedores::where('id_proveedor',$id)->update([
                "nombre"=>$req->input('nombre'),
                "regimen_fiscal"=>$req->input('regimen'),
                "sobrenombre"=>$req->input('sobrenombre'),
                "telefono"=>$req->input('telefono'),
                "telefono2"=>$req->input('telefono2'),
                "contacto"=>$req->input('contacto'),
                "direccion"=>$req->input('direccion'),
                "domicilio"=>$req->input('domicilio'),
                "rfc"=>$req->input('rfc'),
                "correo"=>$req->input('correo'),
                "banco"=>$req->input('banco'),
                "n_cuenta"=>$req->input('n_cuenta'),
                "n_cuenta_clabe"=>$req->input('n_cuenta_clabe'),
            ]);
        }

        //Redirecciona al listado de proveedores con mensaje de exito
        return redirect('proveedores/Compras')->with('update','update');
    }

    /*
      TODO: Desactiva un proveedor específico marcándolo como inactivo en la base de datos.

      En lugar de eliminar el registro del proveedor, este método actualiza el campo 'estatus' a 0,
      indicando que el proveedor está inactivo. Esta operación es crucial para mantener la integridad de los datos
      y permite la recuperación del registro en el futuro si es necesario. Además, se actualiza el campo 'updated_at'
      para reflejar el momento de la desactivación.

      @param  int  $id  El ID del proveedor que se va a desactivar.

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

    public function actualizarProveedores (Request $req){

        ini_set('memory_limit','512M');

        // Validar que se haya subido un archivo
        $req->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Obtener el archivo subido
        $file = $req->file('file');

        // Cargar el archivo Excel
        $spreadsheet = IOFactory::load($file);

        // Obtener la primera hoja del archivo
        $sheet = $spreadsheet->getActiveSheet();

        // Obtener el número total de filas y columnas
        $highestRow = $sheet->getHighestDataRow();
        $highestColumn = $sheet->getHighestDataColumn();

        // Iterar sobre las filas y leer los datos
        for ($row = 4; $row <= $highestRow; $row++) {
            // Crear el array con los datos del registro actual
            $registro = [
                'id_proveedor' => $sheet->getCell('A' . $row)->getValue(),
                'nombre' => strtoupper($sheet->getCell('B' . $row)->getValue()),
                'regimen_fiscal' => strtoupper($sheet->getCell('C' . $row)->getValue()),
                'sobrenombre'=> strtoupper($sheet->getCell('D' . $row)->getValue()),
                'telefono' => $sheet->getCell('E' . $row)->getValue(),
                'telefono2' => $sheet->getCell('F' . $row)->getValue(),
                'contacto' => strtoupper($sheet->getCell('G' . $row)->getValue()),
                'direccion' => strtoupper($sheet->getCell('H' . $row)->getValue()),
                'domicilio' => strtoupper($sheet->getCell('I' . $row)->getValue()),
                'rfc' => strtoupper($sheet->getCell('J' . $row)->getValue()),
                'correo' => strtoupper($sheet->getCell('K' . $row)->getValue()),
                'banco' => strtoupper($sheet->getCell('M' . $row)->getValue()),
                'n_cuenta' => $sheet->getCell('N' . $row)->getValue(),
                'n_cuenta_clabe' => $sheet->getCell('O' . $row)->getValue(),
            ];

            // Verificar si el proveedor existe en la base de datos
            $proveedor = proveedores::where('id_proveedor', $registro['id_proveedor'])->first();

            if ($proveedor) {
                // Actualizar el registro existente
                proveedores::where('id_proveedor', $registro['id_proveedor'])->update([
                    "nombre" => $registro['nombre'],
                    "regimen_fiscal" => $registro['regimen_fiscal'],
                    "sobrenombre" => $registro['sobrenombre'],
                    "telefono" => $registro['telefono'],
                    "telefono2" => $registro['telefono2'],
                    "contacto" => $registro['contacto'],
                    "direccion" => $registro['direccion'],
                    "domicilio" => $registro['domicilio'],
                    "rfc" => $registro['rfc'],
                    "correo" => $registro['correo'],
                    "banco" => $registro['banco'],
                    "n_cuenta" => $registro['n_cuenta'],
                    "n_cuenta_clabe" => $registro['n_cuenta_clabe'],
                ]);
            } else {
                // Crear un nuevo registro
                proveedores::create($registro);
            }
        }

        // Redirigir al usuario a la página anterior con una notificación de éxito
        return back()->with('importado', 'importado');
    }

    /*
      TODO: Prepara y muestra la información necesaria para generar una orden de compra.

      Este método recupera la cotización seleccionada para una requisición específica, identificada por su ID,
      incluyendo los documentos PDF asociados tanto a la cotización como a la requisición. También recopila los
      artículos asociados a la requisición que están pendientes (estatus 0) y un listado de proveedores activos
      (estatus 1), facilitando la xselección de un proveedor en el proceso de creación de la orden de compra.

      @param  int  $id  El ID de la requisición para la cual se preparará la orden de compra.

      Retorna la vista 'Compras.ordenCompra', pasando la cotización seleccionada, los proveedores activos, el ID de la requisición,
      y los artículos pendientes para su visualización y gestión.
    */
    public function createOrdenCompra($id){
        // Recuperación de la cotización seleccionada
        $cotizacion = Cotizaciones::select('cotizaciones.id_cotizacion','cotizaciones.pdf as cotPDF','requisiciones.pdf as reqPDF')
        ->join('requisiciones','cotizaciones.requisicion_id','=', 'requisiciones.id_requisicion')
        ->where('cotizaciones.estatus',1)
        ->where('requisiciones.id_requisicion',$id)
        ->first();

        //Recuperacion de los articulos pertenecientes a la requisicion señalada
        $articulos = Articulos::where('requisicion_id',$id)
        ->where('estatus',0)
        ->get();

        //Recuperacion del listado de proveedores actuales
        $proveedores = Proveedores::
        where('estatus',1)->orderBy('nombre','asc')->get();

        // Carga y muestra la vista con la información recopilada
        return view('Compras.ordenCompra',compact('cotizacion','proveedores','id','articulos'));
    }

    /*
      TODO: Crea una orden de compra basada en una cotización específica y actualiza el estado de los artículos y la requisición correspondiente.

      Este método recoge información desde un formulario que incluye notas, proveedor seleccionado, artículos, y condiciones de pago.
      Según las condiciones de pago (crédito o pago inmediato), ajusta los detalles de la orden. La información del empleado que realiza
      la operación y otros datos relevantes se serializan y se almacenan. También se maneja la creación de un documento PDF que
      resume la orden de compra y la actualización de la base de datos para reflejar los nuevos estados de los artículos y de la
      requisición. Si todos los artículos de la requisición están gestionados, se actualiza su estado a 'Finalizado'.

      @param int $cid El ID de la cotización asociada a la orden de compra.
      @param int $rid El ID de la requisición asociada a los artículos y la orden de compra.

      Redirige al usuario a la lista de órdenes de compra con un mensaje de confirmación de que la operación fue exitosa.
    */
    public function insertOrdenCom(Request $req, $cid,$rid){
            //Variables a utilizar en pdf
            $Nota = $req->input('Notas');
            $proveedor = $req->input('Proveedor');
            $articulos = $req->input('articulos');

            $descuento = $req->input('descuento');

            $condiciones = $req->input('condiciones');

            //Si se condiciona a credito guarda el valor de los días acordados
            if($condiciones === "Credito"){
                $dias = $req->input('dias');
            } else{
                //si no, pasa la variable con pago inmediato
                $dias = "Pago inmediato";
            }

            //Guarda en un arreglo los datos de la sesion activa
            $datosEmpleado[] = [
                'idEmpleado' => session('loginId'),
                'nombres' => session('loginNombres'),
                'apellidoP' => session('loginApepat'),
                'apellidoM' => session('loginApemat'),
                'rol' => session('rol'),
                'dpto' =>session('departamento')
            ];

            //Obtiene el id que le corresponde a la orden de compra
            $OrdenCompra = Orden_Compras::select('id_orden')->latest('id_orden')->first();

            //Y en caso de no haber registros, asigna automaticamente el valor 1
            if (empty($OrdenCompra)){
                $idnuevaorden = 1;
            } else{
                //Si existen ordenes de compra, busca la ultima y le suma 1 para evitar saltar numeros
                $idnuevaorden = $OrdenCompra->id_orden + 1;
            }

            //Obtiene la unidad en caso de ser para mantenimiento
            $datos = Requisiciones::select('unidad_id','mantenimiento')->where('id_requisicion',$rid)->first();

            $mantenimiento = $datos->mantenimiento;

            //Si existe unidad guarda todos sus datos para mostrarlos en pdf
            if(!empty($datos->unidad_id)){
                $unidad = Unidades::where('id_unidad',$datos->unidad_id)->first();
            }

            $articulosSeleccionados = $req->input('articulos_seleccionados');
            $articulosFiltrados = [];

            // Asegúrate de que $articulos tiene ids únicos antes de proceder
            foreach ($articulos as $articulo) {
                if (in_array($articulo['id'], $articulosSeleccionados)) {
                    // Solo agrega artículos seleccionados y evita duplicados
                    $articulosFiltrados[$articulo['id']] = $articulo; // Usar id como clave para evitar duplicados
                }
            }

            // Serializar los datos del empleado y almacenarlos en un archivo
            $datosSerializados = serialize($datosEmpleado);
            $rutaArchivo = storage_path('app/datos_empleados.txt');
            file_put_contents($rutaArchivo, $datosSerializados);

            // Nombre y ruta del archivo en laravel
            $numeroUnico = time(); // Genera un timestamp único
            $nombreArchivo = 'ordenCompra_' . $idnuevaorden . '.pdf';
            $rutaDescargas = 'ordenesCompra/' . $nombreArchivo;

            $datosProveedor = Proveedores::where('id_proveedor',$proveedor)->first();

            // Incluir el archivo Requisicion.php y pasar la ruta del archivo como una variable
            ob_start(); // Iniciar el búfer de salida
            include(public_path('/pdf/TCPDF-main/examples/orden_compra.php'));
            ob_end_clean();
            $totalGastos = 0;

            // Itera sobre el array y suma los valores de la columna 'precio'
            foreach ($articulosFiltrados as $subarray) {
                // Verifica si la clave 'precio' existe en el subarray antes de sumar
                if (isset($subarray['precio_unitario'])) {
                    $totalGastos += $subarray['precio_unitario']*$subarray['cantidad'];
                }
            }

            //Crea una orden de compra en la base de datos basandose en las variables obtenidas.
            Orden_compras::create([
                "id_orden"=>$idnuevaorden,
                "admin_id"=>session('loginId'),
                "cotizacion_id" => $cid,
                "proveedor_id"=>$req->input('Proveedor'),
                "costo_total"=>$totalGastos,
                "pdf" => $rutaDescargas,
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now(),
            ]);

            // Almacenar los estatus de actualización
            $estatusActualizaciones = [];

            // Recorrer los articulos filtrados usando un bucle for
            $articulosCount = count($articulosFiltrados);
            $articulosArray = array_values($articulosFiltrados); // Convierte el array asociativo a un array indexado

            for ($i = 0; $i < $articulosCount; $i++) {
                // Aquí puedes acceder a cada elemento del array $articulo
                $articuloUnico = Articulos::where('id', $articulosArray[$i]['id'])->first();
                if (!empty($articuloUnico)) {
                    // Calcula la cantidad total restando la cantidad comprada de la cantidad actual
                    $cantidad_total = $articuloUnico->cantidad - $articulosArray[$i]['cantidad'];                    

                    //Determina el estatus antes de la actualización
                    $estatus = $cantidad_total == 0 ? 1 : 0;
                    Articulos::where('id', $articulosArray[$i]['id'])->update([
                        'cantidad' => $cantidad_total,
                        'unidad' => $articulosArray[$i]['unidad'],
                        'descripcion' => $articulosArray[$i]['descripcion'],
                        'precio_unitario' => $articulosArray[$i]['precio_unitario'],
                        'ult_compra' => $articulosArray[$i]['cantidad'],
                        'estatus' => $estatus, // Usa la variable $estatus aquí
                        'orden_id' => $idnuevaorden
                    ]);
                }                
            }

            //Conteo de articulos que no se han generado orden de compra
            $totalArticulos = Articulos::where('requisicion_id',$rid)
            ->where('estatus',0)
            ->count();

            //Valida si todos los articulos han sido comprados
            if ($totalArticulos == 0){
                //Si es así, finaliza la requisicion
                Requisiciones::where('id_requisicion',$rid)->update([
                    "estado"=>"Finalizado",
                    "updated_at"=>Carbon::now(),
                ]);
            } else{
                //Si no, cambia el estatus a comprado para que siga mostrando a compras
                Requisiciones::where('id_requisicion',$rid)->update([
                    "estado"=>"Comprado",
                    "updated_at"=>Carbon::now(),
                ]);
            }

            //Elimina los datos de la orden de compra
            session()->forget('datosOrden');

            //Redirecciona a la lista de ordenes de compras con un mensaje de éxito
            return redirect('ordenesCompras')->with('orden','orden');
        }

    /*
      TODO: Recupera y muestra una lista de todas las órdenes de compra activas.

      Este método consulta la base de datos para obtener información detallada sobre cada orden de compra, incluyendo
      el ID de la orden, detalles asociados de la requisición, el estado de la requisición, los nombres de los administradores
      que gestionaron las órdenes, archivos PDF relacionados con las cotizaciones y las órdenes, detalles del proveedor,
      costo total, estado del pago y otros datos relevantes. Filtra cualquier orden relacionada con requisiciones que hayan
      sido rechazadas, centrándose en aquellas que están activas o en proceso.

      Devuelve la vista 'Compras.ordenesCompras', pasando la lista de órdenes para su visualización.
    */
    public function tableOrdenesCompras(){
        /* Obtención de información detallada para cada orden en donde se analizan la evolucion de la peticiónn
           desde requisición hasta orden de compra*/
        $ordenes = Orden_compras::select('orden_compras.id_orden','requisiciones.id_requisicion','requisiciones.estado','requisiciones.pdf as reqPDF','users.nombres','cotizaciones.pdf as cotPDF','proveedores.nombre as proveedor','orden_compras.costo_total','orden_compras.estado as estadoComp','orden_compras.pdf as ordPDF','orden_compras.comprobante_pago','orden_compras.estado' ,'orden_compras.created_at')
        ->join('users','orden_compras.admin_id','=','users.id')
        ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
        ->join('proveedores','orden_compras.proveedor_id','=','proveedores.id_proveedor')
        //Se excluyen las que se encuentren rechazadas.
        ->where('requisiciones.estado','!=','Rechazado')
        ->orderBy('orden_compras.created_at','desc')
        ->get();

        // Formatear fechas
        $ordenes->transform(function ($orden) {
            $orden->fecha_creacion = Carbon::parse($orden->created_at)->format('d/m/Y');
            return $orden;
        });

        //Muentra la vista con la variable que contiene las ordenes de compra
        return view ('Compras.ordenesCompras',compact('ordenes'));
    }

    /*
      TODO: Elimina una orden de compra específica y revierte los cambios en los artículos y la requisición asociados.

      Este método se encarga de gestionar la eliminación de una orden de compra específica, identificada por su ID.
      Además de eliminar la orden, el método recalcula y restaura las cantidades de los artículos que fueron parte
      de la orden a sus valores previos a la compra. Esto se logra sumando las últimas cantidades compradas a las
      actuales cantidades de los artículos. También limpia los valores de 'precio_unitario' y 'orden_id', y
      establece el 'estatus' de los artículos a 0 (disponible). Posteriormente, actualiza el estado de la requisición
      asociada a 'Validado', lo que permite que la requisición pueda ser procesada nuevamente en el futuro.

      @param int $id El ID de la orden de compra que se va a eliminar.
      @param int $sid El ID de la requisición asociada a la orden de compra.

      Redirige al usuario a la página anterior con una sesión flash que indica que la orden ha sido eliminada exitosamente.
    */
    public function deleteOrd($id,$sid){
        // Recuperar los artículos asociados a la orden de compra
        $articulos_ord = Articulos::where('orden_id', $id)->get();

        foreach ($articulos_ord as $articulo) {
            // Restaurar la cantidad del artículo al estado anterior a la compra
            $anterior_cantidad = $articulo->cantidad + $articulo->ult_compra;

            // Actualizas solo este artículo específico usando su ID único
            Articulos::where('id', $articulo->id)->update([
                "cantidad" => $anterior_cantidad,
                "precio_unitario" => null,
                "estatus" => 0,
                "orden_id" => null,
            ]);
        }

        $datos = Orden_compras::where('id_orden',$id)->first();

        //Guarda la ruta del archivo PDF de la requisicion
        $fileToDelete = public_path($datos->pdf);

        //Si existe el archivo lo elimina
        if (file_exists($fileToDelete)) {
            unlink($fileToDelete);
        }
        // Eliminar la orden de compra
        Orden_compras::where('id_orden',$id)->delete();

        // Actualizar el estado de la requisición asociada
        Requisiciones::where('id_requisicion',$sid)->update([
            "estado"=>"Validado",
            "updated_at"=>Carbon::now()
        ]);

        // Redireccionar al usuario con un mensaje de confirmación
        return back()->with('eliminada','eliminada');
    }

    /*
      TODO: Finaliza una requisición específica actualizando su estado a "Finalizado".

      Este método se encarga de marcar una requisición, identificada por su ID, como finalizada. Actualiza el estado
      de la requisición a "Finalizado" en la base de datos.  La actualización también incluye un registro de la fecha
      y hora en que se realizó, utilizando el campo `updated_at` para asegurar una auditoría adecuada.

      @param int $id El ID de la requisición que se va a finalizar.

      Redirige al usuario a la página anterior con una sesión flash que indica que la requisición ha sido finalizada exitosamente.
    */
    public function FinalizarReq($id){
        // Actualiza el estado de la requisición a "Finalizado"
        Requisiciones::where('id_requisicion',$id)->update([
            "estado"=>"Finalizado",
            "updated_at"=>Carbon::now(),
        ]);

        // Redirige al usuario con un mensaje de confirmación
        return back()->with('finalizada','finalizada');
    }

    /*
      TODO: Recupera y muestra información detallada sobre todos los pagos fijos, junto con listados de servicios y proveedores activos.

      Este método consulta la base de datos para obtener un listado completo de todos los pagos fijos registrados en el sistema,
      incluyendo detalles como el ID del pago, comprobante de pago, y la información relacionada del servicio y del proveedor.
      Se realiza una unión con las tablas de servicios y proveedores para enriquecer los datos de cada pago con información
      relevante como el nombre del servicio y del proveedor. Además, se recuperan listados de todos los servicios y proveedores
      activos que se utilizan para filtros o selecciones en la interfaz de usuario.

      Devuelve la vista 'Compras.pagos', pasando los pagos fijos, servicios y proveedores para su visualización y gestión.
    */
    public function tablePagosFijos() {
        // Obtener los pagos fijos con detalles completos de servicios y proveedores
        $pagos = Pagos_Fijos::select('pagos_fijos.*','servicios.id_servicio','servicios.nombre_servicio','proveedores.nombre','pagos_fijos.comprobante_pago')
        ->join('servicios','pagos_fijos.servicio_id','servicios.id_servicio')
        ->join('proveedores','servicios.proveedor_id','proveedores.id_proveedor')
        ->orderBy('id_pago','desc')
        ->where('pagos_fijos.usuario_id',session('loginId'))
        ->get();

        // Obtener todos los servicios activos
        $servicios = Servicios::select('servicios.id_servicio','servicios.nombre_servicio','proveedores.id_proveedor','proveedores.nombre')
        ->join('proveedores','servicios.proveedor_id','=','proveedores.id_proveedor')
        ->orderBy('servicios.nombre_servicio','asc')
        ->where('servicios.estatus','1')
        ->get();

        // Obtener todos los proveedores activos
        $proveedores = Proveedores::where('estatus','1')
        ->orderBy('nombre','asc')
        ->get();

        // Cargar y mostrar la vista con los datos necesarios
        return view('Compras.pagos',compact('pagos','servicios','proveedores'));
    }

    public function crearOrdenPago(){
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
        return view('Compras.crearPago',compact('servicios','proveedores'));
    }

    /*
      TODO: Crea un nuevo servicio en la base de datos y redirige al usuario con un mensaje de éxito.
      Este método recibe una solicitud con los datos necesarios para crear un nuevo servicio, incluyendo el nombre del servicio,
      el proveedor asociado y el usuario que lo crea. Utiliza el modelo `Servicios` para insertar un nuevo registro en la base de datos,
      estableciendo el estatus del servicio como activo (1) y registrando las marcas de tiempo de creación y actualización.

      @param Request $req La solicitud que contiene los datos del nuevo servicio.

      Redirige al usuario a la página anterior con un mensaje de éxito indicando que el servicio ha sido creado correctamente.     
    */ 
    public function createServicio (Request $req){
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
        TODO: Actualiza un servicio existente en la base de datos con los nuevos datos proporcionados.
        Este método recibe una solicitud con los datos actualizados del servicio, incluyendo el nombre del servicio y el proveedor asociado.
        Utiliza el modelo `Servicios` para actualizar el registro correspondiente en la base de datos, estableciendo la fecha y hora de actualización
        mediante `Carbon::now()`. El ID del servicio a actualizar se pasa como parámetro en la URL.

        @param Request $req La solicitud que contiene los datos actualizados del servicio.
        @param int $id El ID del servicio que se va a actualizar.

        Redirige al usuario a la página anterior con un mensaje de éxito indicando que el servicio ha sido editado correctamente.
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
        TODO: Desactiva un servicio específico marcándolo como inactivo en la base de datos.
        Este método actualiza el registro del servicio especificado por su ID, estableciendo el campo 'estatus' a 0,
        lo que indica que el servicio está inactivo. También actualiza el campo 'updated_at' para reflejar la fecha y hora
        de la desactivación. Esta operación es importante para mantener la integridad de los datos y permite que el servicio
        pueda ser reactivado en el futuro si es necesario, sin eliminar el registro de la base de datos.

        @param int $id El ID del servicio que se va a desactivar.

        Redirige al usuario a la página anterior con un mensaje de éxito indicando que el servicio ha sido desactivado correctamente.
    */
    public function deleteServicioC($id){
        // Actualiza el registro del servicio específico para marcarlo como inactivo
        Servicios::where('id_servicio',$id)->update([
            "estatus"=>"0",
            "updated_at"=>Carbon::now(),
        ]);

        // Redirige al usuario a la página anterior con un mensaje de éxito
        return back()->with('servDelete','servDelete');
    }

    /*
        TODO: Crea un nuevo pago fijo en la base de datos y genera un PDF con los detalles del pago.
        Este método recibe una solicitud con los datos necesarios para crear un nuevo pago fijo, incluyendo el ID del servicio,
        las notas, el importe y otros detalles del empleado. Utiliza el modelo `Pagos_Fijos` para insertar un nuevo registro en la base de datos,
        estableciendo el estado del pago como 'Solicitado' y registrando las marcas de tiempo de creación y actualización.
        Además, genera un PDF con los detalles del pago utilizando la biblioteca TCPDF, guardando el archivo en una ruta específica.
        
        @param Request $req La solicitud que contiene los datos del nuevo pago fijo.
        
        Redirige al usuario a la página de pagos fijos con un mensaje de éxito.
    */
    public function createPago(Request $req){
        // Validación de los datos de entrada
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

        // Obtención de los detalles del servicio y proveedor
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

        // Crear un nuevo registro de pago fijo en la base de datos
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

        // Redirigir al usuario a la página de pagos fijos con un mensaje de éxito
        return redirect()->route('pagosFijos')->with('pago','pago');
    }

    /*
        TODO: Actualiza un pago fijo existente en la base de datos y genera un nuevo PDF con los detalles actualizados.
        Este método recibe una solicitud con los datos necesarios para actualizar un pago fijo, incluyendo el ID del servicio,
        las notas, el importe y otros detalles del empleado. Utiliza el modelo `Pagos_Fijos` para actualizar el registro correspondiente
        en la base de datos, estableciendo el estado del pago como 'Solicitado' y registrando las marcas de tiempo de actualización.
        Además, genera un nuevo PDF con los detalles actualizados del pago utilizando la biblioteca TCPDF, guardando el archivo en una ruta específica.

        @param Request $req La solicitud que contiene los datos del pago fijo a actualizar.
        @param int $id El ID del pago fijo que se va a actualizar.

        Redirige al usuario a la página de pagos fijos con un mensaje de éxito.
        
    */
    public function updatePago(Request $req, $id){
        // Validación de los datos de entrada
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

        // Obtiene el ID que le corresponde al pago fijo
        $idcorresponde = $id;
        $pdf = Pagos_Fijos::select('pdf')
        ->where('id_pago',$id)
        ->first();

        // Obtiene los detalles del servicio y proveedor
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

        // Actualizar el registro del pago fijo en la base de datos con los datos proporcionados
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

        // Redirigir al usuario a la página de pagos fijos con un mensaje de éxito
        return back()->with('editado','editado');
    }

    /*
        TODO: Elimina un pago fijo específico de la base de datos y elimina el archivo PDF asociado.
        Este método se encarga de eliminar un pago fijo específico, identificado por su ID. Primero, recupera el registro del pago
        y obtiene la ruta del archivo PDF asociado. Si el archivo existe, lo elimina del sistema de archivos. Luego, procede a
        eliminar el registro del pago fijo de la base de datos. Finalmente, redirige al usuario a la página anterior con un mensaje
        de confirmación de que el pago ha sido eliminado exitosamente.

        @param int $id El ID del pago fijo que se va a eliminar.
        
        Redirige al usuario a la página anterior con un mensaje de éxito indicando que el pago ha sido eliminado correctamente.
    */
    public function deletePago($id){
        // Recupera el pago fijo específico por su ID
        $pago = Pagos_fijos::where('id_pago',$id)->first();

        //Guarda la ruta del archivo PDF de la orden
        $fileToDelete = public_path($pago->pdf);

        //Si existe el archivo lo elimina
        if (file_exists($fileToDelete)) {
            unlink($fileToDelete);
        }

        //Elimina el registro de la base de datos
        Pagos_Fijos::where('id_pago',$id)->delete();

        //Redirecciona a la página de consulta
        return back()->with('eliminado','eliminado');
    }

    /*
      TODO: Prepara y muestra datos necesarios para la generación de reportes en la interfaz administrativa.

      Este método se encarga de mostrar la vista que contiene los formularios para solicitar los reportes.

      Devuelve la vista 'Compras.reportes'.
    */
    public function reportes() {
        //Retorna la vista de reportes.
        return view('Compras.reportes');
    }

    /*
      TODO: Genera y sirve un reporte en archivo excel de requisiciones basado en el intervalo de tiempo especificado.

      Este método maneja la solicitud de generación de reportes de requisiciones. Según el rango de fechas del reporte, recupera las
      requisiciones correspondientes de la base de datos. Finalmente, el método genera un archivo excel utilizando estos datos, que luego
      es enviado directamente al cliente para su descarga.

      Sirve un archivo excel generado directamente al navegador del usuario.
    */
    public function reporteReq(Request $req)
    {
        // Validar los datos recibidos
        $req->validate([
            'inicio' => 'required|date',
            'fin' => 'required|date|after_or_equal:inicio',
        ]);

        // Obtener las fechas del formulario
        $fInicio = $req->input('inicio').' 00:00:00';
        $fFin = $req->input('fin').' 23:59:59';

        // Dar formato a las fechas obtenidas
        $fechaInicio = date('d/m/Y', strtotime($fInicio));
        $fechaFin = date('d/m/Y', strtotime($fFin));

        // Almacenar las fechas en un arreglo para mostrar en el Excel los rangos consultados
        $fechas = [
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ];

        // Obtener los departamentos seleccionados (si se ha implementado)
        $departamentos = $req->input('departamentos', []);

        // Construir la consulta con INNER JOIN
        $query = Requisiciones::join('users', 'requisiciones.usuario_id', '=', 'users.id')
            ->leftJoin('unidades','requisiciones.unidad_id','unidades.id_unidad')
            ->select('requisiciones.*','unidades.id_unidad','unidades.tipo','n_de_permiso', 'users.nombres', 'users.apellidoP', 'users.departamento')
            ->whereBetween('requisiciones.created_at', [$fInicio, $fFin]);

        // Si se han seleccionado departamentos, filtrar por ellos
        if (!empty($departamentos)) {
            $query->whereIn('users.departamento', $departamentos);
        }

        // Ejecutar la consulta y obtener los resultados
        $datosRequisicion = $query->get();

        // Crear un nuevo archivo Excel para los datos de las requisiciones
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Asignar nombre a la hoja
        $sheet->setTitle('Requisiciones');

        // Añadir borde grueso a la celda A1
        $sheet->getStyle('A1:F1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

        // Combinar celdas de la fila 1 para el título
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'REPORTE GENERAL DE REQUISICIONES');

        // Establecer el color de fondo de la celda A1
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF69B0F3');

        // Centrar los encabezados y ajustar tamaño de letra
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setSize(16);

        $sheet->setCellValue('A3', 'Fecha Inicio:');
        $sheet->setCellValue('B3', $fechas['fecha_inicio']);
        $sheet->setCellValue('D3', 'Fecha Fin:');
        $sheet->setCellValue('E3', $fechas['fecha_fin']);

        // Centrar los encabezados de fechas
        $sheet->getStyle('A3:F3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a los encabezados de fecha
        $sheet->getStyle('A3:B3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);
        $sheet->getStyle('D3:E3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

        // Establecer el color de fondo de los encabezados de fecha
        $sheet->getStyle('A3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF99C6F1');
        $sheet->getStyle('D3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF99C6F1');

        // Escribir encabezados en el archivo Excel
        $sheet->setCellValue('A5', 'Mes');
        $sheet->setCellValue('B5', 'Semana');
        $sheet->setCellValue('C5', 'Rango');
        $sheet->setCellValue('D5', 'Fecha Creación');
        $sheet->setCellValue('E5', 'Folio');
        $sheet->setCellValue('F5', 'Nombre Usuario');
        $sheet->setCellValue('G5', 'Departamento');
        $sheet->setCellValue('H5', 'Unidad');
        $sheet->setCellValue('I5', 'Estado');

        // Establecer el color de fondo de los encabezados
        $sheet->getStyle('A5:I5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF99C6F1');

        // Centrar los encabezados
        $sheet->getStyle('A5:I5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a los encabezados
        $sheet->getStyle('A5:I5')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

         // Ajustar el tamaño de las columnas al contenido
         foreach (range('A', 'I') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Escribir los datos de las requisiciones en el archivo Excel
        // Comienza a escribir los datos desde la fila 6
        $rowNumber = 6;

        // Para cada requisicion que obtenga en la consulta
        foreach ($datosRequisicion as $requisicion) {

            // Concatena el nombre del solicitante y su apellido paterno
            $nombreCompleto = $requisicion->nombres . ' ' . $requisicion->apellidoP;

            // Dar fomato a la fecha de cada requisición
            $fechaformato = date('d/m/Y', strtotime($requisicion->created_at));

            $fechaInput = $fechaformato; // Fecha en formato dd/mm/aaaa

            // Convertir la fecha a un objeto Carbon
            $fechaConvert = Carbon::createFromFormat('d/m/Y', $fechaInput);

            // Obtener el mes en texto (en español)
            $nombreMes = $fechaConvert->locale('es')->translatedFormat('F');

            // Obtener el número de semana
            $numeroSemana = $fechaConvert->weekOfYear;

            // Obtener la fecha del lunes y viernes de esa semana
            $lunes = $fechaConvert->startOfWeek(Carbon::MONDAY)->day;
            $viernes = $fechaConvert->endOfWeek(Carbon::FRIDAY)->day;

            // Valida si la requisicion pertenece a una unidad
            if (empty($requisicion->unidad_id)) {
                //Si no tiene una unidad asignada, entonces el valor de unidad es 'NA'
                $unidad = 'NA';
            }

            // Valida si la requisicion pertenece a la unidad 1 o 2
            elseif($requisicion->unidad_id == 1 || $requisicion->unidad_id == 2){
                // Si pertence, entonces el valor de unidad es 'No asignada'
                $unidad = 'No asignada';

                // Si tiene alguna otra unidad...
            } else{
                // Si el tipo es camión o camioneta
                if($requisicion->tipo === "CAMIÓN" || $requisicion->tipo=== "CAMIONETA"){
                    // Y unidad es su permiso
                    $unidad = $requisicion->n_de_permiso;

                } else{
                    // Si no, son sus placas (unidad_id)
                    $unidad = $requisicion->id_unidad;
                }
            }
            // Escribir los datos de la requisición en las celdas correspondientes
            $sheet->setCellValue('A' . $rowNumber, $nombreMes);
            $sheet->setCellValue('B' . $rowNumber, $numeroSemana);
            $sheet->setCellValue('C' . $rowNumber, $lunes.' - '.$viernes);
            $sheet->setCellValue('D' . $rowNumber, $fechaformato);
            $sheet->setCellValue('E' . $rowNumber, $requisicion->id_requisicion);
            $sheet->setCellValue('F' . $rowNumber, $nombreCompleto);
            $sheet->setCellValue('G' . $rowNumber, $requisicion->departamento);
            $sheet->setCellValue('H' . $rowNumber, $unidad);
            $sheet->setCellValue('I' . $rowNumber, $requisicion->estado);

            // Centrar las celdas de la fila actual
            $sheet->getStyle('A' . $rowNumber . ':I' . $rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Añadir bordes normales a las celdas de los datos
            $sheet->getStyle('A' . $rowNumber . ':I' . $rowNumber)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Incrementar el número de fila para la siguiente requisición
            $rowNumber++;
        }

        // Aplicar filtros a la taibla de datos
        $sheet->setAutoFilter('A5:I5');

        // Configurar el archivo para descarga
        $fileName = 'reporte_requisiciones_' . Carbon::now()->format('Ymd_His') . '.xlsx';

        // Crear un objeto Spreadsheet de PhpSpreadsheet
        $writer = new Xlsx($spreadsheet);

        // Crear una respuesta de transmisión para la descarga
        $response = new StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        });

        // Configurar los encabezados de la respuesta
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $fileName . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        // Enviar la respuesta al navegador
        return $response;
    }

    /*
      TODO: Genera y sirve un reporte en archivo excel de las órdenes de compra basado en el intervalo de tiempo especificado.

      Este método maneja la solicitud de generación de reportes de órdenes de compra. Según el rango de fechas del reporte
      y los departamentos que se requiera consultar, recupera las órdenes de compra correspondientes de la base de datos,
      diferenciando entre órdenes pendientes y finalizadas. Finalmente, el método genera un archivo de excel utilizando estos datos,
      que luego es enviado directamente al cliente para su descarga.

      Sirve un archivo excel generado directamente a los archivos del usuario.
    */
    public function reporteOrd(Request $req){

        // Validar los datos recibidos
        $req->validate([
            'inicio' => 'required|date',
            'fin' => 'required|date|after_or_equal:inicio',
        ]);

        // Obtener las fechas del formulario
        // Obtener las fechas del formulario
        $fInicio = $req->input('inicio').' 00:00:00';
        $fFin = $req->input('fin').' 23:59:59';

        //Da formato a la fechas obtenidas
        $fechaInicio = date('d/m/Y', strtotime($fInicio));
        $fechaFin = date('d/m/Y', strtotime($fFin));

        //Almacen las fechas en un arreglo para mostrar en el PDF los rangos consultados
        $fechas = [
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ];

        // Obtener los departamentos seleccionados (si se ha implementado)
        $departamentos = $req->input('departamentos', []);

        $queryTotal = Requisiciones::select(
            'requisiciones.id_requisicion', 'requisiciones.notas', 'requisiciones.estado as estadoReq', 'requisiciones.created_at as fechaReq',
            'orden_compras.id_orden', 'orden_compras.created_at as fecha_orden', 'orden_compras.estado as estadoOrd', 'orden_compras.costo_total',
            'users.nombres', 'users.apellidoP', 'users.departamento',
            'unidades.id_unidad', 'unidades.tipo', 'unidades.n_de_permiso',
            'proveedores.nombre'
        )
        ->leftJoin('users', 'requisiciones.usuario_id', '=', 'users.id')
        ->leftJoin('unidades', 'requisiciones.unidad_id', '=', 'unidades.id_unidad')
        ->leftJoin('cotizaciones', 'cotizaciones.requisicion_id', '=', 'requisiciones.id_requisicion')
        ->leftJoin('orden_compras', 'orden_compras.cotizacion_id', '=', 'cotizaciones.id_cotizacion')
        ->leftJoin('proveedores', 'orden_compras.proveedor_id', '=', 'proveedores.id_proveedor')
        ->whereBetween('requisiciones.created_at', [$fInicio, $fFin])
        ->where(function ($query) {
            $query->whereIn('requisiciones.estado', ['Finalizado', 'Comprado'])
                  ->orWhereNotIn('requisiciones.estado', ['Finalizado', 'Comprado']);
        });    

        // Construir la consulta con INNER JOIN
        $queryPendientes = Requisiciones::select(
            'requisiciones.id_requisicion', 'requisiciones.notas', 'requisiciones.estado as estadoReq', 'requisiciones.created_at as fechaReq',
            'orden_compras.id_orden', 'orden_compras.created_at as fecha_orden', 'orden_compras.estado as estadoOrd', 'orden_compras.costo_total',
            'users.nombres', 'users.apellidoP', 'users.departamento',
            'unidades.id_unidad', 'unidades.tipo', 'unidades.n_de_permiso',
            'proveedores.nombre'
        )
        ->leftJoin('users', 'requisiciones.usuario_id', '=', 'users.id')
        ->leftJoin('unidades', 'requisiciones.unidad_id', '=', 'unidades.id_unidad')
        ->leftJoin('cotizaciones', 'cotizaciones.requisicion_id', '=', 'requisiciones.id_requisicion')
        ->leftJoin('orden_compras', 'orden_compras.cotizacion_id', '=', 'cotizaciones.id_cotizacion')
        ->leftJoin('proveedores', 'orden_compras.proveedor_id', '=', 'proveedores.id_proveedor')
        ->whereBetween('requisiciones.created_at', [$fInicio, $fFin])
        ->where(function ($query) {
            $query->whereIn('requisiciones.estado', ['Finalizado', 'Comprado'])
                  ->orWhereNotIn('requisiciones.estado', ['Finalizado', 'Comprado']);
        })
        ->whereNull('orden_compras.estado');     

        // Construir la consulta con INNER JOIN
        $queryPagados = Requisiciones::select(
            'requisiciones.id_requisicion', 'requisiciones.notas', 'requisiciones.estado as estadoReq', 'requisiciones.created_at as fechaReq',
            'orden_compras.id_orden', 'orden_compras.created_at as fecha_orden', 'orden_compras.estado as estadoOrd', 'orden_compras.costo_total',
            'users.nombres', 'users.apellidoP', 'users.departamento',
            'unidades.id_unidad', 'unidades.tipo', 'unidades.n_de_permiso',
            'proveedores.nombre'
        )
        ->leftJoin('users', 'requisiciones.usuario_id', '=', 'users.id')
        ->leftJoin('unidades', 'requisiciones.unidad_id', '=', 'unidades.id_unidad')
        ->leftJoin('cotizaciones', 'cotizaciones.requisicion_id', '=', 'requisiciones.id_requisicion')
        ->leftJoin('orden_compras', 'orden_compras.cotizacion_id', '=', 'cotizaciones.id_cotizacion')
        ->leftJoin('proveedores', 'orden_compras.proveedor_id', '=', 'proveedores.id_proveedor')
        ->whereBetween('requisiciones.created_at', [$fInicio, $fFin])
        ->where(function ($query) {
            $query->whereIn('requisiciones.estado', ['Finalizado', 'Comprado'])
                  ->orWhereNotIn('requisiciones.estado', ['Finalizado', 'Comprado']);
        })
        ->where('orden_compras.estado', '=', 'Pagado');    

            // Si se han seleccionado departamentos, filtrar por ellos
        if (!empty($departamentos)) {
            $queryPendientes->whereIn('users.departamento', $departamentos);
            $queryPagados->whereIn('users.departamento', $departamentos);
            $queryTotal->whereIn('users.departamento', $departamentos);
        }

        // Ejecutar la consulta y obtener los resultados
        $datosGastosFinalizados = $queryPagados->get();
        $datosGastosPendientes = $queryPendientes->get();
        $datosGastos = $queryTotal->get();

        // Crear un nuevo archivo Excel para los datos de las requisiciones
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Asignar nombre a la hoja
        $sheet->setTitle('Ordenes de Compra');

        // Añadir borde grueso a la celda A1
        $sheet->getStyle('A1:G1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

        // Combinar celdas de la fila 1 para el título
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'REPORTE GENERAL DE ORDENES DE COMPRA');

        // Establecer el color de fondo de la celda A1
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF69B0F3');

        // Centrar los encabezados y ajustar tamaño de letra
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setSize(18);

        $sheet->setCellValue('B3', 'Fecha Inicio:');
        $sheet->setCellValue('C3', $fechas['fecha_inicio']);
        $sheet->setCellValue('E3', 'Fecha Fin:');
        $sheet->setCellValue('F3', $fechas['fecha_fin']);

        // Centrar los encabezados de fechas
        $sheet->getStyle('A3:G3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a los encabezados de fecha
        $sheet->getStyle('B3:C3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);
        $sheet->getStyle('E3:F3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

        // Establecer el color de fondo de los encabezados de fecha
        $sheet->getStyle('B3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF99C6F1');
        $sheet->getStyle('E3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF99C6F1');

        // Combinar celdas de la fila 5 para clasificar requisiciones
        $sheet->mergeCells('A5:D5');

        $sheet->setCellValue('A5', 'Registro de ordenes de compra');

        // Centrar los encabezados y ajustar tamaño de letra
        $sheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A5')->getFont()->setSize(16);

        // Escribir encabezados en el archivo Excel
        $sheet->setCellValue('A7', 'Mes');
        $sheet->setCellValue('B7', 'Semana');
        $sheet->setCellValue('C7', 'Rango');
        $sheet->setCellValue('D7', 'Fecha Requisicion');
        $sheet->setCellValue('E7', 'Área');
        $sheet->setCellValue('F7', 'Solicitante');
        $sheet->setCellValue('G7', 'Requisicion');
        $sheet->setCellValue('H7', 'Estado');
        $sheet->setCellValue('I7', 'orden compra');
        $sheet->setCellValue('J7', 'Semana Pago');
        $sheet->setCellValue('K7', 'Proveedor');
        $sheet->setCellValue('L7', 'Costo');
        $sheet->setCellValue('M7', 'Unidad');

        // Establecer el color de fondo de los encabezados
        $sheet->getStyle('A7:M7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF99C6F1');

        // Centrar los encabezados
        $sheet->getStyle('A7:M7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a los encabezados
        $sheet->getStyle('A7:M7')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

         // Ajustar el tamaño de las columnas al contenido
         foreach (range('A', 'M') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Escribir los datos de las requisiciones en el archivo Excel
        // Comienza a escribir los datos desde la fila 8
        $rowNumber = 8;

        // Para cada orden de compra que obtenga la consulta
        foreach ($datosGastos as $orden) {

            // Contatena el nombre completo del solicitante
            $nombreCompleto = $orden->nombres . ' ' . $orden->apellidoP;

            // Le da formato dd/mm/aaaa a la fecha creacion de la requisición
            $fecha = date('d/m/Y', strtotime($orden->fechaReq));

            $fechaInput = $fecha; // Fecha en formato dd/mm/aaaa

            // Convertir la fecha a un objeto Carbon
            $fechaConvert = Carbon::createFromFormat('d/m/Y', $fechaInput);

            // Obtener el mes en texto (en español)
            $nombreMes = $fechaConvert->locale('es')->translatedFormat('F');

            // Obtener el número de semana
            $numeroSemana = $fechaConvert->weekOfYear;

            // Convertir la fecha de la orden a un objeto Carbon
            $fechaOrden = Carbon::parse($orden->fecha_orden);

            // Obtener el número de semana
            $numeroSemanaOrden = $fechaOrden->weekOfYear;

            // Obtener la fecha del lunes y viernes de esa semana
            $lunes = $fechaConvert->startOfWeek(Carbon::MONDAY)->day;
            $viernes = $fechaConvert->endOfWeek(Carbon::FRIDAY)->day;

            // Valida si la requisición pertenece a una unidad
            if (empty($orden->id_unidad)) {
                // Si no tiene una unidad asignada, entonces el valor de unidad es 'NA'
                $unidad = 'NA';
            }

            // Valida si la requisición pertenece a la unidad 1 o 2
            elseif($orden->id_unidad == 1 || $orden->id_unidad == 2){
                // Si pertenece, entonces el valor de unidad es 'No signada'
                $unidad = 'No asignada';

                //S tiene otra unidad...
            } else{

                //Valida si el tipo es camión o camioneta
                if($orden->tipo === "CAMIÓN" || $orden->tipo=== "CAMIONETA"){
                    // Si su tipo es alguno de esos, unidad es su permiso
                    $unidad = $orden->n_de_permiso;

                } else{
                    // Si no, son sus placas (unidad_id)
                    $unidad = $orden->id_unidad;
                }
            }

            // Validar si los datos están vacíos
            if (empty($orden->id_orden) || empty($orden->nombre) || empty($orden->costo_total) || empty($orden->fecha_orden)){
                //
                $numeroSemanaOrden = ''; // Cambia el texto a "Sin semana"
                if ($orden->estadoReq != "Finalizado") {
                    $colorFondo = 'FFFFFF'; // Fondo blanco si los datos están completos    
                } else {
                    $colorFondo = 'FFFF99'; // Código de color amarillo pastel            
                }                
            } else {
                $colorFondo = 'FFFFFF'; // Fondo blanco si los datos están completos
            }

            // Escribir los datos de la orden de compra en las celdas correspondientes
            $sheet->setCellValue('A' . $rowNumber, $nombreMes);
            $sheet->setCellValue('B' . $rowNumber, $numeroSemana);
            $sheet->setCellValue('C' . $rowNumber, $lunes.' - '.$viernes);
            $sheet->setCellValue('D' . $rowNumber, $fecha);
            $sheet->setCellValue('E' . $rowNumber, $orden->departamento);
            $sheet->setCellValue('F' . $rowNumber, $nombreCompleto);
            $sheet->setCellValue('G' . $rowNumber, $orden->id_requisicion);
            $sheet->setCellValue('H' . $rowNumber, $orden->estadoReq);
            $sheet->setCellValue('I' . $rowNumber, $orden->id_orden);
            $sheet->setCellValue('J' . $rowNumber, $numeroSemanaOrden);
            $sheet->setCellValue('K' . $rowNumber, $orden->nombre);
            $sheet->setCellValue('L' . $rowNumber, $orden->costo_total);
            $sheet->setCellValue('M' . $rowNumber, $unidad);

            // Aplicar formato de color a toda la fila
            $sheet->getStyle('A' . $rowNumber . ':M' . $rowNumber)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $colorFondo],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ]);

            // Centrar las celdas de la fila actual
            $sheet->getStyle('A' . $rowNumber . ':M' . $rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Añadir bordes normales a las celdas de los datos
            $sheet->getStyle('A' . $rowNumber . ':M' . $rowNumber)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Aumenta en 1 la fila.
            $rowNumber++;
        }

        // Aplicar filtros a la tabla de datos
        $sheet->setAutoFilter('A7:M7');

        // Calcular y escribir el total de los costos al final de la tabla
        $sheet->setCellValue('K' . $rowNumber, 'Total');
        $sheet->setCellValue('L' . $rowNumber, '=SUM(L8:L' . ($rowNumber - 1) . ')');

        // Formato de moneda para la celda del total
        $sheet->getStyle('L' . $rowNumber)->getNumberFormat()->setFormatCode('$#,##0.00');

        // Centrar las celdas del total
        $sheet->getStyle('K' . $rowNumber . ':K' . $rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a la fila del total
        $sheet->getStyle('K' . $rowNumber . ':K' . $rowNumber)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

        // Crear segunda hoja
        $sheet2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Ordenes Pendientes');
        $spreadsheet->addSheet($sheet2);

        // Añadir borde grueso a la celda A1
        $sheet2->getStyle('A1:H1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

        // Combinar celdas de la fila 1 para el título
        $sheet2->mergeCells('A1:H1');
        $sheet2->setCellValue('A1', 'REPORTE GENERAL DE ORDENES DE COMPRA');

        // Establecer el color de fondo de la celda A1
        $sheet2->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF69B0F3');

        // Centrar los encabezados y ajustar tamaño de letra
        $sheet2->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet2->getStyle('A1')->getFont()->setSize(18);

        $sheet2->setCellValue('B3', 'Fecha Inicio:');
        $sheet2->setCellValue('C3', $fechas['fecha_inicio']);
        $sheet2->setCellValue('E3', 'Fecha Fin:');
        $sheet2->setCellValue('F3', $fechas['fecha_fin']);

        // Centrar los encabezados de fechas
        $sheet2->getStyle('A3:G3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a los encabezados de fecha
        $sheet2->getStyle('B3:C3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);
        $sheet2->getStyle('E3:F3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

        // Establecer el color de fondo de los encabezados de fecha
        $sheet2->getStyle('B3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF99C6F1');
        $sheet2->getStyle('E3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF99C6F1');

        // Combinar celdas de la fila 5 para clasificar requisiciones
        $sheet2->mergeCells('A5:D5');

        $sheet2->setCellValue('A5', 'Registro de ordenes de compra pendientes');

        // Centrar los encabezados y ajustar tamaño de letra
        $sheet2->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet2->getStyle('A5')->getFont()->setSize(16);

        // Escribir encabezados en el archivo Excel
        $sheet2->setCellValue('A7', 'Mes');
        $sheet2->setCellValue('B7', 'Semana');
        $sheet2->setCellValue('C7', 'Rango');
        $sheet2->setCellValue('D7', 'Fecha Requisicion');
        $sheet2->setCellValue('E7', 'Área');
        $sheet2->setCellValue('F7', 'Solicitante');
        $sheet2->setCellValue('G7', 'Requisicion');
        $sheet2->setCellValue('H7', 'Estado');
        $sheet2->setCellValue('I7', 'orden compra');
        $sheet2->setCellValue('J7', 'Semana Pago');
        $sheet2->setCellValue('K7', 'Proveedor');
        $sheet2->setCellValue('L7', 'Costo');
        $sheet2->setCellValue('M7', 'Unidad');

        // Establecer el color de fondo de los encabezados
        $sheet2->getStyle('A7:M7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF99C6F1');

        // Centrar los encabezados
        $sheet2->getStyle('A7:M7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a los encabezados
        $sheet2->getStyle('A7:M7')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

         // Ajustar el tamaño de las columnas al contenido
         foreach (range('A', 'M') as $columnID) {
            $sheet2->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Calcular el total de costos pendientes
        $totalPendientes = $datosGastosPendientes->sum('costo_total');

        // Escribir los datos de las requisiciones en el archivo Excel
        // Comeinza a escribir los datos desde la fila 8
        $rowNumber = 8;

        // Para cada orden de compra que obtenga en la consulta
        foreach ($datosGastosPendientes as $orden) {

            // Contatena el nombre completo del solicitante
            $nombreCompleto = $orden->nombres . ' ' . $orden->apellidoP;

            // Le da formato dd/mm/aaaa a la fecha creacion de la requisición
            $fecha = date('d/m/Y', strtotime($orden->fechaReq));

            $fechaInput = $fecha; // Fecha en formato dd/mm/aaaa

            // Convertir la fecha a un objeto Carbon
            $fechaConvert = Carbon::createFromFormat('d/m/Y', $fechaInput);

            // Convertir la fecha de la orden a un objeto Carbon
            $fechaOrden = Carbon::parse($orden->fecha_orden);

            // Obtener el número de semana
            $numeroSemanaOrden = $fechaOrden->weekOfYear;
            
            // Obtener el mes en texto (en español)
            $nombreMes = $fechaConvert->locale('es')->translatedFormat('F');

            // Obtener el número de semana
            $numeroSemana = $fechaConvert->weekOfYear;

            // Obtener la fecha del lunes y viernes de esa semana
            $lunes = $fechaConvert->startOfWeek(Carbon::MONDAY)->day;
            $viernes = $fechaConvert->endOfWeek(Carbon::FRIDAY)->day;

            // Valida si la requisición pertenece a una unidad
            if (empty($orden->id_unidad)) {
                // Si no tiene una unidad asignada, entonces el valor de unidad es 'NA'
                $unidad = 'NA';
            }

            // Valida si la requisición pertenece a la unidad 1 o 2
            elseif($orden->id_unidad == 1 || $orden->id_unidad == 2){
                // Si pertenece, entonces el valor de unidad es 'No signada'
                $unidad = 'No asignada';

                //S tiene otra unidad...
            } else{

                //Valida si el tipo es camión o camioneta
                if($orden->tipo === "CAMIÓN" || $orden->tipo=== "CAMIONETA"){
                    // Si su tipo es alguno de esos, unidad es su permiso
                    $unidad = $orden->n_de_permiso;

                } else{
                    // Si no, son sus placas (unidad_id)
                    $unidad = $orden->id_unidad;
                }
            }

            $sheet2->setCellValue('A' . $rowNumber, $nombreMes);
            $sheet2->setCellValue('B' . $rowNumber, $numeroSemana);
            $sheet2->setCellValue('C' . $rowNumber, $lunes.' - '.$viernes);
            $sheet2->setCellValue('D' . $rowNumber, $fecha);
            $sheet2->setCellValue('E' . $rowNumber, $orden->departamento);
            $sheet2->setCellValue('F' . $rowNumber, $nombreCompleto);
            $sheet2->setCellValue('G' . $rowNumber, $orden->id_requisicion);
            $sheet2->setCellValue('H' . $rowNumber, $orden->estadoReq);
            $sheet2->setCellValue('I' . $rowNumber, $orden->id_orden);
            $sheet2->setCellValue('J' . $rowNumber, $numeroSemanaOrden);
            $sheet2->setCellValue('K' . $rowNumber, $orden->nombre);
            $sheet2->setCellValue('L' . $rowNumber, $orden->costo_total);
            $sheet2->setCellValue('M' . $rowNumber, $unidad);

            // Aplicar formato de color a toda la fila
            $sheet2->getStyle('A' . $rowNumber . ':M' . $rowNumber)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $colorFondo],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ]);

            // Centrar las celdas de la fila actual
            $sheet2->getStyle('A' . $rowNumber . ':M' . $rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Añadir bordes normales a las celdas de los datos
            $sheet2->getStyle('A' . $rowNumber . ':M' . $rowNumber)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Aumenta en 1 la fila.
            $rowNumber++;
        }

        // Aplicar filtros a la tabla de datos
        $sheet2->setAutoFilter('A7:M7');

        // Calcular y escribir el total de los costos al final de la tabla
        $sheet2->setCellValue('K' . $rowNumber, 'Total');
        $sheet2->setCellValue('L' . $rowNumber, '=SUM(L8:L' . ($rowNumber - 1) . ')');

        // Formato de moneda para la celda del total
        $sheet2->getStyle('L' . $rowNumber)->getNumberFormat()->setFormatCode('$#,##0.00');

        // Centrar las celdas del total
        $sheet2->getStyle('K' . $rowNumber . ':L' . $rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a la fila del total
        $sheet2->getStyle('K' . $rowNumber . ':L' . $rowNumber)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

        // Crear segunda hoja
        $sheet3 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Ordenes Pagadas');
        $spreadsheet->addSheet($sheet3);

        // Añadir borde grueso a la celda A1
        $sheet3->getStyle('A1:H1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

        // Combinar celdas de la fila 1 para el título
        $sheet3->mergeCells('A1:H1');
        $sheet3->setCellValue('A1', 'REPORTE GENERAL DE ORDENES DE COMPRA');

        // Establecer el color de fondo de la celda A1
        $sheet3->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF69B0F3');

        // Centrar los encabezados y ajustar tamaño de letra
        $sheet3->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet3->getStyle('A1')->getFont()->setSize(18);

        $sheet3->setCellValue('B3', 'Fecha Inicio:');
        $sheet3->setCellValue('C3', $fechas['fecha_inicio']);
        $sheet3->setCellValue('E3', 'Fecha Fin:');
        $sheet3->setCellValue('F3', $fechas['fecha_fin']);

        // Centrar los encabezados de fechas
        $sheet3->getStyle('A3:G3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a los encabezados de fecha
        $sheet3->getStyle('B3:C3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);
        $sheet3->getStyle('E3:F3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

        // Establecer el color de fondo de los encabezados de fecha
        $sheet3->getStyle('B3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF99C6F1');
        $sheet3->getStyle('E3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF99C6F1');

        // Combinar celdas de la fila 5 para clasificar requisiciones
        $sheet3->mergeCells('A5:D5');

        $sheet3->setCellValue('A5', 'Registro de ordenes de compra pagadas');

        // Centrar los encabezados y ajustar tamaño de letra
        $sheet3->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet3->getStyle('A5')->getFont()->setSize(16);

        // Escribir encabezados en el archivo Excel
        $sheet3->setCellValue('A7', 'Mes');
        $sheet3->setCellValue('B7', 'Semana');
        $sheet3->setCellValue('C7', 'Rango');
        $sheet3->setCellValue('D7', 'Fecha Requisicion');
        $sheet3->setCellValue('E7', 'Área');
        $sheet3->setCellValue('F7', 'Solicitante');
        $sheet3->setCellValue('G7', 'Requisicion');
        $sheet3->setCellValue('H7', 'Estado');
        $sheet3->setCellValue('I7', 'orden compra');
        $sheet3->setCellValue('J7', 'Semana Pago');
        $sheet3->setCellValue('K7', 'Proveedor');
        $sheet3->setCellValue('L7', 'Costo');
        $sheet3->setCellValue('M7', 'Unidad');

        // Establecer el color de fondo de los encabezados
        $sheet3->getStyle('A7:M7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF99C6F1');

        // Centrar los encabezados
        $sheet3->getStyle('A7:M7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a los encabezados
        $sheet3->getStyle('A7:M7')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

         // Ajustar el tamaño de las columnas al contenido
         foreach (range('A', 'M') as $columnID) {
            $sheet3->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Calcular el total de costos pendientes
        $totalFinalizados = $datosGastosFinalizados->sum('costo_total');

        // Escribir los datos de las requisiciones en el archivo Excel
        // Comeinza a escribir los datos desde la fila 8
        $rowNumber = 8;

        // Para cada orden de compra que obtenga en la consulta
        foreach ($datosGastosFinalizados as $orden) {

            // Contatena el nombre completo del solicitante
            $nombreCompleto = $orden->nombres . ' ' . $orden->apellidoP;

            // Le da formato dd/mm/aaaa a la fecha creacion de la requisición
            $fecha = date('d/m/Y', strtotime($orden->fechaReq));

            $fechaInput = $fecha; // Fecha en formato dd/mm/aaaa

            // Convertir la fecha a un objeto Carbon
            $fechaConvert = Carbon::createFromFormat('d/m/Y', $fechaInput);

            // Convertir la fecha de la orden a un objeto Carbon
            $fechaOrden = Carbon::parse($orden->fecha_orden);

            // Obtener el número de semana
            $numeroSemanaOrden = $fechaOrden->weekOfYear;            

            // Obtener el mes en texto (en español)
            $nombreMes = $fechaConvert->locale('es')->translatedFormat('F');

            // Obtener el número de semana
            $numeroSemana = $fechaConvert->weekOfYear;

            // Obtener la fecha del lunes y viernes de esa semana
            $lunes = $fechaConvert->startOfWeek(Carbon::MONDAY)->day;
            $viernes = $fechaConvert->endOfWeek(Carbon::FRIDAY)->day;

            // Valida si la requisición pertenece a una unidad
            if (empty($orden->id_unidad)) {
                // Si no tiene una unidad asignada, entonces el valor de unidad es 'NA'
                $unidad = 'NA';
            }

            // Valida si la requisición pertenece a la unidad 1 o 2
            elseif($orden->id_unidad == 1 || $orden->id_unidad == 2){
                // Si pertenece, entonces el valor de unidad es 'No signada'
                $unidad = 'No asignada';

                //S tiene otra unidad...
            } else{

                //Valida si el tipo es camión o camioneta
                if($orden->tipo === "CAMIÓN" || $orden->tipo=== "CAMIONETA"){
                    // Si su tipo es alguno de esos, unidad es su permiso
                    $unidad = $orden->n_de_permiso;

                } else{
                    // Si no, son sus placas (unidad_id)
                    $unidad = $orden->id_unidad;
                }
            }

            $sheet3->setCellValue('A' . $rowNumber, $nombreMes);
            $sheet3->setCellValue('B' . $rowNumber, $numeroSemana);
            $sheet3->setCellValue('C' . $rowNumber, $lunes.' - '.$viernes);
            $sheet3->setCellValue('D' . $rowNumber, $fecha);
            $sheet3->setCellValue('E' . $rowNumber, $orden->departamento);
            $sheet3->setCellValue('F' . $rowNumber, $nombreCompleto);
            $sheet3->setCellValue('G' . $rowNumber, $orden->id_requisicion);
            $sheet3->setCellValue('H' . $rowNumber, $orden->estadoReq);
            $sheet3->setCellValue('I' . $rowNumber, $orden->id_orden);
            $sheet3->setCellValue('J' . $rowNumber, $numeroSemanaOrden);
            $sheet3->setCellValue('K' . $rowNumber, $orden->nombre);
            $sheet3->setCellValue('L' . $rowNumber, $orden->costo_total);
            $sheet3->setCellValue('M' . $rowNumber, $unidad);

            // Aplicar formato de color a toda la fila
            $sheet3->getStyle('A' . $rowNumber . ':M' . $rowNumber)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $colorFondo],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ]);


            // Centrar las celdas de la fila actual
            $sheet3->getStyle('A' . $rowNumber . ':M' . $rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Añadir bordes normales a las celdas de los datos
            $sheet3->getStyle('A' . $rowNumber . ':M' . $rowNumber)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Aumenta en 1 la fila.
            $rowNumber++;
        }

        // Aplicar filtros a la tabla de datos
        $sheet3->setAutoFilter('A7:M7');

        // Calcular y escribir el total de los costos al final de la tabla
        $sheet3->setCellValue('K' . $rowNumber, 'Total');
        $sheet3->setCellValue('L' . $rowNumber, '=SUM(L8:L' . ($rowNumber - 1) . ')');

        // Formato de moneda para la celda del total
        $sheet3->getStyle('L' . $rowNumber)->getNumberFormat()->setFormatCode('$#,##0.00');

        // Centrar las celdas del total
        $sheet3->getStyle('K' . $rowNumber . ':L' . $rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a la fila del total
        $sheet3->getStyle('K' . $rowNumber . ':L' . $rowNumber)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

        $spreadsheet->setActiveSheetIndex(0);

        // Configurar el archivo para descarga
        $fileName = 'reporte_ordenes_' . Carbon::now()->format('Ymd_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        // Crear una respuesta de transmisión para la descarga
        $response = new StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        });

        // Configurar los encabezados de la respuesta
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $fileName . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        // Devolver la respuesta para descargar el archivo
        return $response;
    }

    /*
      TODO: Genera y sirve un reporte en archivo excel de las órdenes de pago basado en el intervalo de tiempo especificado.

      Este método maneja la solicitud de generación de reportes de órdenes de pago. Según el rango de fechas del reporte
      y los departamentos que se requiera consultar, recupera las órdenes de pago correspondientes de la base de datos,
      diferenciando entre órdenes pendientes y finalizadas. Finalmente, el método genera un archivo de excel utilizando estos datos,
      que luego es enviado directamente al cliente para su descarga.

      Sirve un archivo excel generado directamente a los archivos del usuario.
    */

    public function reportePagos(Request $req){

        // Validar los datos recibidos
        $req->validate([
            'inicio' => 'required|date',
            'fin' => 'required|date|after_or_equal:inicio',
        ]);

        // Obtener las fechas del formulario
        // Obtener las fechas del formulario
        $fInicio = $req->input('inicio').' 00:00:00';
        $fFin = $req->input('fin').' 23:59:59';

        //Da formato a la fechas obtenidas
        $fechaInicio = date('d/m/Y', strtotime($fInicio));
        $fechaFin = date('d/m/Y', strtotime($fFin));

        //Almacen las fechas en un arreglo para mostrar en el PDF los rangos consultados
        $fechas = [
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ];

        // Obtener los departamentos seleccionados (si se ha implementado)
        $departamentos = $req->input('departamentos', []);

        $queryTotal = Pagos_Fijos::select('pagos_fijos.id_pago','pagos_fijos.estado','pagos_fijos.created_at','servicios.nombre_servicio as servicio','proveedores.nombre as proveedor',
                'users.nombres as usuario','users.apellidoP','users.departamento')
               ->join('users', 'pagos_fijos.usuario_id', '=', 'users.id')
               ->join('servicios','pagos_fijos.servicio_id','servicios.id_servicio')
               ->join('proveedores', 'servicios.proveedor_id', '=', 'proveedores.id_proveedor')
               ->where(function ($query) use ($fInicio, $fFin) {
                   $query->whereBetween('pagos_fijos.created_at', [$fInicio, $fFin]);
               });

        // Construir la consulta con INNER JOIN
        $queryPendientes = Pagos_Fijos::select('pagos_fijos.id_pago','pagos_fijos.estado','pagos_fijos.created_at','servicios.nombre_servicio as servicio','proveedores.nombre as proveedor',
                'users.nombres as usuario','users.apellidoP','users.departamento')
                ->join('users', 'pagos_fijos.usuario_id', '=', 'users.id')
                ->join('servicios','pagos_fijos.servicio_id','servicios.id_servicio')
                ->join('proveedores', 'servicios.proveedor_id', '=', 'proveedores.id_proveedor')
                ->where(function ($query) use ($fInicio, $fFin) {
                    $query->whereBetween('pagos_fijos.created_at', [$fInicio, $fFin]);
                })
            ->where('pagos_fijos.estado', '=', 'Solicitado');

        // Construir la consulta con INNER JOIN
        $queryPagados = Pagos_Fijos::select('pagos_fijos.id_pago','pagos_fijos.estado','pagos_fijos.created_at','servicios.nombre_servicio as servicio','proveedores.nombre as proveedor',
                'users.nombres as usuario','users.apellidoP','users.departamento')
                ->join('users', 'pagos_fijos.usuario_id', '=', 'users.id')
                ->join('servicios','pagos_fijos.servicio_id','servicios.id_servicio')
                ->join('proveedores', 'servicios.proveedor_id', '=', 'proveedores.id_proveedor')
                ->where(function ($query) use ($fInicio, $fFin) {
                    $query->whereBetween('pagos_fijos.created_at', [$fInicio, $fFin]);
                })
            ->where('pagos_fijos.estado', '=', 'Pagado');

            // Si se han seleccionado departamentos, filtrar por ellos
        if (!empty($departamentos)) {
            $queryPendientes->whereIn('users.departamento', $departamentos);
            $queryPagados->whereIn('users.departamento', $departamentos);
            $queryTotal->whereIn('users.departamento', $departamentos);
        }

        // Ejecutar la consulta y obtener los resultados
        $datosGastosFinalizados = $queryPagados->get();
        $datosGastosPendientes = $queryPendientes->get();
        $datosGastos = $queryTotal->get();

        // Crear un nuevo archivo Excel para los datos de las requisiciones
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Asignar nombre a la hoja
        $sheet->setTitle('Ordenes de Pago');

        // Añadir borde grueso a la celda A1
        $sheet->getStyle('A1:G1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

        // Combinar celdas de la fila 1 para el título
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'REPORTE GENERAL DE ORDENES DE PAGO');

        // Establecer el color de fondo de la celda A1
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF69B0F3');

        // Centrar los encabezados y ajustar tamaño de letra
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setSize(18);

        $sheet->setCellValue('B3', 'Fecha Inicio:');
        $sheet->setCellValue('C3', $fechas['fecha_inicio']);
        $sheet->setCellValue('E3', 'Fecha Fin:');
        $sheet->setCellValue('F3', $fechas['fecha_fin']);

        // Centrar los encabezados de fechas
        $sheet->getStyle('A3:G3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a los encabezados de fecha
        $sheet->getStyle('B3:C3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);
        $sheet->getStyle('E3:F3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

        // Establecer el color de fondo de los encabezados de fecha
        $sheet->getStyle('B3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF99C6F1');
        $sheet->getStyle('E3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF99C6F1');

        // Combinar celdas de la fila 5 para clasificar requisiciones
        $sheet->mergeCells('A5:D5');

        $sheet->setCellValue('A5', 'Registro de ordenes de pago');

        // Centrar los encabezados y ajustar tamaño de letra
        $sheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A5')->getFont()->setSize(16);

        // Escribir encabezados en el archivo Excel
        $sheet->setCellValue('A7', 'Mes');
        $sheet->setCellValue('B7', 'Semana');
        $sheet->setCellValue('C7', 'Rango');
        $sheet->setCellValue('D7', 'Fecha Pago');
        $sheet->setCellValue('E7', 'Área');
        $sheet->setCellValue('F7', 'Solicitante');
        $sheet->setCellValue('G7', 'Folio');
        $sheet->setCellValue('H7', 'Estado');
        $sheet->setCellValue('I7', 'Servicio');
        $sheet->setCellValue('J7', 'Proveedor');
        $sheet->setCellValue('K7', 'Importe');

        // Establecer el color de fondo de los encabezados
        $sheet->getStyle('A7:K7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF99C6F1');

        // Centrar los encabezados
        $sheet->getStyle('A7:K7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a los encabezados
        $sheet->getStyle('A7:K7')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

         // Ajustar el tamaño de las columnas al contenido
         foreach (range('A', 'K') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Escribir los datos de las requisiciones en el archivo Excel
        // Comienza a escribir los datos desde la fila 8
        $rowNumber = 8;

        // Para cada orden de compra que obtenga la consulta
        foreach ($datosGastos as $orden) {

            // Contatena el nombre completo del solicitante
            $nombreCompleto = $orden->usuario . ' ' . $orden->apellidoP;

            // Le da formato dd/mm/aaaa a la fecha creacion de la requisición
            $fecha = date('d/m/Y', strtotime($orden->created_at));

            $fechaInput = $fecha; // Fecha en formato dd/mm/aaaa

            // Convertir la fecha a un objeto Carbon
            $fechaConvert = Carbon::createFromFormat('d/m/Y', $fechaInput);

            // Obtener el mes en texto (en español)
            $nombreMes = $fechaConvert->locale('es')->translatedFormat('F');

            // Obtener el número de semana
            $numeroSemana = $fechaConvert->weekOfYear;

            // Obtener la fecha del lunes y viernes de esa semana
            $lunes = $fechaConvert->startOfWeek(Carbon::MONDAY)->day;
            $viernes = $fechaConvert->endOfWeek(Carbon::FRIDAY)->day;

            $sheet->setCellValue('A' . $rowNumber, $nombreMes);
            $sheet->setCellValue('B' . $rowNumber, $numeroSemana);
            $sheet->setCellValue('C' . $rowNumber, $lunes.' - '.$viernes);
            $sheet->setCellValue('D' . $rowNumber, $fecha);
            $sheet->setCellValue('E' . $rowNumber, $orden->departamento);
            $sheet->setCellValue('F' . $rowNumber, $nombreCompleto);
            $sheet->setCellValue('G' . $rowNumber, $orden->id_pago);
            $sheet->setCellValue('H' . $rowNumber, $orden->estado);
            $sheet->setCellValue('I' . $rowNumber, $orden->servicio);
            $sheet->setCellValue('J' . $rowNumber, $orden->proveedor);
            $sheet->setCellValue('K' . $rowNumber, $orden->costo_total);

            // Centrar las celdas de la fila actual
            $sheet->getStyle('A' . $rowNumber . ':K' . $rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Añadir bordes normales a las celdas de los datos
            $sheet->getStyle('A' . $rowNumber . ':K' . $rowNumber)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Aumenta en 1 la fila.
            $rowNumber++;
        }

        // Aplicar filtros a la tabla de datos
        $sheet->setAutoFilter('A7:K7');

        // Calcular y escribir el total de los costos al final de la tabla
        $sheet->setCellValue('J' . $rowNumber, 'Total');
        $sheet->setCellValue('K' . $rowNumber, '=SUM(K8:K' . ($rowNumber - 1) . ')');

        // Formato de moneda para la celda del total
        $sheet->getStyle('K' . $rowNumber)->getNumberFormat()->setFormatCode('$#,##0.00');

        // Centrar las celdas del total
        $sheet->getStyle('J' . $rowNumber . ':K' . $rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a la fila del total
        $sheet->getStyle('J' . $rowNumber . ':K' . $rowNumber)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

        // Crear segunda hoja
        $sheet2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Pagos Pendientes');
        $spreadsheet->addSheet($sheet2);

        // Añadir borde grueso a la celda A1
        $sheet2->getStyle('A1:G1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

        // Combinar celdas de la fila 1 para el título
        $sheet2->mergeCells('A1:G1');
        $sheet2->setCellValue('A1', 'REPORTE GENERAL DE ORDENES DE PAGO');

        // Establecer el color de fondo de la celda A1
        $sheet2->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF69B0F3');

        // Centrar los encabezados y ajustar tamaño de letra
        $sheet2->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet2->getStyle('A1')->getFont()->setSize(18);

        $sheet2->setCellValue('B3', 'Fecha Inicio:');
        $sheet2->setCellValue('C3', $fechas['fecha_inicio']);
        $sheet2->setCellValue('E3', 'Fecha Fin:');
        $sheet2->setCellValue('F3', $fechas['fecha_fin']);

        // Centrar los encabezados de fechas
        $sheet2->getStyle('A3:G3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a los encabezados de fecha
        $sheet2->getStyle('B3:C3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);
        $sheet2->getStyle('E3:F3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

        // Establecer el color de fondo de los encabezados de fecha
        $sheet2->getStyle('B3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF99C6F1');
        $sheet2->getStyle('E3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF99C6F1');

        // Combinar celdas de la fila 5 para clasificar requisiciones
        $sheet2->mergeCells('A5:D5');

        $sheet2->setCellValue('A5', 'Registro de ordenes de pago');

        // Centrar los encabezados y ajustar tamaño de letra
        $sheet2->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet2->getStyle('A5')->getFont()->setSize(16);

        // Escribir encabezados en el archivo Excel
        $sheet2->setCellValue('A7', 'Mes');
        $sheet2->setCellValue('B7', 'Semana');
        $sheet2->setCellValue('C7', 'Rango');
        $sheet2->setCellValue('D7', 'Fecha Pago');
        $sheet2->setCellValue('E7', 'Área');
        $sheet2->setCellValue('F7', 'Solicitante');
        $sheet2->setCellValue('G7', 'Folio');
        $sheet2->setCellValue('H7', 'Estado');
        $sheet2->setCellValue('I7', 'Servicio');
        $sheet2->setCellValue('J7', 'Proveedor');
        $sheet2->setCellValue('K7', 'Importe');

        // Establecer el color de fondo de los encabezados
        $sheet2->getStyle('A7:K7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF99C6F1');

        // Centrar los encabezados
        $sheet2->getStyle('A7:K7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a los encabezados
        $sheet2->getStyle('A7:K7')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

         // Ajustar el tamaño de las columnas al contenido
         foreach (range('A', 'K') as $columnID) {
            $sheet2->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Escribir los datos de las requisiciones en el archivo Excel
        // Comienza a escribir los datos desde la fila 8
        $rowNumber = 8;

        // Para cada orden de compra que obtenga la consulta
        foreach ($datosGastosPendientes as $orden) {

            // Contatena el nombre completo del solicitante
            $nombreCompleto = $orden->usuario . ' ' . $orden->apellidoP;

            // Le da formato dd/mm/aaaa a la fecha creacion de la requisición
            $fecha = date('d/m/Y', strtotime($orden->created_at));

            $fechaInput = $fecha; // Fecha en formato dd/mm/aaaa

            // Convertir la fecha a un objeto Carbon
            $fechaConvert = Carbon::createFromFormat('d/m/Y', $fechaInput);

            // Obtener el mes en texto (en español)
            $nombreMes = $fechaConvert->locale('es')->translatedFormat('F');

            // Obtener el número de semana
            $numeroSemana = $fechaConvert->weekOfYear;

            // Obtener la fecha del lunes y viernes de esa semana
            $lunes = $fechaConvert->startOfWeek(Carbon::MONDAY)->day;
            $viernes = $fechaConvert->endOfWeek(Carbon::FRIDAY)->day;

            $sheet2->setCellValue('A' . $rowNumber, $nombreMes);
            $sheet2->setCellValue('B' . $rowNumber, $numeroSemana);
            $sheet2->setCellValue('C' . $rowNumber, $lunes.' - '.$viernes);
            $sheet2->setCellValue('D' . $rowNumber, $fecha);
            $sheet2->setCellValue('E' . $rowNumber, $orden->departamento);
            $sheet2->setCellValue('F' . $rowNumber, $nombreCompleto);
            $sheet2->setCellValue('G' . $rowNumber, $orden->id_pago);
            $sheet2->setCellValue('H' . $rowNumber, $orden->estado);
            $sheet2->setCellValue('I' . $rowNumber, $orden->servicio);
            $sheet2->setCellValue('J' . $rowNumber, $orden->proveedor);
            $sheet2->setCellValue('K' . $rowNumber, $orden->costo_total);

            // Centrar las celdas de la fila actual
            $sheet2->getStyle('A' . $rowNumber . ':K' . $rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Añadir bordes normales a las celdas de los datos
            $sheet2->getStyle('A' . $rowNumber . ':K' . $rowNumber)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Aumenta en 1 la fila.
            $rowNumber++;
        }

        // Aplicar filtros a la tabla de datos
        $sheet2->setAutoFilter('A7:K7');

        // Calcular y escribir el total de los costos al final de la tabla
        $sheet2->setCellValue('J' . $rowNumber, 'Total');
        $sheet2->setCellValue('K' . $rowNumber, '=SUM(K8:K' . ($rowNumber - 1) . ')');

        // Formato de moneda para la celda del total
        $sheet2->getStyle('K' . $rowNumber)->getNumberFormat()->setFormatCode('$#,##0.00');

        // Centrar las celdas del total
        $sheet2->getStyle('J' . $rowNumber . ':K' . $rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a la fila del total
        $sheet2->getStyle('J' . $rowNumber . ':K' . $rowNumber)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

        // Crear segunda hoja
        $sheet3 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Pagos Finalizados');
        $spreadsheet->addSheet($sheet3);

        // Añadir borde grueso a la celda A1
        $sheet3->getStyle('A1:G1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

        // Combinar celdas de la fila 1 para el título
        $sheet3->mergeCells('A1:G1');
        $sheet3->setCellValue('A1', 'REPORTE GENERAL DE ORDENES DE PAGO');

        // Establecer el color de fondo de la celda A1
        $sheet3->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF69B0F3');

        // Centrar los encabezados y ajustar tamaño de letra
        $sheet3->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet3->getStyle('A1')->getFont()->setSize(18);

        $sheet3->setCellValue('B3', 'Fecha Inicio:');
        $sheet3->setCellValue('C3', $fechas['fecha_inicio']);
        $sheet3->setCellValue('E3', 'Fecha Fin:');
        $sheet3->setCellValue('F3', $fechas['fecha_fin']);

        // Centrar los encabezados de fechas
        $sheet3->getStyle('A3:G3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a los encabezados de fecha
        $sheet3->getStyle('B3:C3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);
        $sheet3->getStyle('E3:F3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

        // Establecer el color de fondo de los encabezados de fecha
        $sheet3->getStyle('B3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF99C6F1');
        $sheet3->getStyle('E3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF99C6F1');

        // Combinar celdas de la fila 5 para clasificar requisiciones
        $sheet3->mergeCells('A5:D5');

        $sheet3->setCellValue('A5', 'Registro de ordenes de pago');

        // Centrar los encabezados y ajustar tamaño de letra
        $sheet3->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet3->getStyle('A5')->getFont()->setSize(16);

        // Escribir encabezados en el archivo Excel
        $sheet3->setCellValue('A7', 'Mes');
        $sheet3->setCellValue('B7', 'Semana');
        $sheet3->setCellValue('C7', 'Rango');
        $sheet3->setCellValue('D7', 'Fecha Pago');
        $sheet3->setCellValue('E7', 'Área');
        $sheet3->setCellValue('F7', 'Solicitante');
        $sheet3->setCellValue('G7', 'Folio');
        $sheet3->setCellValue('H7', 'Estado');
        $sheet3->setCellValue('I7', 'Servicio');
        $sheet3->setCellValue('J7', 'Proveedor');
        $sheet3->setCellValue('K7', 'Importe');

        // Establecer el color de fondo de los encabezados
        $sheet3->getStyle('A7:K7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF99C6F1');

        // Centrar los encabezados
        $sheet3->getStyle('A7:K7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a los encabezados
        $sheet3->getStyle('A7:K7')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

         // Ajustar el tamaño de las columnas al contenido
         foreach (range('A', 'K') as $columnID) {
            $sheet3->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Escribir los datos de las requisiciones en el archivo Excel
        // Comienza a escribir los datos desde la fila 8
        $rowNumber = 8;

        // Para cada orden de compra que obtenga la consulta
        foreach ($datosGastosFinalizados as $orden) {

            // Contatena el nombre completo del solicitante
            $nombreCompleto = $orden->usuario . ' ' . $orden->apellidoP;

            // Le da formato dd/mm/aaaa a la fecha creacion de la requisición
            $fecha = date('d/m/Y', strtotime($orden->created_at));

            $fechaInput = $fecha; // Fecha en formato dd/mm/aaaa

            // Convertir la fecha a un objeto Carbon
            $fechaConvert = Carbon::createFromFormat('d/m/Y', $fechaInput);

            // Obtener el mes en texto (en español)
            $nombreMes = $fechaConvert->locale('es')->translatedFormat('F');

            // Obtener el número de semana
            $numeroSemana = $fechaConvert->weekOfYear;

            // Obtener la fecha del lunes y viernes de esa semana
            $lunes = $fechaConvert->startOfWeek(Carbon::MONDAY)->day;
            $viernes = $fechaConvert->endOfWeek(Carbon::FRIDAY)->day;

            $sheet3->setCellValue('A' . $rowNumber, $nombreMes);
            $sheet3->setCellValue('B' . $rowNumber, $numeroSemana);
            $sheet3->setCellValue('C' . $rowNumber, $lunes.' - '.$viernes);
            $sheet3->setCellValue('D' . $rowNumber, $fecha);
            $sheet3->setCellValue('E' . $rowNumber, $orden->departamento);
            $sheet3->setCellValue('F' . $rowNumber, $nombreCompleto);
            $sheet3->setCellValue('G' . $rowNumber, $orden->id_pago);
            $sheet3->setCellValue('H' . $rowNumber, $orden->estado);
            $sheet3->setCellValue('I' . $rowNumber, $orden->servicio);
            $sheet3->setCellValue('J' . $rowNumber, $orden->proveedor);
            $sheet3->setCellValue('K' . $rowNumber, $orden->costo_total);

            // Centrar las celdas de la fila actual
            $sheet3->getStyle('A' . $rowNumber . ':K' . $rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Añadir bordes normales a las celdas de los datos
            $sheet3->getStyle('A' . $rowNumber . ':K' . $rowNumber)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Aumenta en 1 la fila.
            $rowNumber++;
        }

        // Aplicar filtros a la tabla de datos
        $sheet3->setAutoFilter('A7:K7');

        // Calcular y escribir el total de los costos al final de la tabla
        $sheet3->setCellValue('J' . $rowNumber, 'Total');
        $sheet3->setCellValue('K' . $rowNumber, '=SUM(K8:K' . ($rowNumber - 1) . ')');

        // Formato de moneda para la celda del total
        $sheet3->getStyle('K' . $rowNumber)->getNumberFormat()->setFormatCode('$#,##0.00');

        // Centrar las celdas del total
        $sheet3->getStyle('J' . $rowNumber . ':K' . $rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a la fila del total
        $sheet3->getStyle('J' . $rowNumber . ':K' . $rowNumber)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

        $spreadsheet->setActiveSheetIndex(0);

        // Configurar el archivo para descarga
        $fileName = 'reporte_ordenes_pago_' . Carbon::now()->format('Ymd_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        // Crear una respuesta de transmisión para la descarga
        $response = new StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        });

        // Configurar los encabezados de la respuesta
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $fileName . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        // Enviar la respuesta
        return $response;
    }

    /*
        TODO: Generar reporte de proveedores

        Este método maneja la solicitud de generación de reporte de proveedores actual. Consulta, recupera 
        los datos registrados a cerca de los proveedores correspondientes de la base de datos. Finalmente, 
        el método genera un archivo de excel utilizando estos datos, que luego es enviado directamente al cliente para su descarga.

        Sirve un archivo excel generado directamente a los archivos del usuario.
    */
    public function reporteProveedores (){

        // Obtener los proveedores activos de la base de datos
        $proveedores = Proveedores::where('estatus',1)
        ->orderBY('nombre','asc')
        ->get();

        // Crear un nuevo archivo Excel para los datos de las requisiciones 
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Asignar nombre a la hoja
        $sheet->setTitle('Proveedores');

        // Añadir borde grueso a la celda A1
        $sheet->getStyle('A1:G1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

        // Combinar celdas de la fila 1 para el título
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'REPORTE GENERAL DE PROVEEDORES');

        // Establecer el color de fondo de la celda A1
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF69B0F3');

        // Centrar los encabezados y ajustar tamaño de letra
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setSize(16);

        // Escribir encabezados en el archivo Excel
        $sheet->setCellValue('A3', 'Folio');
        $sheet->setCellValue('B3', 'Nombre');
        $sheet->setCellValue('C3', 'Sobrenombre');
        $sheet->setCellValue('D3', 'Regimen Fiscal');
        $sheet->setCellValue('E3', 'Telefono');
        $sheet->setCellValue('F3', 'Telefono 2');
        $sheet->setCellValue('G3', 'Contacto');
        $sheet->setCellValue('H3', 'Direccion');
        $sheet->setCellValue('I3', 'Domicilio');
        $sheet->setCellValue('J3', 'RFC');
        $sheet->setCellValue('K3', 'Correo');
        $sheet->setCellValue('L3', 'CIF');
        $sheet->setCellValue('M3', 'Banco');
        $sheet->setCellValue('N3', 'Cuenta');
        $sheet->setCellValue('O3', 'Clabe');
        $sheet->setCellValue('P3', 'Estado Cuenta');

        // Establecer el color de fondo de los encabezados
        $sheet->getStyle('A3:P3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF99C6F1');

        // Centrar los encabezados
        $sheet->getStyle('A3:P3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a los encabezados
        $sheet->getStyle('A3:P3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

        // Ajustar el tamaño de las columnas al contenido
        foreach (range('A', 'P') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Escribir los datos de los proveedores en el archivo Excel
        // Comienza a escribir los datos desde la fila 4
        $rowNumber = 4;

        // Para cada proveedor que obtenga la consulta
        foreach ($proveedores as $proveedor) {

            $sheet->setCellValue('A' . $rowNumber, $proveedor->id_proveedor);
            $sheet->setCellValue('B' . $rowNumber, $proveedor->nombre);
            $sheet->setCellValue('C' . $rowNumber, $proveedor->sobrenombre);
            $sheet->setCellValue('D' . $rowNumber, $proveedor->regimen_fiscal);
            $sheet->setCellValue('E' . $rowNumber, $proveedor->telefono);
            $sheet->setCellValue('F' . $rowNumber, $proveedor->telefono2);
            $sheet->setCellValue('G' . $rowNumber, $proveedor->contacto);
            $sheet->setCellValue('H' . $rowNumber, $proveedor->direccion);
            $sheet->setCellValue('I' . $rowNumber, $proveedor->domicilio);
            $sheet->setCellValue('J' . $rowNumber, $proveedor->rfc);
            $sheet->setCellValue('K' . $rowNumber, $proveedor->correo);
            if (empty($proveedor->CIF)) {
                $sheet->setCellValue('L' . $rowNumber, $proveedor->CIF);
            } else {
                // Detectar protocolo (http o https)
                $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                
                // Construir URL base del sitio
                $rutaBase = $protocolo . '://' . $_SERVER['HTTP_HOST'] . '/';
                
                // Construir URL del archivo CIF
                $rutaArchivoCIF = $rutaBase . ltrim($proveedor->CIF, '/');
            
                // Insertar la fórmula con hipervínculo en Excel
                $sheet->setCellValue('L' . $rowNumber, '=HYPERLINK("' . $rutaArchivoCIF . '", "Ver archivo")');
            }
            
            $sheet->setCellValue('M' . $rowNumber, $proveedor->banco);
            $sheet->setCellValue('N' . $rowNumber, $proveedor->n_cuenta);
            $sheet->setCellValue('O' . $rowNumber, $proveedor->n_cuenta_clabe);
            
            if (empty($proveedor->estado_cuenta)) {
                $sheet->setCellValue('P' . $rowNumber, $proveedor->estado_cuenta);
            } else {
                // Construir URL del archivo Estado de Cuenta
                $rutaArchivoEC = $rutaBase . ltrim($proveedor->estado_cuenta, '/');
                
                // Insertar la fórmula con hipervínculo en Excel
                $sheet->setCellValue('P' . $rowNumber, '=HYPERLINK("' . $rutaArchivoEC . '", "Ver archivo")');
            }            

            // Centrar las celdas de la fila actual
            $sheet->getStyle('A' . $rowNumber . ':P' . $rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Añadir bordes normales a las celdas de los datos
            $sheet->getStyle('A' . $rowNumber . ':P' . $rowNumber)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Aumenta en 1 la fila.
            $rowNumber++;
        }
        
        // Aplicar filtros a la tabla de datos
        $sheet->setAutoFilter('A3:P3');

        // Configurar el archivo para descarga
        $fileName = 'reporte_proveedores_' . Carbon::now()->format('Ymd_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        //Crear una respuesta de transmisión para la descarga
        $response = new StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        });

        // Configurar los encabezados de la respuesta
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $fileName . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        // Enviar la respuesta
        return $response;
    }
    /*
      TODO Genera y sirve un reporte en formato PDF de las unidades activas al momento.

      Este método maneja la solicitud de generación de reportes de unidades. Recupera las unidades activas
      correspondientes de la base de datos. Finalmente, el método genera un archivo en PDF utilizando estos datos,
      que luego es enviado directamente al cliente para su descarga.

      Sirve un PDF generado directamente a la vista para que el usuario pueda visualizarlo y descargarlo.
    */
    public function reporteUnidades(){

        // Consultar las unidades que se encuentren activas al momento
        $unidades = Unidades::where('estatus',1)
        ->orderBY('n_de_permiso','asc')
        ->where('id_unidad','!=',1)
        ->where('id_unidad','!=',2)
        ->get();

        // Incluir el archivo Requisicion.php y pasar la ruta del archivo como una variable
        ob_start();
        include(public_path('/pdf/TCPDF-main/examples/Reporte_Unidades.php'));
        $pdfContent = ob_get_clean();
        header('Content-Type: application/pdf');
        echo $pdfContent;
    }
}
