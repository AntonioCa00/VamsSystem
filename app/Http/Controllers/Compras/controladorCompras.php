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
//-------PHPOFFICE---------
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
//-------DATABASE---------
use DB;
//-------FECHAS-----------
use Carbon\Carbon;

class controladorCompras extends Controller
{
    /*
      Recopila datos para la visualización de informes de gestión en el área correspondiente.

      Este método se encarga de compilar datos detallados de operaciones de compra por mes y totales anuales,
      así como el conteo de requisiciones completas y pendientes. Utiliza la clase Orden_compras para sumar los costos totales
      de las compras realizadas en cada mes del año actual y calcula los totales de compras para el mes y año en curso.
      Además, cuenta las requisiciones en estado 'Comprado' y las requisiciones pendientes que no están 'Compradas' ni 'Rechazadas'.

      Retorna la vista 'Admin.index' con los datos compilados para informes de gestión.
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
        ->where('requisiciones.estado','!=', 'Finalizado')
        ->where('requisiciones.estado','!=','Rechazado')
        ->count();

        // Pasar los datos a la vista
        return view("Admin.index",[
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

    //VISTAS DE LAS TABLAS
    /*
      Recupera y muestra todas las refacciones activas en el almacen.

      Este método consulta la base de datos para obtener un listado de todas las refacciones que tienen un estatus '1',
      indicando que están activas. La intención es proporcionar una visión general de las refacciones disponibles en el
      almacen para su gestión o asignación a tareas específicas.

      Retorna la vista 'Admin.refaccion', pasando el listado de refacciones activas para su visualización.
    */
    public function tableRefaccion(){
        // Recupera todas las refacciones del almacen que están activas (estatus = 1)
        $refacciones = Almacen::get()->where("estatus",1);

        // Carga y muestra la vista con el listado de refacciones activas
        return view('Admin.refaccion',compact('refacciones'));
    }

    //! ESTA FUNCION NO ESTA SIRVIENDO ACTUALMENTE
    public function tableEntradas(){
        $entradas = Entradas::select('entradas.id_entrada','requisiciones.pdf as reqPDF','orden_compras.pdf as ordPDF','entradas.factura','entradas.created_at')
        ->join('orden_compras','entradas.orden_id','=','orden_compras.id_orden')
        ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
        ->get();
        return view('Admin.entradas',compact('entradas'));
    }

    /*
      Recupera y muestra un listado de unidades activas, excluyendo una unidad específica.

      Este método consulta la base de datos para obtener un listado de todas las unidades que están marcadas como activas
      (estatus '1'), excluyendo la unidad con ID '1' por razones específicas de negocio o de la aplicación. Además, las unidades
      activas se ordenan en orden ascendente por su ID para facilitar su visualización y gestión.

      Retorna la vista 'Admin.unidad', pasando el listado de unidades activas para su visualización.
    */
    public function tableUnidad(){
        // Recupera las unidades activas, excluyendo la unidad con ID '1' y ordenándolas por ID de manera ascendente
        $unidades = Unidades::where('estatus','1')
        ->where('id_unidad','!=','1')
        ->where('estado','Activo')->orderBy('id_unidad','asc')
        ->get();

        // Carga y muestra la vista con el listado de unidades activas
        return view('Admin.unidad',compact('unidades'));
    }

    /*
      Muestra la vista para la creación de una nueva unidad.

      Este método se encarga de cargar y presentar la vista que contiene el formulario utilizado para la creación
      de nuevas unidades dentro del sistema. La vista proporcionará los campos necesarios para capturar la información
      esencial de la nueva unidad.

      Retorna la vista 'Admin.crearUnidad', que contiene el formulario para la creación de una nueva unidad.
    */
    public function CreateUnidad(){
        // Cargar y mostrar la vista con el formulario de creación de unidad
        return view('Admin.crearUnidad');
    }

    /*
      Inserta una nueva unidad en la base de datos con la información proporcionada a través de un formulario.

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
        "created_at"=>Carbon::now(),
        "updated_at"=>Carbon::now()
        ]);

        // Redirige al usuario a la vista de unidades
        return redirect()->route('unidades')->with('regis','regis');
    }

    /*
      Muestra la vista para editar los detalles de una unidad específica.

      Este método se encarga de recuperar los detalles de una unidad específica, identificada por su ID, de la base de datos.
      La recuperación de esta información es crucial para pre-rellenar el formulario de edición en la vista con los datos actuales
      de la unidad, permitiendo así que los administradores o los usuarios con los permisos adecuados realicen cambios en la información
      de la unidad como tipo, estado, año, marca, modelo, características, número de serie, y número de permiso.

      @param  int  $id  El ID de la unidad cuyos detalles se van a editar.

      Retorna la vista 'Admin.editarUnidad', pasando los detalles de la unidad específica para su edición.
    */
    public function editUnidad($id){
        // Recupera los detalles de la unidad específica por su ID
        $unidad = Unidades::where('id_unidad',$id)->first();

        // Carga y muestra la vista con el formulario de edición de unidad, pasando los detalles de la unidad
        return view('Admin.editarUnidad',compact('unidad'));
    }

    /*
      Actualiza los detalles de una unidad específica en la base de datos con la información proporcionada por el formulario.

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
            "updated_at"=>Carbon::now()
        ]);

        // Redirige al usuario a la lista de unidades con un mensaje de éxito
        return redirect()->route('unidades')->with('update','update');
    }

    /*
      Desactiva una unidad específica marcándola como inactiva en la base de datos.

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
      Marca una unidad específica como inactiva en la base de datos.

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
      Recupera y muestra todas las unidades inactivas para su potencial activación.

      Este método consulta la base de datos para obtener un listado de todas las unidades que actualmente están marcadas
      como "Inactivo". La intención es proporcionar a los administradores una visión general de las unidades que no están
      en uso activo pero que pueden ser reactivadas según sea necesario.

      Retorna la vista 'Admin.activaUnidad', pasando el listado de unidades inactivas para su visualización y potencial activación.
    */
    public function activarUnidad(){
        // Recupera las unidades marcadas como inactivas
        $unidades = Unidades::where("estado",'Inactivo')->get();

        // Carga y muestra la vista con el listado de unidades inactivas
        return view('Admin.activaUnidad',compact('unidades'));
    }

    /*
      Reactiva una unidad específica cambiando su estado a "Activo".

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
        return view('Admin.salidas',compact('salidas'));
    }

    /*
      Recupera y muestra un listado de solicitudes que cumplen con ciertos criterios de estado, junto con información relevante.

      Este método realiza consultas complejas a la base de datos para obtener un listado de solicitudes que están en los estados
      'Aprobado', 'Cotizado', 'Validado', o 'Comprado'. Además de la información básica de la solicitud, se calcula y muestra la
      cantidad de artículos asociados a cada solicitud que tienen un estatus '0'. También se recupera información agrupada por
      departamento y por estados específicos de las solicitudes para posibles filtros o visualizaciones en la vista.

      Retorna la vista 'Admin.solicitudes', pasando el listado de solicitudes, departamentos, y estados de solicitudes para su visualización.
    */
    public function tableSolicitud(){
        // Consulta de solicitudes que cumplen con los criterios de estado especificados y cálculo de cantidad de artículos inactivos.
        $solicitudes = Requisiciones::select(['requisiciones.id_requisicion','users.nombres','requisiciones.unidad_id','requisiciones.pdf','requisiciones.estado','requisiciones.created_at as fecha_creacion',DB::raw('(SELECT COUNT(*) FROM articulos WHERE articulos.requisicion_id = requisiciones.id_requisicion AND articulos.estatus = 0) as cantidad_articulos')])
        ->leftJoin('comentarios','requisiciones.id_requisicion','=','comentarios.requisicion_id')
        ->leftJoin('users as us','us.id','=','comentarios.usuario_id')
        ->select('requisiciones.id_requisicion','users.nombres','requisiciones.unidad_id','requisiciones.estado','requisiciones.created_at','requisiciones.pdf','requisiciones.created_at as fecha_creacion', DB::raw('MAX(comentarios.detalles) as detalles'),'us.rol',DB::raw('MAX(comentarios.created_at) as fechaCom'))
        ->join('users', 'requisiciones.usuario_id', '=', 'users.id')
        ->orderBy('requisiciones.created_at','desc')
        ->groupBy('requisiciones.id_requisicion')
        ->where('requisiciones.estado','!=','Finalizado')
        ->get();

        // Recuperación de información agrupada por departamento
        $departamentos = Requisiciones::select('departamento')
        ->join('users','requisiciones.usuario_id','users.id')
        ->groupBy('departamento')->get();

        // Recuperación de estados de solicitudes para filtros adicionales
        $estatus = Requisiciones::select('estado')
        ->where(function($query) {
            $query->where('estado', '!=', 'Solicitado')
                ->Where('estado', '!=', 'Finalizado')
                ->Where('estado', '!=', 'Pre Validado');
        })
        ->groupBy('estado')
        ->orderBy('estado','asc')->get();


        // Redirige al usuario a la lista de solicitudes con los datos recuperados
        return view('Admin.solicitudes',compact('solicitudes','departamentos','estatus'));
    }

    /*
      Filtra y muestra un listado de solicitudes basado en criterios específicos como el departamento o el estado.

      Este método maneja la lógica para filtrar las solicitudes de acuerdo con el criterio especificado por el usuario,
      ya sea 'departamento' o 'estado'. Dependiendo del criterio seleccionado, realiza una consulta a la base de datos
      para recuperar las solicitudes que coinciden con el valor de filtro proporcionado en la petición HTTP. Además de
      filtrar las solicitudes, también recupera y organiza información adicional sobre los departamentos y los estados de
      las solicitudes para posibles filtros o visualizaciones adicionales en la vista.

      @param  string $filt El criterio de filtro especificado ('departamento' o 'estado').

      Retorna la vista 'Admin.solicitudes', pasando el listado filtrado de solicitudes, departamentos y estados para su visualización.
    */
    public function filtrarSolicitudes(Request $req,$filt){

        // Lógica para filtrar las solicitudes basada en el criterio especificado
        switch ($filt){
            case "departamento":
                // Filtrar por departamento

                // Consulta de solicitudes que cumplen con los criterios de estado especificados y cálculo de cantidad de artículos inactivos.
                $solicitudes = Requisiciones::select(['requisiciones.id_requisicion','users.nombres','requisiciones.unidad_id','requisiciones.pdf','requisiciones.estado','requisiciones.created_at as fecha_creacion',DB::raw('(SELECT COUNT(*) FROM articulos WHERE articulos.requisicion_id = requisiciones.id_requisicion AND articulos.estatus = 0) as cantidad_articulos')])
                ->join('users', 'requisiciones.usuario_id', '=', 'users.id')
                ->where(function($query) {
                    $query->where('requisiciones.estado', '=', 'Aprobado')
                        ->orWhere('requisiciones.estado', '=', 'Cotizado')
                        ->orWhere('requisiciones.estado', '=', 'Validado')
                        ->orWhere('requisiciones.estado', '=', 'Comprado');
                })
                //Esta codición se encarga de mostrar solo las que cumplan con el filtro
                ->where('users.departamento','=',$req->filtro)
                ->orderBy('requisiciones.created_at','desc')
                ->get();

                // Recuperación de información agrupada por departamento
                $departamentos = Requisiciones::select('departamento')
                ->join('users','requisiciones.usuario_id','users.id')
                ->groupBy('departamento')->get();

                // Recuperación de estados de solicitudes para filtros adicionales
                $estatus = Requisiciones::select('estado')
                ->where(function($query) {
                    $query->where('estado', '!=', 'Solicitado')
                        ->Where('estado', '!=', 'Finalizado')
                        ->Where('estado', '!=', 'Pre Validado');
                })
                ->groupBy('estado')
                ->orderBy('estado','asc')->get();
            break;
            case "estado":
                // Filtrar por estado

                // Consulta de solicitudes que cumplen con los criterios de estado especificados y cálculo de cantidad de artículos inactivos.
                $solicitudes = Requisiciones::select(['requisiciones.id_requisicion','users.nombres','requisiciones.unidad_id','requisiciones.pdf','requisiciones.estado','requisiciones.created_at as fecha_creacion',DB::raw('(SELECT COUNT(*) FROM articulos WHERE articulos.requisicion_id = requisiciones.id_requisicion AND articulos.estatus = 0) as cantidad_articulos')])
                ->join('users', 'requisiciones.usuario_id', '=', 'users.id')
                ->where(function($query) {
                    $query->where('requisiciones.estado', '=', 'Aprobado')
                        ->orWhere('requisiciones.estado', '=', 'Cotizado')
                        ->orWhere('requisiciones.estado', '=', 'Validado')
                        ->orWhere('requisiciones.estado', '=', 'Comprado');
                })
                //Esta codición se encarga de mostrar solo las que cumplan con el filtro
                ->where('requisiciones.estado','=',$req->filtro)
                ->orderBy('requisiciones.created_at','desc')
                ->get();

                // Recuperación de información agrupada por departamento
                $departamentos = Requisiciones::select('departamento')
                ->join('users','requisiciones.usuario_id','users.id')
                ->groupBy('departamento')->get();

                // Recuperación de estados de solicitudes para filtros adicionales
                $estatus = Requisiciones::select('estado')
                ->where(function($query) {
                    $query->where('estado', '!=', 'Solicitado')
                        ->Where('estado', '!=', 'Finalizado')
                        ->Where('estado', '!=', 'Pre Validado');
                })
                ->groupBy('estado')
                ->orderBy('estado','asc')->get();
            break;
        }

        // Redirige al usuario a la lista de solicitudes con los datos recuperados
        return view('Admin.solicitudes',compact('solicitudes','departamentos','estatus'));
    }

    /*
      Valida una solicitud específica cambiando su estado a "Validado" y registra la acción en un log.

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
      Carga la vista para la creación de nuevas cotizaciones asociadas a una solicitud específica.

      Este método recupera todas las cotizaciones activas (estatus '1') asociadas a una solicitud específica,
      identificada por su ID, incluyendo los archivos PDF tanto de la solicitud original como de las cotizaciones
      existentes.

      @param  int  $id El ID de la solicitud para la cual se crearán nuevas cotizaciones.

      Retorna la vista 'Admin.crearCotizacion', pasando las cotizaciones existentes y el ID de la solicitud para su visualización.
    */
    public function createCotiza($id){
        // Recupera las cotizaciones activas asociadas a la solicitud específica
        $cotizaciones = Cotizaciones::select('cotizaciones.id_cotizacion','requisiciones.id_requisicion','requisiciones.pdf as reqPDF','cotizaciones.pdf as cotPDF')
        ->join('requisiciones','cotizaciones.requisicion_id', '=', 'requisiciones.id_requisicion')
        ->where('cotizaciones.requisicion_id', $id)->where('cotizaciones.estatus','1')->get();

        // Carga y muestra la vista con los datos de las cotizaciones existentes
        return view('Admin.crearCotizacion',compact('cotizaciones','id'));
    }

    /*
      Procesa y almacena una nueva cotización para una requisición específica.

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

            // Manejo del caso en que no se sube un archivo válido
            return back()->with('error', 'No se ha seleccionado ningún archivo.');
        }
    }

    /*
      Elimina una cotización específica de la base de datos.

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
      Recupera y muestra todos los proveedores activos en la base de datos.

      Este método consulta la base de datos para obtener un listado de todos los proveedores que están marcados como activos
      mediante el estatus '1'. La intención es proporcionar a los administradores y usuarios autorizados una visión general
      de los proveedores disponibles para gestiones de compras, contrataciones o cualquier otro tipo de interacción comercial.

      Retorna la vista 'Admin.proveedores', pasando el listado de proveedores activos para su visualización.
    */
    public function tableProveedor(){
        // Recupera todos los proveedores activos (estatus = 1)
        $proveedores = Proveedores::where('estatus',1)->get();

        // Carga y muestra la vista con el listado de proveedores activos
        return view('Admin.proveedores',compact('proveedores'));
    }

    /*
      Muestra la vista para la creación de un nuevo proveedor.

      Este método se encarga de cargar y presentar la vista que contiene el formulario utilizado para la creación
      de nuevos proveedores dentro del sistema. La vista proporcionará los campos necesarios para capturar la información
      esencial del nuevo proveedor, como su nombre, teléfono, correo electrónico, dirección, y cualquier otro dato relevante
      según los requisitos específicos de la aplicación. Este método facilita la tarea de administración de proveedores,
      permitiendo a los administradores o usuarios con los permisos adecuados añadir nuevos proveedores al sistema de manera
      sencilla y eficiente.

      Retorna la vista 'Admin.crearProveedor', que contiene el formulario para la creación de un nuevo proveedor.
    */
    public function createProveedor(){
        // Cargar y mostrar la vista con el formulario de creación de proveedor
        return view('Admin.crearProveedor');
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
        //Valida que exista un archivo CIF, que se haya cargado el comprante de pago y el numero de cuenta clabe
        if (empty($req->file('archivo_CIF')) && (empty($req->banco) || empty($req->n_cuenta) || empty($req->n_cuenta_clabe))){
            //En caso de que no se carguen se inserta el proveedor sin esos archivos, unicamente con los datos obligatorios
            Proveedores::create([
                "nombre"=>$req->input('nombre'),
                "telefono"=>$req->input('telefono'),
                "telefono2"=>$req->input('telefono2'),
                "contacto"=>$req->input('contacto'),
                "direccion"=>$req->input('direccion'),
                "domicilio"=>$req->input('domicilio'),
                "rfc"=>$req->input('rfc'),
                "correo"=>$req->input('correo'),
                "CIF"=>null,
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);
        } else {
            //En caso de que se hayan cargado los archivos, estos se guardan en sus respectivas carpetas y se relacionan al proveedor con el nombre
            //Procesamiento y almacenamiento de los archivo CIF
            $nombreEmpresa = str_replace(' ', '', $req->nombre); // Elimina todos los espacios en blanco
            $archivo = $req->file('archivo_CIF');
            $nombreArchivo = 'CIF_' . $nombreEmpresa . '.' . $archivo->getClientOriginalExtension();

            $archivo->storeAs('CIF', $nombreArchivo, 'public');
            $CIF_pdf = 'CIF/' . $nombreArchivo;

            //Validacion de datos bancarios para validar archivos de cuenta
            if(!empty($req->banco) || !empty($req->n_cuenta) || !empty($req->n_cuenta_clabe)) {
                // Solo validar 'archivo_estadoCuenta' si se cumplen las condiciones
                $req->validate([
                    'archivo_estadoCuenta' => 'required|file|mimes:pdf',
                ]);

                //Procesamiento y almacenamiento del archivo estado de cuenta
                $archivo = $req->file('archivo_estadoCuenta');
                $nombreArchivo = 'estadoCuenta_' . $nombreEmpresa . '.' . $archivo->getClientOriginalExtension();
                $archivo->storeAs('Estado Cuenta', $nombreArchivo, 'public');
                $estadoCuenta_pdf = 'Estado Cuenta/' . $nombreArchivo;

                // Creación del registro de proveedor en la base de datos
                Proveedores::create([
                    "nombre"=>$req->input('nombre'),
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
                    "estado_cuenta"=>$estadoCuenta_pdf,
                    "estatus"=>"1",
                    "created_at"=>Carbon::now(),
                    "updated_at"=>Carbon::now()
                    ]);
            }else{
                //En caso de no tener datos bancarios crea el registro son esos datos
                Proveedores::create([
                    "nombre"=>$req->input('nombre'),
                    "telefono"=>$req->input('telefono'),
                    "telefono2"=>$req->input('telefono2'),
                    "contacto"=>$req->input('contacto'),
                    "direccion"=>$req->input('direccion'),
                    "domicilio"=>$req->input('domicilio'),
                    "rfc"=>$req->input('rfc'),
                    "correo"=>$req->input('correo'),
                    "CIF"=>$CIF_pdf,
                    "created_at"=>Carbon::now(),
                    "updated_at"=>Carbon::now()
                ]);
            }
        }

        // Redirección con mensaje de éxito
        return redirect('proveedores/Compras')->with('insert','insert');
    }

    /*
      Muestra la vista para editar los detalles de un proveedor específico.

      Este método se encarga de recuperar los detalles de un proveedor específico, identificado por su ID, de la base de datos.
      La recuperación de esta información es crucial para pre-rellenar el formulario de edición en la vista con los datos actuales
      del proveedor, permitiendo así que los administradores o los usuarios con los permisos adecuados realicen cambios en la información
      del proveedor como nombre, contacto, dirección, datos bancarios, y cualquier otro dato relevante.

      @param  int  $id  El ID del proveedor cuyos detalles se van a editar.

      Retorna la vista 'Admin.editarProveedor', pasando los detalles del proveedor específico para su edición.
    */
    public function editProveedor($id){
        // Recupera los detalles del proveedor específico por su ID
        $proveedor = Proveedores::where('id_proveedor',$id)->first();

        // Carga y muestra la vista con el formulario de edición de proveedor, pasando los detalles del proveedor
        return view('Admin.editarProveedor',compact('proveedor'));
    }

    /*
     Actualiza los detalles de un proveedor existente y gestiona la actualización de sus documentos.

      Este método permite actualizar la información básica de un proveedor, como el nombre, contacto, dirección, RFC,
      y detalles bancarios. Además, maneja la validación, actualización y almacenamiento de documentos críticos como el
      CIF y el estado de cuenta bancario. Si hay cambios en la información bancaria o se suben nuevos documentos, el sistema
      valida y almacena estos archivos, reemplazando los anteriores si existen.

      @param  int  $id  El ID del proveedor que se va a actualizar.

      Redirige al usuario a la lista de proveedores con una sesión flash que indica que el proveedor ha sido actualizado exitosamente.
    */
    public function updateProveedor(Request $req,$id){
        //Recupera los datos actuales del proveedor
        $proveedor = Proveedores::where('id_proveedor',$id)->first();

        //Valida si los datos bancarios han cambiado respecto a los guardados por ultima vez
        if(($req->banco != $proveedor->banco || $req->n_cuenta != $proveedor->n_cuenta || $req->n_cuenta_banco != $proveedor->n_cuenta_banco) || $req->hasFile('archivo_estadoCuenta') && $req->file('archivo_estadoCuenta')->isValid()){
            //En caso de ser así, hace obligatorio subir un estado de cuenta nuevo
            $req->validate([
                'archivo_estadoCuenta' => 'required|file|mimes:pdf',
            ]);

            //Eliminacion del archivo de estado de cuenta para evitar duplicados
            if (!empty($proveedor->estado_cuenta)) {
                $fileToDelete = public_path($proveedor->estado_cuenta);
                // Luego, verifica si el archivo realmente existe antes de intentar eliminarlo.
                if (file_exists($fileToDelete)) {
                    unlink($fileToDelete);
                }
            }

            //Procesar y almacenar el archivo de estado de cuenta
            $nombreEmpresa = str_replace(' ', '', $req->nombre); // Elimina todos los espacios en blanco
            $archivo = $req->file('archivo_estadoCuenta');
            $nombreArchivo = 'estadoCuenta_' . $nombreEmpresa . '.' . $archivo->getClientOriginalExtension();
            $archivo->storeAs('Estado Cuenta', $nombreArchivo, 'public');
            $estadoCuenta_pdf = 'Estado Cuenta/' . $nombreArchivo;

            //Validar que se haya modificado el archivo de CIF
            if(empty($req->archivo_CIF)){

                //Insertar al proveedor en la base de datos sin modificar CIF pero con datos bancarios nuevos
                Proveedores::where('id_proveedor',$id)->update([
                    "nombre"=>$req->input('nombre'),
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
            }else{
                //Si se cargó un documento elimina el archivo existente para evitar duplicados
                if (!empty($proveedor->CIF)) {
                    $fileToDelete = public_path($proveedor->CIF);
                    // Luego, verifica si el archivo realmente existe antes de intentar eliminarlo.
                    if (file_exists($fileToDelete)) {
                        unlink($fileToDelete);
                    }
                }

                //Procesar y almacenar el CIF
                $nombreEmpresa = str_replace(' ', '', $req->nombre); // Elimina todos los espacios en blanco
                $archivo = $req->file('archivo_CIF');
                $nombreArchivo = 'CIF_' . $nombreEmpresa . '.' . $archivo->getClientOriginalExtension();

                $archivo->storeAs('CIF', $nombreArchivo, 'public');
                $CIF_pdf = 'CIF/' . $nombreArchivo;

                //Insertar el archivo CIF y modificaciones junto con los datos bancarios modificados
                Proveedores::where('id_proveedor',$id)->update([
                    "nombre"=>$req->input('nombre'),
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
            }
        } else {
            //Datos bancarios no actualizados
            if(empty($req->archivo_CIF)){
                //Si no se modifia el CIF, actualiza los datos unicamente que son obligatorios
                Proveedores::where('id_proveedor',$id)->update([
                    "nombre"=>$req->input('nombre'),
                    "telefono"=>$req->input('telefono'),
                    "telefono2"=>$req->input('telefono2'),
                    "contacto"=>$req->input('contacto'),
                    "direccion"=>$req->input('direccion'),
                    "domicilio"=>$req->input('domicilio'),
                    "rfc"=>$req->input('rfc'),
                    "correo"=>$req->input('correo'),
                ]);
            }else{

                //Si se cargó un documento elimina el archivo existente para evitar duplicados
                if (!empty($proveedor->CIF)) {
                    $fileToDelete = public_path($proveedor->CIF);
                    // Luego, verifica si el archivo realmente existe antes de intentar eliminarlo.
                    if (file_exists($fileToDelete)) {
                        unlink($fileToDelete);
                    }
                }

                //Procesar y almacenar el CIF
                $nombreEmpresa = str_replace(' ', '', $req->nombre); // Elimina todos los espacios en blanco
                $archivo = $req->file('archivo_CIF');
                $nombreArchivo = 'CIF_' . $nombreEmpresa . '.' . $archivo->getClientOriginalExtension();

                $archivo->storeAs('CIF', $nombreArchivo, 'public');
                $CIF_pdf = 'CIF/' . $nombreArchivo;

                //Insertar el archivo CIF y modificaciones
                Proveedores::where('id_proveedor',$id)->update([
                    "nombre"=>$req->input('nombre'),
                    "telefono"=>$req->input('telefono'),
                    "telefono2"=>$req->input('telefono2'),
                    "contacto"=>$req->input('contacto'),
                    "direccion"=>$req->input('direccion'),
                    "domicilio"=>$req->input('domicilio'),
                    "rfc"=>$req->input('rfc'),
                    "correo"=>$req->input('correo'),
                    "CIF"=>$CIF_pdf
                ]);
            }
        }

        //Redirecciona al listado de proveedores con mensaje de exito
        return redirect('proveedores/Compras')->with('update','update');
    }

    /*
      Desactiva un proveedor específico marcándolo como inactivo en la base de datos.

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

    /*
      Prepara y muestra la información necesaria para generar una orden de compra.

      Este método recupera la cotización seleccionada para una requisición específica, identificada por su ID,
      incluyendo los documentos PDF asociados tanto a la cotización como a la requisición. También recopila los
      artículos asociados a la requisición que están pendientes (estatus 0) y un listado de proveedores activos
      (estatus 1), facilitando la selección de un proveedor en el proceso de creación de la orden de compra.


      @param  int  $id  El ID de la requisición para la cual se preparará la orden de compra.

      Retorna la vista 'Admin.ordenCompra', pasando la cotización seleccionada, los proveedores activos, el ID de la requisición,
      y los artículos pendientes para su visualización y gestión.
    */
    public function ordenCompra($id){
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
        return view('Admin.ordenCompra',compact('cotizacion','proveedores','id','articulos'));
    }

    /*
      Crea una orden de compra basada en una cotización específica y actualiza el estado de los artículos y la requisición correspondiente.

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
            $datos = Requisiciones::select('unidad_id')->where('id_requisicion',$rid)->first();

            //Si existe unidad guarda todos sus datos para mostrarlos en pdf
            if(!empty($datos->unidad_id)){
                $unidad = Unidades::where('id_unidad',$datos->unidad_id)->first();
            }

            //Variable que contiene los articulos seleccionados en el formulario.
            $articulosSeleccionados = $req->input('articulos_seleccionados');

            //Filtrar solo los articulos seleccionados
            $articulosFiltrados = array_filter($articulos, function($articulo) use ($articulosSeleccionados){
                return in_array($articulo['id'],$articulosSeleccionados);
            });

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

            //Por cada articulo modifica las cantidades y precios.
            foreach ($articulosFiltrados as $id => $articulo) {
                // Aquí puedes acceder a cada elemento del array $articulo
                $articuloUnico = Articulos::where('id',$id)->first();
                $cantidad_total = $articuloUnico->cantidad - $articulo['cantidad'];

                // Determina el estatus antes de la actualización
                $estatus = $cantidad_total == 0 ? 1 : 0;
                Articulos::where('id', $id)->update([
                    'cantidad' => $cantidad_total,
                    'unidad' => $articulo['unidad'],
                    'descripcion' => $articulo['descripcion'],
                    'precio_unitario' => $articulo['precio_unitario'],
                    'ult_compra'=>$articulo['cantidad'],
                    'estatus' => $estatus, // Usa la variable $estatus aquí
                    'orden_id'=>$idnuevaorden
                ]);
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
      Recupera y muestra una lista de todas las órdenes de compra activas.

      Este método consulta la base de datos para obtener información detallada sobre cada orden de compra, incluyendo
      el ID de la orden, detalles asociados de la requisición, el estado de la requisición, los nombres de los administradores
      que gestionaron las órdenes, archivos PDF relacionados con las cotizaciones y las órdenes, detalles del proveedor,
      costo total, estado del pago y otros datos relevantes. Filtra cualquier orden relacionada con requisiciones que hayan
      sido rechazadas, centrándose en aquellas que están activas o en proceso.

      Devuelve la vista 'Admin.ordenesCompras', pasando la lista de órdenes para su visualización.
    */
    public function ordenesCompras(){
        /* Obtención de información detallada para cada orden en donde se analizan la evolucion de la peticiónn
           desde requisición hasta orden de compra*/
        $ordenes = Orden_compras::select('orden_compras.id_orden','requisiciones.id_requisicion','requisiciones.estado','users.nombres','cotizaciones.pdf as cotPDF','proveedores.nombre as proveedor','orden_compras.costo_total','orden_compras.estado as estadoComp','orden_compras.pdf as ordPDF','orden_compras.comprobante_pago','orden_compras.estado' ,'orden_compras.created_at')
        ->join('users','orden_compras.admin_id','=','users.id')
        ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
        ->join('proveedores','orden_compras.proveedor_id','=','proveedores.id_proveedor')
        //Se excluyen las que se encuentren rechazadas.
        ->where('requisiciones.estado','!=','Rechazado')
        ->orderBy('orden_compras.created_at','desc')
        ->get();
        //Muentra la vista con la variable que contiene las ordenes de compra
        return view ('Admin.ordenesCompras',compact('ordenes'));
    }

    /*
      Elimina una orden de compra específica y revierte los cambios en los artículos y la requisición asociados.

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
      Finaliza una requisición específica actualizando su estado a "Finalizado".

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
      Recupera y muestra información detallada sobre todos los pagos fijos, junto con listados de servicios y proveedores activos.

      Este método consulta la base de datos para obtener un listado completo de todos los pagos fijos registrados en el sistema,
      incluyendo detalles como el ID del pago, comprobante de pago, y la información relacionada del servicio y del proveedor.
      Se realiza una unión con las tablas de servicios y proveedores para enriquecer los datos de cada pago con información
      relevante como el nombre del servicio y del proveedor. Además, se recuperan listados de todos los servicios y proveedores
      activos que se utilizan para filtros o selecciones en la interfaz de usuario.

      Devuelve la vista 'Admin.pagos', pasando los pagos fijos, servicios y proveedores para su visualización y gestión.
    */
    public function pagosFijos() {
        // Obtener los pagos fijos con detalles completos de servicios y proveedores
        $pagos = Pagos_Fijos::select('pagos_fijos.*','servicios.id_servicio','servicios.nombre_servicio','proveedores.nombre','pagos_fijos.comprobante_pago')
        ->join('servicios','pagos_fijos.servicio_id','servicios.id_servicio')
        ->join('proveedores','servicios.proveedor_id','proveedores.id_proveedor')
        ->orderBy('id_pago','desc')
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
        return view('Admin.pagos',compact('pagos','servicios','proveedores'));
    }

    /*
      Prepara y muestra datos necesarios para la generación de reportes en la interfaz administrativa.

      Este método se encarga de mostrar la vista que contiene los formularios para solicitar los reportes.

      Devuelve la vista 'Admin.reportes'.
    */
    public function reportes() {
        //Retorna la vista de reportes.
        return view('Admin.reportes');
    }

    /*
      Genera y sirve un reporte en archivo excel de requisiciones basado en el intervalo de tiempo especificado.

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
        $sheet->setCellValue('A5', 'Folio');
        $sheet->setCellValue('B5', 'Nombre Usuario');
        $sheet->setCellValue('C5', 'Departamento');
        $sheet->setCellValue('D5', 'Fecha Creación');
        $sheet->setCellValue('E5', 'Unidad');
        $sheet->setCellValue('F5', 'Estado');

        // Establecer el color de fondo de los encabezados
        $sheet->getStyle('A5:F5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF99C6F1');

        // Centrar los encabezados
        $sheet->getStyle('A5:F5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a los encabezados
        $sheet->getStyle('A5:F5')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

         // Ajustar el tamaño de las columnas al contenido
         foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Escribir los datos de las requisiciones en el archivo Excel
        // Comienza a escribir los datos desde la fila 6
        $rowNumber = 6;

        // Para cada requsicion que obtenga en la consulta
        foreach ($datosRequisicion as $requisicion) {

            // Concatena el nombre del solicitante y su apellido paterno
            $nombreCompleto = $requisicion->nombres . ' ' . $requisicion->apellidoP;
            $fechaformato = date('d/m/Y', strtotime($requisicion->created_at));
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
            $sheet->setCellValue('A' . $rowNumber, $requisicion->id_requisicion);
            $sheet->setCellValue('B' . $rowNumber, $nombreCompleto);
            $sheet->setCellValue('C' . $rowNumber, $requisicion->departamento);
            $sheet->setCellValue('D' . $rowNumber, $fechaformato);
            $sheet->setCellValue('E' . $rowNumber, $unidad);
            $sheet->setCellValue('F' . $rowNumber, $requisicion->estado);

            // Centrar las celdas de la fila actual
            $sheet->getStyle('A' . $rowNumber . ':F' . $rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Añadir bordes normales a las celdas de los datos
            $sheet->getStyle('A' . $rowNumber . ':F' . $rowNumber)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            $rowNumber++;
        }

        // Aplicar filtros a la tabla de datos
        $sheet->setAutoFilter('A5:F5');

        // Configurar el archivo para descarga
        $fileName = 'reporte_requisiciones_' . Carbon::now()->format('Ymd_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        // Crear una respuesta de transmisión para la descarga
        $response = new StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        });

        // Configurar los encabezados de la respuesta
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $fileName . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    /*
      Genera y sirve un reporte en archivo excel de las órdenes de compra basado en el intervalo de tiempo especificado.

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

        // Construir la consulta con INNER JOIN
        $queryPendientes = Orden_compras::select('orden_compras.id_orden','users.nombres','users.apellidoP','users.departamento','orden_compras.created_at as fecha_orden','orden_compras.estado as estadoOrd','requisiciones.*','unidades.*','proveedores.nombre','orden_compras.costo_total')
                            ->join('proveedores','orden_compras.proveedor_id','=','proveedores.id_proveedor')
                            ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
                            ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
                            ->join('users','requisiciones.usuario_id','users.id')
                            ->leftJoin('unidades','requisiciones.unidad_id','unidades.id_unidad')
                            ->whereBetween('orden_compras.created_at', [$fInicio, $fFin])
                            ->where('orden_compras.estado','=',null);

        // Construir la consulta con INNER JOIN
        $queryPagados = Orden_compras::select('orden_compras.id_orden','users.nombres','users.apellidoP','users.departamento','orden_compras.created_at as fecha_orden','orden_compras.estado as estadoOrd','requisiciones.*','unidades.*','proveedores.nombre','orden_compras.costo_total')
                            ->join('proveedores','orden_compras.proveedor_id','=','proveedores.id_proveedor')
                            ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
                            ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
                            ->leftJoin('unidades','requisiciones.unidad_id','unidades.id_unidad')
                            ->join('users','requisiciones.usuario_id','users.id')
                            ->whereBetween('orden_compras.created_at', [$fInicio, $fFin])
                            ->where('orden_compras.estado','=','Pagado');

        // Si se han seleccionado departamentos, filtrar por ellos
        if (!empty($departamentos)) {
            $queryPendientes->whereIn('users.departamento', $departamentos);
            $queryPagados->whereIn('users.departamento', $departamentos);
        }

        // Ejecutar la consulta y obtener los resultados
        $datosGastosFinalizados = $queryPagados->get();
        $datosGastosPendientes = $queryPendientes->get();

        // Crear un nuevo archivo Excel para los datos de las requisiciones
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Asignar nombre a la hoja
        $sheet->setTitle('Ordenes Pendientes');

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
        $sheet->mergeCells('A5:C5');

        $sheet->setCellValue('A5', 'Registro de ordenes de compra pendientes');

        // Centrar los encabezados y ajustar tamaño de letra
        $sheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A5')->getFont()->setSize(16);

        // Escribir encabezados en el archivo Excel
        $sheet->setCellValue('A7', 'Fecha de requisicion');
        $sheet->setCellValue('B7', 'Área');
        $sheet->setCellValue('C7', 'Solicitante');
        $sheet->setCellValue('D7', 'Requisicion');
        $sheet->setCellValue('E7', 'Orden compra');
        $sheet->setCellValue('F7', 'Proveedor');
        $sheet->setCellValue('G7', 'Costo');
        $sheet->setCellValue('H7', 'Unidad');

        // Establecer el color de fondo de los encabezados
        $sheet->getStyle('A7:H7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF99C6F1');

        // Centrar los encabezados
        $sheet->getStyle('A7:H7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a los encabezados
        $sheet->getStyle('A7:H7')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

         // Ajustar el tamaño de las columnas al contenido
         foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Escribir los datos de las requisiciones en el archivo Excel
        // Comienza a escribir los datos desde la fila 8
        $rowNumber = 8;

        // Para cada orden de compra que obtenga la consulta
        foreach ($datosGastosPendientes as $orden) {

            // Contatena el nombre completo del solicitante
            $nombreCompleto = $orden->nombres . ' ' . $orden->apellidoP;

            // Le da formato dd/mm/aaaa a la fecha creacion de la requisición
            $fecha = date('d/m/Y', strtotime($orden->created_at));

            // Valida si la requisición pertenece a una unidad
            if (empty($orden->unidad_id)) {
                // Si no tiene una unidad asignada, entonces el valor de unidad es 'NA'
                $unidad = 'NA';
            }

            // Valida si la requisición pertenece a la unidad 1 o 2
            elseif($orden->unidad_id == 1 || $orden->unidad_id == 2){
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

            $sheet->setCellValue('A' . $rowNumber, $fecha);
            $sheet->setCellValue('B' . $rowNumber, $orden->departamento);
            $sheet->setCellValue('C' . $rowNumber, $nombreCompleto);
            $sheet->setCellValue('D' . $rowNumber, $orden->id_requisicion);
            $sheet->setCellValue('E' . $rowNumber, $orden->id_orden);
            $sheet->setCellValue('F' . $rowNumber, $orden->nombre);
            $sheet->setCellValue('G' . $rowNumber, $orden->costo_total);
            $sheet->setCellValue('H' . $rowNumber, $unidad);

            // Centrar las celdas de la fila actual
            $sheet->getStyle('A' . $rowNumber . ':H' . $rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Añadir bordes normales a las celdas de los datos
            $sheet->getStyle('A' . $rowNumber . ':H' . $rowNumber)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Aumenta en 1 la fila.
            $rowNumber++;
        }

        // Aplicar filtros a la tabla de datos
        $sheet->setAutoFilter('A7:H7');

        // Calcular y escribir el total de los costos al final de la tabla
        $sheet->setCellValue('F' . $rowNumber, 'Total');
        $sheet->setCellValue('G' . $rowNumber, '=SUM(G8:G' . ($rowNumber - 1) . ')');

        // Formato de moneda para la celda del total
        $sheet->getStyle('G' . $rowNumber)->getNumberFormat()->setFormatCode('$#,##0.00');

        // Centrar las celdas del total
        $sheet->getStyle('F' . $rowNumber . ':G' . $rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a la fila del total
        $sheet->getStyle('F' . $rowNumber . ':G' . $rowNumber)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

        // Crear segunda hoja
        $sheet2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Ordenes Pagadas');
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
        $sheet2->mergeCells('A5:C5');

        $sheet2->setCellValue('A5', 'Registro de ordenes de compra pagados');

        // Centrar los encabezados y ajustar tamaño de letra
        $sheet2->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet2->getStyle('A5')->getFont()->setSize(16);

        // Escribir encabezados en el archivo Excel
        $sheet2->setCellValue('A7', 'Fecha de requisicion');
        $sheet2->setCellValue('B7', 'Área');
        $sheet2->setCellValue('C7', 'Solicitante');
        $sheet2->setCellValue('D7', 'Requisicion');
        $sheet2->setCellValue('E7', 'Orden compra');
        $sheet2->setCellValue('F7', 'Proveedor');
        $sheet2->setCellValue('G7', 'Costo');
        $sheet2->setCellValue('H7', 'Unidad');

        // Establecer el color de fondo de los encabezados
        $sheet2->getStyle('A7:H7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF99C6F1');

        // Centrar los encabezados
        $sheet2->getStyle('A7:H7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a los encabezados
        $sheet2->getStyle('A7:H7')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

         // Ajustar el tamaño de las columnas al contenido
         foreach (range('A', 'H') as $columnID) {
            $sheet2->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Calcular el total de costos pendientes
        $totalPendientes = $datosGastosFinalizados->sum('costo_total');

        // Escribir los datos de las requisiciones en el archivo Excel
        // Comeinza a escribir los datos desde la fila 8
        $rowNumber = 8;

        // Para cada orden de compra que obtenga en la consulta
        foreach ($datosGastosFinalizados as $orden) {

            // Concatena el nombre del solicitante y su apellido paterno
            $nombreCompleto = $orden->nombres . ' ' . $orden->apellidoP;

            // Le da formato dd/mm/aaaa a la fecha creacion de la requisición
            $fecha = date('d/m/Y', strtotime($orden->created_at));

            // Valida si la requisicion pertence a una unidad
            if (empty($orden->unidad_id)) {

                // Si no tiene una unidad asignada, entonces el valor de unidad es 'NA'
                $unidad = 'NA';
            }

            // Valida si la requisicion pertenece a la unidad 1 o 2
            elseif($orden->unidad_id == 1 || $orden->unidad_id == 2){
                // Si pertenece, entonces el valorde unidad es 'No asignada'
                $unidad = 'No asignada';

                // Si tiene alguna otra unidad...
            } else{
                // Valida si el tipo es camión o camioneta
                if($orden->tipo === "CAMIÓN" || $orden->tipo=== "CAMIONETA"){
                    // Y unidad es su permiso
                    $unidad = $orden->n_de_permiso;

                    // Si no, son sus placas (unidad_id)
                } else{
                    $unidad = $orden->id_unidad;
                }
            }

            $sheet2->setCellValue('A' . $rowNumber, $fecha);
            $sheet2->setCellValue('B' . $rowNumber, $orden->departamento);
            $sheet2->setCellValue('C' . $rowNumber, $nombreCompleto);
            $sheet2->setCellValue('D' . $rowNumber, $orden->id_requisicion);
            $sheet2->setCellValue('E' . $rowNumber, $orden->id_orden);
            $sheet2->setCellValue('F' . $rowNumber, $orden->nombre);
            $sheet2->setCellValue('G' . $rowNumber, $orden->costo_total);
            $sheet2->setCellValue('H' . $rowNumber, $unidad);

            // Centrar las celdas de la fila actual
            $sheet2->getStyle('A' . $rowNumber . ':H' . $rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Añadir bordes normales a las celdas de los datos
            $sheet2->getStyle('A' . $rowNumber . ':H' . $rowNumber)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            $rowNumber++;
        }

        $sheet2->setAutoFilter('A7:H7');

        // Calcular y escribir el total de los costos al final de la tabla
        $sheet2->setCellValue('F' . $rowNumber, 'Total');
        $sheet2->setCellValue('G' . $rowNumber, '=SUM(G8:G' . ($rowNumber - 1) . ')');

        // Formato de moneda para la celda del total
        $sheet2->getStyle('G' . $rowNumber)->getNumberFormat()->setFormatCode('$#,##0.00');

        // Centrar las celdas del total
        $sheet2->getStyle('F' . $rowNumber . ':G' . $rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a la fila del total
        $sheet2->getStyle('F' . $rowNumber . ':G' . $rowNumber)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);$sheet2->setAutoFilter('A7:G7');

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

        return $response;
    }

    /*
      Genera y sirve un reporte en formato PDF de las unidades activas al momento.

      Este método maneja la solicitud de generación de reportes de unidades. Recupera las unidades activas
      correspondientes de la base de datos. Finalmente, el método genera un archivo en PDF utilizando estos datos,
      que luego es enviado directamente al cliente para su descarga.

      Sirve un PDF generado directamente a la vista para que el usuario pueda visualizarlo y descargarlo.
    */
    public function reporteUnidades(){

        // Consultar las unidades que se encuentren activas al momento
        $unidades = Unidades::where('estatus',1)
        ->orderBY('n_de_permiso','asc')
        ->get();

        // Incluir el archivo Requisicion.php y pasar la ruta del archivo como una variable
        ob_start();
        include(public_path('/pdf/TCPDF-main/examples/Reporte_Unidades.php'));
        $pdfContent = ob_get_clean();
        header('Content-Type: application/pdf');
        echo $pdfContent;

    }
}
