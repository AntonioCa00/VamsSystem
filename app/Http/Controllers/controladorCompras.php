<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
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
use DB;
use Carbon\Carbon;

class controladorCompras extends Controller
{
    /*
      TODO: Recopila datos para la visualización de informes de gestión en el área correspondiente.
     
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
        $Enero = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as enero"))
            ->whereBetween('created_at', ["$anio_actual-01-01 00:00:00", "$anio_actual-01-31 23:59:59"])
            ->first();

        $Febrero = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as febrero"))
            ->whereBetween('created_at', ["$anio_actual-02-01 00:00:00", "$anio_actual-02-28 23:59:59"])
            ->first();

        $Marzo = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as marzo"))
            ->whereBetween('created_at', ["$anio_actual-03-01 00:00:00", "$anio_actual-03-31 23:59:59"])
            ->first();

        $Abril = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as abril"))
            ->whereBetween('created_at', ["$anio_actual-04-01 00:00:00", "$anio_actual-04-30 23:59:59"])
            ->first();

        $Mayo = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as mayo"))
            ->whereBetween('created_at', ["$anio_actual-05-01 00:00:00", "$anio_actual-05-31 23:59:59"])
            ->first();

        $Junio = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as junio"))
            ->whereBetween('created_at', ["$anio_actual-06-01 00:00:00", "$anio_actual-06-30 23:59:59"])
            ->first();

        $Julio = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as julio"))
            ->whereBetween('created_at', ["$anio_actual-07-01 00:00:00", "$anio_actual-07-31 23:59:59"])
            ->first();

        $Agosto = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as agosto"))
            ->whereBetween('created_at', ["$anio_actual-08-01 00:00:00", "$anio_actual-08-31 23:59:59"])
            ->first();

        $Septiembre = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as septiembre"))
            ->whereBetween('created_at', ["$anio_actual-09-01 00:00:00", "$anio_actual-09-30 23:59:59"])
            ->first();

        $Octubre = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as octubre"))
            ->whereBetween('created_at', ["$anio_actual-10-01 00:00:00", "$anio_actual-10-31 23:59:59"])
            ->first();

        $Noviembre = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as noviembre"))
            ->whereBetween('created_at', ["$anio_actual-11-01 00:00:00", "$anio_actual-11-30 23:59:59"])
            ->first();

        $Diciembre = Orden_compras::
            select(DB::raw("COALESCE(SUM(costo_total), 0) as diciembre"))
            ->whereBetween('created_at', ["$anio_actual-12-01 00:00:00", "$anio_actual-12-31 23:59:59"])
            ->first();

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
        $completas = Requisiciones::where('estado', 'Finalizado')->count();

        // Conteo de requisiciones pendientes
        $pendiente = Requisiciones::where('estado','!=', 'Finalizado')->where('estado','!=','Rechazado')->count();

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
      TODO: Recupera y muestra todas las refacciones activas en el almacen.
     
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
      TODO: Recupera y muestra un listado de unidades activas, excluyendo una unidad específica.
     
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
      TODO: Muestra la vista para la creación de una nueva unidad.
     
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
      
      Retorna la vista 'Admin.editarUnidad', pasando los detalles de la unidad específica para su edición.
    */
    public function editUnidad($id){
        // Recupera los detalles de la unidad específica por su ID
        $unidad= Unidades::where('id_unidad',$id)->first();

        // Carga y muestra la vista con el formulario de edición de unidad, pasando los detalles de la unidad
        return view('Admin.editarUnidad',compact('unidad'));
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
      TODO: Recupera y muestra todas las unidades inactivas para su potencial activación.
     
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

    //! ESTA FUNCION NO ESTA SIRVIENDO ACTUALMENTE
    public function tableSalidas(){
        $salidas = Salidas::select('salidas.id_salida','requisiciones.pdf as reqPDF','salidas.cantidad','users.nombres','almacen.clave','almacen.ubicacion','almacen.descripcion','salidas.created_at')
        ->join('almacen','salidas.refaccion_id','=','almacen.clave')
        ->join('requisiciones','salidas.requisicion_id','=','requisiciones.id_requisicion')
        ->join('users','requisiciones.usuario_id','=','users.id')
        ->get();
        return view('Admin.salidas',compact('salidas'));
    }

    /*
      TODO: Recupera y muestra un listado de solicitudes que cumplen con ciertos criterios de estado, junto con información relevante.
     
      Este método realiza consultas complejas a la base de datos para obtener un listado de solicitudes que están en los estados
      'Aprobado', 'Cotizado', 'Validado', o 'Comprado'. Además de la información básica de la solicitud, se calcula y muestra la
      cantidad de artículos asociados a cada solicitud que tienen un estatus '0'. También se recupera información agrupada por
      departamento y por estados específicos de las solicitudes para posibles filtros o visualizaciones en la vista.
     
      Retorna la vista 'Admin.solicitudes', pasando el listado de solicitudes, departamentos, y estados de solicitudes para su visualización.
    */
    public function tableSolicitud(){
        // Consulta de solicitudes que cumplen con los criterios de estado especificados y cálculo de cantidad de artículos inactivos.
        $solicitudes = Requisiciones::select(['requisiciones.id_requisicion','users.nombres','requisiciones.unidad_id','requisiciones.pdf','requisiciones.estado','requisiciones.created_at as fecha_creacion',DB::raw('(SELECT COUNT(*) FROM articulos WHERE articulos.requisicion_id = requisiciones.id_requisicion AND articulos.estatus = 0) as cantidad_articulos')])    
        ->join('users', 'requisiciones.usuario_id', '=', 'users.id')
        ->where(function($query) {
            $query->where('requisiciones.estado', '=', 'Aprobado')
                  ->orWhere('requisiciones.estado', '=', 'Cotizado')
                  ->orWhere('requisiciones.estado', '=', 'Validado')
                  ->orWhere('requisiciones.estado', '=', 'Comprado');
        })
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

        // Redirige al usuario a la lista de solicitudes con los datos recuperados
        return view('Admin.solicitudes',compact('solicitudes','departamentos','estatus'));
    }

    /*
      TODO: Filtra y muestra un listado de solicitudes basado en criterios específicos como el departamento o el estado.
     
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
      Carga la vista para la creación de nuevas cotizaciones asociadas a una solicitud específica.
     
      Este método recupera todas las cotizaciones activas (estatus '1') asociadas a una solicitud específica,
      identificada por su ID, incluyendo los archivos PDF tanto de la solicitud original como de las cotizaciones
      existentes.
     
      @param  int  $id El ID de la solicitud para la cual se crearán nuevas cotizaciones.

      Retorna la vista 'Admin.crearCotizacion', pasando las cotizaciones existentes y el ID de la solicitud para su visualización.
    */
    public function createCotiza($id){
        // Recupera las cotizaciones activas asociadas a la solicitud específica
        $cotizaciones = Cotizaciones::select('cotizaciones.id_cotizacion','requisiciones.pdf as reqPDF','cotizaciones.pdf as cotPDF')
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
      TODO: Elimina una cotización específica de la base de datos.
     
      Este método permite a los usuarios con los permisos adecuados eliminar una cotización específica,
      identificada por su ID, de la base de datos.
     
      @param  int  $id  El ID de la cotización que se va a eliminar.

      Redirige al usuario a la página anterior con una sesión flash que indica que la cotización ha sido eliminada exitosamente.
    */
    public function deleteCotiza($id){
        // Elimina la cotización específica por su ID
        Cotizaciones::where('id_cotizacion', $id)->delete();

        // Redirige al usuario a la página anterior con un mensaje de confirmación
        return back()->with('eliminado','eliminado');    
    }  

    /*
      TODO: Recupera y muestra todos los proveedores activos en la base de datos.
     
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
      TODO: Muestra la vista para la creación de un nuevo proveedor.
     
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
        // Validación de archivo
        $req->validate([
            'archivo_CIF' =>'required|file|mimes:pdf',
        ]);

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

      Retorna la vista 'Admin.editarProveedor', pasando los detalles del proveedor específico para su edición.
    */
    public function editProveedor($id){
        // Recupera los detalles del proveedor específico por su ID
        $proveedor = Proveedores::where('id_proveedor',$id)->first();

        // Carga y muestra la vista con el formulario de edición de proveedor, pasando los detalles del proveedor
        return view('Admin.editarProveedor',compact('proveedor'));
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
        } else{
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

    /*
      TODO: Prepara y muestra la información necesaria para generar una orden de compra.
     
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

    public function ordenesCompras(){
        $ordenes = Orden_compras::select('orden_compras.id_orden','requisiciones.id_requisicion','requisiciones.estado','users.nombres','cotizaciones.pdf as cotPDF','proveedores.nombre as proveedor','orden_compras.costo_total','orden_compras.estado as estadoComp','orden_compras.pdf as ordPDF', 'orden_compras.created_at')
        ->join('users','orden_compras.admin_id','=','users.id')
        ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
        ->join('proveedores','orden_compras.proveedor_id','=','proveedores.id_proveedor')
        ->where('requisiciones.estado','!=','Rechazado')
        ->orderBy('orden_compras.created_at','desc')
        ->get();
        return view ('Admin.ordenesCompras',compact('ordenes'));
    }

    public function deleteOrd($id,$sid){
        $articulos_ord = Articulos::where('orden_id', $id)->get();
    
    foreach ($articulos_ord as $articulo) {
        // Calculas la nueva cantidad sumando la cantidad actual con la última compra
        $anterior_cantidad = $articulo->cantidad + $articulo->ult_compra;
        
        // Actualizas solo este artículo específico usando su ID único
        Articulos::where('id', $articulo->id)->update([
            "cantidad" => $anterior_cantidad,
            "precio_unitario" => null,
            "estatus" => 0,
            "orden_id" => null,
        ]);
    }

        Orden_compras::where('id_orden',$id)->delete();

        Requisiciones::where('id_requisicion',$sid)->update([
            "estado"=>"Validado",
            "updated_at"=>Carbon::now()
        ]);
        return back()->with('eliminada','eliminada');
    }

    public function FinalizarC($id){
        Orden_Compras::where('id_orden',$id)->update([
            "estado"=>"Finalizado",
            "updated_at"=>Carbon::now(),
        ]);

        return back()->with('finalizada','finalizada');
    }

    public function FinalizarReq($id){
        Requisiciones::where('id_requisicion',$id)->update([
            "estado"=>"Finalizado",
            "updated_at"=>Carbon::now(),
        ]);

        return back()->with('finalizada','finalizada');
    }

    public function reportes() {
        $encargados = User::where('rol','General')->where('estatus','1')
        ->orderBy('nombres','asc')->get();
        $unidades = Unidades::where('estatus','1')
        ->orderBy('id_unidad','asc')->get();
        return view('Admin.reportes',compact('encargados','unidades'));
    }

    public function reporteEnc(Request $req){

        $idEncargado = $req->encargado;
        
        $encargado = User::where('id',$idEncargado)->first();
        $solicitudes = Requisiciones::where('usuario_id',$idEncargado)->count();
        $completas = Requisiciones::where('estado','Entregado')->where('usuario_id',$idEncargado)->count();
        $Requisiciones = Requisiciones::where('usuario_id',$idEncargado)->get();
        $salidas = Salidas::select('salidas.id_salida','salidas.created_at','salidas.cantidad','requisiciones.unidad_id','almacen.descripcion')
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

    public function reporteReq(Request $req){
        $datosEmpleado[] = [
            'idEmpleado' => session('loginId'),
            'nombres' => session('loginNombres'),
            'apellidoP' => session('loginApepat'),
            'apellidoM' => session('loginApemat'),
            'rol' => session('rol'),
            'dpto' =>session('departamento')
        ];

        $tipoReporte = $req->input('tipoReport');

        switch ($tipoReporte){
            case "semanal":
                $unaSemanaAtras = Carbon::now()->subWeek();
                
                $datosRequisicion = Requisiciones::select('requisiciones.id_requisicion','users.nombres','users.apellidoP','requisiciones.created_at','requisiciones.estado','requisiciones.unidad_id')
                ->join('users','requisiciones.usuario_id','=','users.id')
                ->where('requisiciones.created_at', '>=', $unaSemanaAtras)
                ->get();    
                
                break;
            case "mensual":
                $inicioDelMes = Carbon::now()->startOfMonth();
                
                $datosRequisicion = Requisiciones::select('requisiciones.id_requisicion','users.nombres','users.apellidoP','requisiciones.created_at','requisiciones.estado','requisiciones.unidad_id')
                ->join('users','requisiciones.usuario_id','=','users.id')
                ->where('requisiciones.created_at', '>=', $inicioDelMes)
                ->get();    

                break;
            case "anual":
                $inicioDelAnio = Carbon::now()->startOfYear();
                
                $datosRequisicion = Requisiciones::select('requisiciones.id_requisicion','users.nombres','users.apellidoP','requisiciones.created_at','requisiciones.estado','requisiciones.unidad_id')
                ->join('users','requisiciones.usuario_id','=','users.id')
                ->where('requisiciones.created_at', '>=', $inicioDelAnio)
                ->get();  

                break;
            case "todas":
                $inicioDelAnio = Carbon::now()->startOfYear(); 
                
                $datosRequisicion = Requisiciones::select('requisiciones.id_requisicion','users.nombres','users.apellidoP','requisiciones.created_at','requisiciones.estado','requisiciones.unidad_id')
                ->join('users','requisiciones.usuario_id','=','users.id')
                ->get();  

                break;
        }

        // Serializar los datos del empleado y almacenarlos en un archivo
        $datosSerializados = serialize($datosEmpleado);
        $rutaArchivo = storage_path('app/datos_empleados.txt');
        file_put_contents($rutaArchivo, $datosSerializados);

        // Incluir el archivo Requisicion.php y pasar la ruta del archivo como una variable
        ob_start();
        include(public_path('/pdf/TCPDF-main/examples/Reporte_Requisiciones.php'));
        $pdfContent = ob_get_clean();
        header('Content-Type: application/pdf');
        echo $pdfContent;
    }

    public function reporteOrd(Request $req){
        $datosEmpleado[] = [
            'idEmpleado' => session('loginId'),
            'nombres' => session('loginNombres'),
            'apellidoP' => session('loginApepat'),
            'apellidoM' => session('loginApemat'),
            'rol' => session('rol'),
            'dpto' =>session('departamento')
        ];

        $tipoReporte = $req->input('tipoReport');

        switch ($tipoReporte){
            case "semanal":
                $unaSemanaAtras = Carbon::now()->subWeek();

                $datosGastosPendientes = Orden_compras::select('orden_compras.id_orden','users.nombres','users.apellidoP','orden_compras.created_at','orden_compras.estado','requisiciones.id_requisicion','proveedores.nombre','orden_compras.costo_total')
                ->join('proveedores','orden_compras.proveedor_id','=','proveedores.id_proveedor')
                ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
                ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
                ->join('users','requisiciones.usuario_id','users.id')
                ->where('orden_compras.estado','!=','Finalizado')
                ->where('orden_compras.created_at', '>=', $unaSemanaAtras)            
                ->get();

                $datosGastosFinalizados = Orden_compras::select('orden_compras.id_orden','users.nombres','users.apellidoP','orden_compras.created_at','orden_compras.estado','requisiciones.id_requisicion','proveedores.nombre','orden_compras.costo_total')
                ->join('proveedores','orden_compras.proveedor_id','=','proveedores.id_proveedor')
                ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
                ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
                ->join('users','requisiciones.usuario_id','users.id')
                ->where('orden_compras.estado','=','Finalizado')
                ->where('orden_compras.created_at', '>=', $unaSemanaAtras)            
                ->get();
                
                break;
            case "mensual":
                $inicioDelMes = Carbon::now()->startOfMonth();

                $datosGastosPendientes = Orden_compras::select('orden_compras.id_orden','users.nombres','users.apellidoP','orden_compras.created_at','requisiciones.id_requisicion','proveedores.nombre','orden_compras.costo_total')
                ->join('proveedores','orden_compras.proveedor_id','=','proveedores.id_proveedor')
                ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
                ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
                ->join('users','requisiciones.usuario_id','users.id')
                ->where('orden_compras.estado','!=','Finalizado')
                ->where('orden_compras.created_at', '>=', $inicioDelMes)
                ->get();    

                $datosGastosFinalizados = Orden_compras::select('orden_compras.id_orden','users.nombres','users.apellidoP','orden_compras.created_at','requisiciones.id_requisicion','proveedores.nombre','orden_compras.costo_total')
                ->join('proveedores','orden_compras.proveedor_id','=','proveedores.id_proveedor')
                ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
                ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
                ->join('users','requisiciones.usuario_id','users.id')
                ->where('orden_compras.estado','=','Finalizado')
                ->where('orden_compras.created_at', '>=', $inicioDelMes)
                ->get();    

                break;
            case "anual":
                $inicioDelAnio = Carbon::now()->startOfYear();

                $datosGastosPendientes = Orden_compras::select('orden_compras.id_orden','users.nombres','users.apellidoP','orden_compras.created_at','requisiciones.id_requisicion','proveedores.nombre','orden_compras.costo_total')
                ->join('proveedores','orden_compras.proveedor_id','=','proveedores.id_proveedor')
                ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
                ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
                ->join('users','requisiciones.usuario_id','users.id')
                ->where('orden_compras.estado','!=','Finalizado')
                ->where('orden_compras.created_at', '>=', $inicioDelAnio)
                ->get();

                $datosGastosFinalizados = Orden_compras::select('orden_compras.id_orden','users.nombres','users.apellidoP','orden_compras.created_at','requisiciones.id_requisicion','proveedores.nombre','orden_compras.costo_total')
                ->join('proveedores','orden_compras.proveedor_id','=','proveedores.id_proveedor')
                ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
                ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
                ->join('users','requisiciones.usuario_id','users.id')
                ->where('orden_compras.estado','=','Finalizado')
                ->where('orden_compras.created_at', '>=', $inicioDelAnio)
                ->get();

                break;
            case "todas":
                $datosGastosPendientes = Orden_compras::select('orden_compras.id_orden','users.nombres','users.apellidoP','orden_compras.created_at','orden_compras.estado','requisiciones.id_requisicion','proveedores.nombre','orden_compras.costo_total')
                ->join('proveedores','orden_compras.proveedor_id','=','proveedores.id_proveedor')
                ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
                ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
                ->join('users','requisiciones.usuario_id','users.id')
                ->where('orden_compras.estado','!=','Finalizado')
                ->get();         

                $datosGastosFinalizados = Orden_compras::select('orden_compras.id_orden','users.nombres','users.apellidoP','orden_compras.created_at','orden_compras.estado','requisiciones.id_requisicion','proveedores.nombre','orden_compras.costo_total')
                ->join('proveedores','orden_compras.proveedor_id','=','proveedores.id_proveedor')
                ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
                ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
                ->join('users','requisiciones.usuario_id','users.id')
                ->where('orden_compras.estado','=','Finalizado')
                ->get();         

                break;
        }

        // Serializar los datos del empleado y almacenarlos en un archivo
        $datosSerializados = serialize($datosEmpleado);
        $rutaArchivo = storage_path('app/datos_empleados.txt');
        file_put_contents($rutaArchivo, $datosSerializados);

        // Incluir el archivo Requisicion.php y pasar la ruta del archivo como una variable
        ob_start();
        include(public_path('/pdf/TCPDF-main/examples/Reporte_Ordenes.php'));
        $pdfContent = ob_get_clean();
        header('Content-Type: application/pdf');
        echo $pdfContent;
    }
}