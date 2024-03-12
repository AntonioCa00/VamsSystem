<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Orden_compras;
use App\Models\Requisiciones;
use App\Models\Unidades;
use App\Models\Cotizaciones;
use App\Models\Pagos_Fijos;
Use Carbon\Carbon;
use DB;

use Illuminate\Http\Request;

class controladorGerenciaGen extends Controller
{
    /*
      TODO: Recopila datos para la visualización de informes de gestión en el área correspondiente.
     
      Este método se encarga de compilar datos detallados de operaciones de compra por mes y totales anuales,
      así como el conteo de requisiciones completas y pendientes. Utiliza la clase Orden_compras para sumar los costos totales
      de las compras realizadas en cada mes del año actual y calcula los totales de compras para el mes y año en curso.
      Además, cuenta las requisiciones en estado 'Comprado' y las requisiciones pendientes que no están 'Compradas' ni 'Rechazadas'.
     
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
        ->where('requisiciones.estado','!=', 'Finalizado')
        ->where('requisiciones.estado','!=','Rechazado')
        ->count();

        // Pasar los datos a la vista
        return view("Gerencia General.index",[
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
      TODO: Recupera y muestra una lista de todas las solicitudes junto con información relacionada para su visualización.
     
      Este método realiza una consulta compleja a la base de datos para obtener un listado detallado de todas las solicitudes
      (requisiciones) existentes. Para cada solicitud, se recopila información como el ID de la requisición, el nombre del usuario
      que la creó, el ID de la unidad asociada, el archivo PDF de la requisición, el estado de la solicitud, el PDF de la orden de
      compra relacionada (si existe), y la fecha de creación de la solicitud. Estos datos se obtienen mediante uniones (joins) con
      las tablas 'users', 'cotizaciones', y 'orden_compras' para recopilar la información necesaria de múltiples fuentes.
     
      Retorna la vista 'Gerencia General.solicitudes', pasando el listado de solicitudes recopiladas para su visualización.
    */
    public function tableSolicitud(){
        // Recupera las solicitudes de la base de datos
        $solicitudes = Requisiciones::select('requisiciones.id_requisicion', 'users.nombres', 'requisiciones.unidad_id', 'requisiciones.pdf', 'requisiciones.estado','orden_compras.pdf as ordenCompra', 'requisiciones.created_at as fecha_creacion')
        ->join('users', 'requisiciones.usuario_id', '=', 'users.id')
        ->leftJoin('cotizaciones','cotizaciones.requisicion_id','requisiciones.id_requisicion')
        ->leftJoin('orden_compras','orden_compras.cotizacion_id','cotizaciones.id_cotizacion')
        ->orderBy('requisiciones.created_at','desc')
        ->get();

        //Redirige al usuario a la página para visualizar la consulta
        return view('Gerencia General.solicitudes',compact('solicitudes'));
    }

    /*
      TODO: Recupera y muestra una lista de usuarios activos (encargados) ordenados por sus nombres.
     
      Este método consulta la base de datos para obtener un listado de todos los usuarios que tienen un estatus activo (estatus '1').
      La intención es identificar a los usuarios que están actualmente habilitados para asumir responsabilidades o roles específicos
      dentro de la organización. Los usuarios recuperados son ordenados alfabéticamente por sus nombres para facilitar su visualización
      y gestión.
     
      Retorna la vista 'Gerencia General.encargado', pasando el listado de usuarios activos (encargados) para su visualización.
    */
    public function tableEncargado(){
        // Recupera los usuarios activos de la base de datos
        $encargados = User::where('estatus','1')->orderBy('nombres')->get();

        //Redirige al usuario a la página para visualizar la consulta
        return view('Gerencia General.encargado',compact('encargados'));
    }

    /*
      TODO: Muestra la vista para la creación de un nuevo usuario.
     
      Este método se encarga de cargar y mostrar la vista que contiene el formulario utilizado para la creación
      de nuevos usuarios dentro del sistema. La vista proporcionará los campos necesarios para capturar la información
      del nuevo usuario.
     
      Retorna la vista 'Gerencia General.crearUser', que contiene el formulario para la creación de un nuevo usuario.
    */
    public function createUser(){
        // Cargar y mostrar la vista con el formulario de creación de usuario
        return view('Gerencia General.crearUser');
    }

    /*
      TODO: Inserta un nuevo usuario en la base de datos con los datos proporcionados a través de un formulario.
     
      Este método recibe datos de un formulario a través de una petición HTTP, incluyendo el nombre, apellidos,
      teléfono, correo electrónico, rol, y departamento del nuevo usuario. Se genera una contraseña aleatoria para
      el usuario utilizando un método auxiliar. Dependiendo del rol seleccionado ('Otro', 'Gerente Area', o cualquier otro),
      se ajustan los valores específicos antes de insertar el registro en la base de datos.

      Redirige al usuario a la lista de encargados con una sesión flash que indica que el nuevo usuario ha sido creado exitosamente.
    */
    public function insertUser(Request $req){
        $password = $this->generateRandomPassword();// Genera una contraseña aleatoria

        // Inserción del nuevo usuario en la base de datos con ajustes condicionales basados en el rol
        if($req->rol === "Otro"){
            // Caso específico para el rol 'Otro'
            DB::table('users')->insert([
                "nombres"=>$req->input('nombres'),
                "apellidoP"=>$req->input('apepat'),
                "apellidoM"=>$req->input('apemat'),
                "telefono"=>$req->input('telefono'),
                "correo"=>$req->input('correo'),
                "password"=>$password,
                "rol"=>'General',
                "departamento"=>$req->input('departamento'),
                "estatus"=>'1',
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);
        }elseif ($req->rol === "Gerente Area"){
            // Caso específico para el rol 'Gerente Area'
            DB::table('users')->insert([
                "nombres"=>$req->input('nombres'),
                "apellidoP"=>$req->input('apepat'),
                "apellidoM"=>$req->input('apemat'),
                "telefono"=>$req->input('telefono'),
                "correo"=>$req->input('correo'),
                "password"=>$password,
                "rol"=>$req->input('rol'),
                "departamento"=>$req->input('departamento'),
                "estatus"=>'1',
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);
        } else {
            // Caso general para otros roles
            DB::table('users')->insert([
                "nombres"=>$req->input('nombres'),
                "apellidoP"=>$req->input('apepat'),
                "apellidoM"=>$req->input('apemat'),
                "telefono"=>$req->input('telefono'),
                "correo"=>$req->input('correo'),
                "password"=>$password,
                "rol"=>$req->input('rol'),
                "estatus"=>'1',
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]); 
        }
        //Redirige al usuario a la página d usuarios para visualizar la actualización
        return redirect()->route('encargados')->with('creado','creado');
    }

    /*
      TODO: Muestra la vista para editar los detalles de un usuario específico.
     
      Este método se encarga de recuperar los detalles de un usuario específico, identificado por su ID, de la base de datos.
      La recuperación de esta información es crucial para pre-rellenar el formulario de edición en la vista con los datos actuales
      del usuario, permitiendo así que los administradores o los usuarios con los permisos adecuados realicen cambios en la información
      del usuario como nombre, teléfono, correo electrónico, rol, entre otros. 
     
      @param  int  $id  El ID del usuario cuyos detalles se van a editar.

      Retorna la vista 'Gerencia General.editarUser', pasando los detalles del usuario específico para su edición.
    */
    public function editUser($id){
        // Recupera los detalles del usuario específico por su ID
        $encargado = User::where('id',$id)->first();

        // Carga y muestra la vista con el formulario de edición de usuario, pasando los detalles del usuario
        return view('Gerencia General.editarUser',compact('encargado'));
    }

    /*
      TODOS: Actualiza los detalles de un usuario específico en la base de datos con la información proporcionada por el formulario.
     
      Este método recibe datos de un formulario a través de una petición HTTP, incluyendo el nombre, apellidos,
      teléfono, correo electrónico, contraseña, rol, y estatus del usuario. Utiliza estos datos para actualizar
      el registro del usuario específico en la base de datos, identificado por el ID proporcionado. 
     
      @param  int  $id  El ID del usuario que se va a actualizar.

      Redirige al usuario a la lista de encargados con una sesión flash que indica que el usuario ha sido editado exitosamente.
    */
    public function updateUser(Request $req, $id){
        // Actualiza el registro del usuario específico con los datos proporcionados
        User::where('id',$id)->update([
            "nombres"=>$req->input('nombres'),
            "apellidoP"=>$req->input('apepat'),
            "apellidoM"=>$req->input('apemat'),
            "telefono"=>$req->input('telefono'),
            "correo"=>$req->input('correo'),
            "password"=>$req->input('password'),
            "rol"=>$req->input('rol'),
            "estatus"=>'1',
            "updated_at"=>Carbon::now()
        ]);

        // Redirige al usuario a la lista de encargados con un mensaje de éxito
        return redirect()->route('encargados')->with('editado','editado');
    }

    /*
      TODO: Desactiva un usuario específico marcándolo como inactivo en la base de datos.
     
      En lugar de eliminar el registro del usuario, este método actualiza el campo 'estatus' a 0,
      indicando que el usuario está inactivo. Esta operación es crucial para mantener la integridad de los datos
      y permite la recuperación del registro en el futuro si es necesario. 
     
      @param  int  $id  El ID del usuario que se va a desactivar.

      Redirige al usuario a la lista de encargados con una sesión flash que indica que el usuario ha sido desactivado exitosamente.
    */
    public function deleteUser($id){
        // Actualiza el registro del usuario específico para marcarlo como inactivo
        User::where('id',$id)->update([
             "estatus"=>'0',
             "updated_at"=>Carbon::now()
         ]);

        // Redirige al usuario a la lista de encargados con un mensaje de confirmación
        return redirect()->route('encargados')->with('eliminado','eliminado');
    }
    
    /*
      TODO: Recupera y muestra una lista de unidades activas para la Gerencia General, excluyendo una unidad específica por su ID.
     
      Este método consulta la base de datos para obtener un listado de todas las unidades que tienen un estatus '1',
      lo que indica que están activas, y excluye la unidad con ID '1' de este listado. Las unidades son ordenadas
      en orden ascendente por su ID para facilitar su visualización y gestión.
     
      Retorna la vista 'Gerencia General.unidad', pasando el listado de unidades activas para su visualización.
    */
    public function unidadesGerGen(){
        // Recupera las unidades activas al momento
        $unidades = Unidades::where('estatus','1')
        ->where('id_unidad','!=','1')
        ->where('estado','Activo')
        ->orderBy('id_unidad','asc')->get();
        
        // Redirecciona a la vista para mostrar las unidades
        return view('Gerencia General.unidad',compact('unidades'));
    }

    /*
      TODO: Elimina una solicitud específica de la base de datos.
     
      Este método permite a los usuarios con los permisos adecuados eliminar una solicitud específica,
      identificada por su ID, de la base de datos. La eliminación de una solicitud puede ser necesaria
      en varias circunstancias, como cuando una solicitud ha sido ingresada por error, ya no es relevante,
      o ha sido reemplazada por otra más actualizada.
     
      @param  int  $id  El ID de la solicitud que se va a eliminar.

      Redirige al usuario a la página anterior con una sesión flash que indica que la solicitud ha sido eliminada exitosamente.
    */    
    public function deleteSolicitud($id){
        // Elimina la solicitud específica por su ID
        Requisiciones::where('id_requisicion',$id)->delete();

        // Redirige al usuario a la página anterior con un mensaje de confirmación
        return back()->with('eliminado','eliminado');  
    }

    /*
      TODO: Recupera y muestra todas las cotizaciones asociadas a una solicitud específica.
     
      Este método consulta la base de datos para obtener un listado de todas las cotizaciones que están asociadas
      a una solicitud específica, identificada por su ID. Para cada cotización, se recopilan detalles como el ID de la cotización,
      el ID de la requisición asociada, y las rutas a los archivos PDF de la requisición y de la cotización. 
     
      @param  int  $id El ID de la requisición para la cual se recuperarán las cotizaciones.

      Retorna la vista 'Gerencia General.cotizaciones', pasando el listado de cotizaciones recopiladas para su visualización.
    */
    public function cotizaciones($id){
        //Recupera las cotizaciones segun la requisicion
        $cotizaciones = Cotizaciones::select('requisiciones.id_requisicion','cotizaciones.id_cotizacion','requisiciones.PDF','cotizaciones.PDF as cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','requisiciones.id_requisicion')
        ->where('requisicion_id',$id)
        ->get();

        // Redirige al usuario a la página para visualizar las cotizaciones
        return view('Gerencia General.cotizaciones',compact('cotizaciones'));
    }

    /*
      TODO: Muestra la vista de reportes
     
      Este método se encarga de cargar y presentar la vista que contiene las herramientas y opciones de reporte
      disponibles para la Gerencia General. Al proporcionar una interfaz dedicada a los reportes, este método facilita a los 
      usuarios autorizados el acceso rápido y eficiente a la información necesaria para la toma de decisiones y la planificación estratégica.
     
      Retorna la vista 'Gerencia General.reportes', que contiene las opciones y herramientas de reporte disponibles.
    */
    public function reportes() {
        // Cargar y mostrar la vista con las opciones de reporte
        return view('Gerencia General.reportes');
    }

    /*
      TODO: Genera y muestra un reporte de requisiciones basado en el tipo de reporte seleccionado.
     
      Este método recibe una petición HTTP que contiene el tipo de reporte solicitado (semanal, mensual, anual, o todas).
      Dependiendo del tipo de reporte, realiza una consulta a la base de datos para recuperar las requisiciones dentro
      del periodo de tiempo especificado, junto con información relevante de los usuarios que las crearon.
      Los datos recopilados se serializan y almacenan en un archivo, luego se genera un PDF del reporte utilizando una
      plantilla específica. Finalmente, el contenido del PDF se envía al navegador para su visualización o descarga.
     
      @return void
    */
    public function reporteReq(Request $req){
        // Recopilación de información del empleado para incluirla en el reporte
        $datosEmpleado[] = [
            'idEmpleado' => session('loginId'),
            'nombres' => session('loginNombres'),
            'apellidoP' => session('loginApepat'),
            'apellidoM' => session('loginApemat'),
            'rol' => session('rol'),
            'dpto' =>session('departamento')
        ];

        // Valida el tipo de reporte que se desea obtener
        $tipoReporte = $req->input('tipoReport');

         // Recopilación de datos de las requisiciones para el periodo seleccionado
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

        // Incluir el archivo Reporte_Requisiciones.php y pasar la ruta del archivo como una variable
        ob_start();
        include(public_path('/pdf/TCPDF-main/examples/Reporte_Requisiciones.php'));
        // Generación del PDF del reporte y envío del contenido al navegador
        $pdfContent = ob_get_clean();
        header('Content-Type: application/pdf');
        echo $pdfContent;
    }

    /*
      TODO: Genera y muestra un reporte de ordenes de compra basado en el tipo de reporte seleccionado.
     
      Este método recibe una petición HTTP que contiene el tipo de reporte solicitado (semanal, mensual, anual, o todas).
      Dependiendo del tipo de reporte, realiza una consulta a la base de datos para recuperar las ordenes de compra dentro
      del periodo de tiempo especificado, junto con información relevante de los usuarios que las crearon.
      Los datos recopilados se serializan y almacenan en un archivo, luego se genera un PDF del reporte utilizando una
      plantilla específica. Finalmente, el contenido del PDF se envía al navegador para su visualización o descarga.
     
      @return void
    */
    public function reporteOrd(Request $req){
        //Recopilación de datos del usuario en sesión
        $datosEmpleado[] = [
            'idEmpleado' => session('loginId'),
            'nombres' => session('loginNombres'),
            'apellidoP' => session('loginApepat'),
            'apellidoM' => session('loginApemat'),
            'rol' => session('rol'),
            'dpto' =>session('departamento')
        ];

        //Variable que define los rangos de reportes
        $tipoReporte = $req->input('tipoReport');

        //Validación de la variable
        switch ($tipoReporte){
            case "semanal":
                //Si es semana, define el tiempo con la librería Carbon
                $unaSemanaAtras = Carbon::now()->subWeek();

                //Recuperar las ordenes de compra que no se han finalizado (pagado).
                $datosGastosPendientes = Orden_compras::select('orden_compras.id_orden','users.nombres','users.apellidoP','orden_compras.created_at','orden_compras.estado','requisiciones.id_requisicion','proveedores.nombre','orden_compras.costo_total')
                ->join('proveedores','orden_compras.proveedor_id','=','proveedores.id_proveedor')
                ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
                ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
                ->join('users','requisiciones.usuario_id','users.id')
                ->where('orden_compras.estado','!=','Finalizado')
                ->where('orden_compras.created_at', '>=', $unaSemanaAtras)            
                ->get();

                //Recuperar las ordenes de compra que se han finalizado (pagado).
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
                //Si es mensual, definir el mes actual al momento del reporte con la librería Carbon
                $inicioDelMes = Carbon::now()->startOfMonth();

                //Recuperar las ordenes de compra que no se han finalizado (pagado)
                $datosGastosPendientes = Orden_compras::select('orden_compras.id_orden','users.nombres','users.apellidoP','orden_compras.created_at','requisiciones.id_requisicion','proveedores.nombre','orden_compras.costo_total')
                ->join('proveedores','orden_compras.proveedor_id','=','proveedores.id_proveedor')
                ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
                ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
                ->join('users','requisiciones.usuario_id','users.id')
                ->where('orden_compras.estado','!=','Finalizado')
                ->where('orden_compras.created_at', '>=', $inicioDelMes)
                ->get();    

                //Recuperar las ordenes de compra que se han finalizado (pagado)
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
                //Si es mensual, definir el mes actual al momento del reporte con la librería Carbon
                $inicioDelAnio = Carbon::now()->startOfYear();

                //Recuperar las ordenes de compra que no se han finalizado (pagado)
                $datosGastosPendientes = Orden_compras::select('orden_compras.id_orden','users.nombres','users.apellidoP','orden_compras.created_at','requisiciones.id_requisicion','proveedores.nombre','orden_compras.costo_total')
                ->join('proveedores','orden_compras.proveedor_id','=','proveedores.id_proveedor')
                ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
                ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
                ->join('users','requisiciones.usuario_id','users.id')
                ->where('orden_compras.estado','!=','Finalizado')
                ->where('orden_compras.created_at', '>=', $inicioDelAnio)
                ->get();

                //Recuperar las ordenes de compra que se han finalizado (pagado)
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
                //Al ser todas, no define rangos de tiempo.
                //Recuperar las ordenes de compra que no se han finalizado (pagado)
                $datosGastosPendientes = Orden_compras::select('orden_compras.id_orden','users.nombres','users.apellidoP','orden_compras.created_at','orden_compras.estado','requisiciones.id_requisicion','proveedores.nombre','orden_compras.costo_total')
                ->join('proveedores','orden_compras.proveedor_id','=','proveedores.id_proveedor')
                ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
                ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
                ->join('users','requisiciones.usuario_id','users.id')
                ->where('orden_compras.estado','!=','Finalizado')
                ->get();         

                //Recuperar las ordenes de compra que se han finalizado (pagado)
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

    /*
      TODO: Genera una contraseña aleatoria de 6 caracteres utilizando letras mayúsculas y minúsculas.
     
      Este método crea una contraseña segura y aleatoria seleccionando caracteres de un conjunto definido
      que incluye todas las letras del alfabeto en mayúsculas y minúsculas. Utiliza la función `random_int`
      para asegurar una selección aleatoria criptográficamente segura de los caracteres. La longitud de la
      contraseña generada es fija en 6 caracteres, equilibrando la simplicidad y la seguridad para propósitos
      generales de autenticación.
     
      @return string La contraseña aleatoria generada.
    */
    public function generateRandomPassword() {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';// Conjunto de caracteres elegibles
        $password = ''; // Inicialización de la variable de contraseña
    
        // Bucle para seleccionar 6 caracteres aleatorios del conjunto definido
        for ($i = 0; $i < 6; $i++) {
            $index = random_int(0, strlen($characters) - 1); // Selección aleatoria de un índice
            $password .= $characters[$index]; // Concatenación del carácter seleccionado a la contraseña
        }
    
        return $password; // Devolución de la contraseña generada
    }
}