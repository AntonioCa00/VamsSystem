<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\Controller; // Asegúrate de incluir esta línea correctamente
use App\Models\User;
use App\Models\Orden_compras;
use App\Models\Requisiciones;
use App\Models\Unidades;
use App\Models\Cotizaciones;
use App\Models\Pagos_Fijos;
use App\Models\Servicios;
use App\Models\Proveedores;
use App\Models\Logs;
//-------PHPOFFICE---------
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
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
        $solicitudes = Requisiciones::select('requisiciones.id_requisicion', 'users.nombres','users.departamento', 'requisiciones.unidad_id', 'requisiciones.pdf', 'requisiciones.estado','orden_compras.pdf as ordenCompra', 'requisiciones.created_at as fecha_creacion')
        ->join('users', 'requisiciones.usuario_id', '=', 'users.id')
        ->leftJoin('cotizaciones','cotizaciones.requisicion_id','requisiciones.id_requisicion')
        ->leftJoin('orden_compras','orden_compras.cotizacion_id','cotizaciones.id_cotizacion')
        ->orderBy('requisiciones.created_at','desc')
        ->get();

        //Redirige al usuario a la página para visualizar la consulta
        return view('Gerencia General.solicitudes',compact('solicitudes'));
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
    public function cotizacionesFin($id){
        //Recupera las cotizaciones basandose en el estatus 1 y segun la requisicion
        $cotizaciones = Cotizaciones::select('cotizaciones.id_cotizacion','requisiciones.id_requisicion as requisicion_id','users.nombres as usuario','requisiciones.pdf as reqPDF','cotizaciones.pdf as cotPDF')
        ->join('requisiciones','cotizaciones.requisicion_id', '=', 'requisiciones.id_requisicion')
        ->join('users','cotizaciones.usuario_id', '=', 'users.id')
        ->where('requisicion_id', $id)
        ->where('cotizaciones.estatus','1')->get();

        // Redirige al usuario a la página para visualizar las cotizaciones
        return view('Gerencia General.validCotizacion',compact('cotizaciones','id'));
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
    public function selectCotizaF($id,$sid){
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

        // Redirige al usuario a la lista de solicitudes con una sesión flash indicando que la cotización ha sido pre-validada o validada.
        return redirect('solicitud/GerenciaGen')->with('validacion','validacion');
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
    public function deleteCotizaF($id, $rid){
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

        $departamento = null;
         // Validación condicional basada en el valor del campo 'rol'
        if ($req->rol === "Gerente Area") {
            $req->validate([
                'departamentos' => 'required|array|min:1', // Permite minimo un departamento
            ]);

            // Procesar los datos si son válidos
            $departamentos = $req->input('departamentos');

            // Concatenar los departamentos en una sola cadena
            $departamento = implode(' / ', $departamentos);

        } elseif($req->rol === "Otro") {
            $req->validate([
                'departamentos' => 'required|array|size:1', // Permite uno o más departamentos
            ],
            [
                'departamentos.size' => 'El solicitante solo puede pertenecer a un departamento.',
            ]);

            // Procesar los datos si son válidos
            $departamentos = $req->input('departamentos');

            // Concatenar los departamentos en una sola cadena
            $departamento = implode(' / ', $departamentos);
            $req->rol = 'General';
        }

        $password = $this->generateRandomPassword();// Genera una contraseña aleatoria

        User::create([
            "nombres"=>$req->input('nombres'),
            "apellidoP"=>$req->input('apepat'),
            "apellidoM"=>$req->input('apemat'),
            "telefono"=>$req->input('telefono'),
            "correo"=>$req->input('correo'),
            "password"=>$password,
            "rol"=>$req->input('rol'),
            "departamento"=>$departamento,
            "estatus"=>'1',
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);
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

        $departamento = null;
         // Validación condicional basada en el valor del campo 'rol'
        if ($req->rol === "Gerente Area") {
            $req->validate([
                'departamentos' => 'required|array|min:1', // Permite minimo un departamento
            ]);

            // Procesar los datos si son válidos
            $departamentos = $req->input('departamentos');

            // Concatenar los departamentos en una sola cadena
            $departamento = implode(' / ', $departamentos);

        } elseif($req->rol === "General") {
            $req->validate([
                'departamentos' => 'required|array|size:1', // Permite uno o más departamentos
            ],
            [
                'departamentos.size' => 'El solicitante solo puede pertenecer a un departamento.',
            ]);

            // Procesar los datos si son válidos
            $departamentos = $req->input('departamentos');

            // Concatenar los departamentos en una sola cadena
            $departamento = implode(' / ', $departamentos);
            $req->rol = 'General';
        }

        // Actualiza el registro del usuario específico con los datos proporcionados
        User::where('id',$id)->update([
            "nombres"=>$req->input('nombres'),
            "apellidoP"=>$req->input('apepat'),
            "apellidoM"=>$req->input('apemat'),
            "telefono"=>$req->input('telefono'),
            "correo"=>$req->input('correo'),
            "password"=>$req->input('password'),
            "rol"=>$req->input('rol'),
            "departamento"=>$departamento,
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
      Recupera y muestra una lista detallada de todas las órdenes de compra que no están asociadas a requisiciones rechazadas.

      Este método consulta la base de datos para obtener información completa sobre cada orden de compra en el sistema,
      excluyendo aquellas relacionadas con requisiciones que han sido rechazadas. Los datos recopilados incluyen el ID de la orden,
      detalles de la requisición asociada, información del administrador que manejó la orden, datos del proveedor, PDFs de las
      cotizaciones, y otros documentos relevantes como comprobantes de pago.

      Devuelve la vista 'Gerencia General.ordenesCompras', pasando los datos de las órdenes de compra para su visualización.
    */
    public function compras(){
        // Obtener las órdenes de compra junto con información de segumiento de ordenes de compras
        $ordenes = Orden_compras::select('orden_compras.id_orden','requisiciones.id_requisicion','requisiciones.estado','users.nombres','cotizaciones.pdf as cotPDF','proveedores.nombre as proveedor','orden_compras.costo_total','orden_compras.estado as estadoComp','orden_compras.pdf as ordPDF','orden_compras.comprobante_pago','orden_compras.estado' ,'orden_compras.created_at')
        ->join('users','orden_compras.admin_id','=','users.id')
        ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
        ->join('proveedores','orden_compras.proveedor_id','=','proveedores.id_proveedor')
        ->where('requisiciones.estado','!=','Rechazado')
        ->orderBy('orden_compras.created_at','desc')
        ->get();

        // Cargar y mostrar la vista con los datos necesarios para la revisión de órdenes de compra
        return view ('Gerencia General.ordenesCompras',compact('ordenes'));
    }

    /*
      Recupera y muestra información detallada sobre los pagos fijos, junto con los servicios y proveedores asociados.

      Este método se encarga de obtener un listado de todos los pagos fijos registrados en el sistema, incluyendo detalles
      completos como el ID del pago, el servicio asociado, el nombre del proveedor, y los comprobantes de pago. Adicionalmente,
      se recupera información sobre todos los servicios activos y sus proveedores asociados, que es esencial para facilitar
      la gestión y revisión de pagos.

      Devuelve la vista 'Gerencia General.pagos', pasando los datos de los pagos, servicios y proveedores para su visualización.
    */
    public function pagos(){
        // Obtener los pagos fijos en relación de sus proveedores y servicios al cual va dirigido.
        $pagos = Pagos_Fijos::select('pagos_fijos.*','servicios.id_servicio','servicios.nombre_servicio','proveedores.nombre','pagos_fijos.comprobante_pago')
        ->join('servicios','pagos_fijos.servicio_id','servicios.id_servicio')
        ->join('proveedores','servicios.proveedor_id','proveedores.id_proveedor')
        ->orderBy('id_pago','desc')
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

        // Cargar y mostrar la vista con los datos necesarios
        return view('Gerencia General.pagos',compact('pagos','servicios','proveedores'));
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

      Este método recibe una petición HTTP que contiene el tipo de reporte solicitado (fechas especificas y filtro de dptos).
      Dependiendo del tipo de reporte, realiza una consulta a la base de datos para recuperar las requisiciones dentro
      del periodo de tiempo especificado, junto con información de los departamentos seleccionados.
      Los datos recopilados se serializan y almacenan en un archivo, luego se genera un PDF del reporte utilizando una
      plantilla específica. Finalmente, el contenido del PDF se envía al navegador para su visualización o descarga.

      @return void
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

        // Para cada requsicion que obtenga en la consulta
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

            $rowNumber++;
        }

        // Aplicar filtros a la taibla de datos
        $sheet->setAutoFilter('A5:I5');

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
        $queryPendientes = Requisiciones::select('requisiciones.id_requisicion','requisiciones.notas','requisiciones.estado as estadoReq','requisiciones.created_at as fechaReq',
            'orden_compras.id_orden','orden_compras.created_at as fecha_orden', 'orden_compras.estado as estadoOrd','orden_compras.costo_total',
            'users.nombres', 'users.apellidoP', 'users.departamento',
            'unidades.id_unidad','unidades.tipo','unidades.n_de_permiso',
            'proveedores.nombre')
            ->join('users', 'requisiciones.usuario_id', '=', 'users.id')
            ->leftJoin('unidades', 'requisiciones.unidad_id', '=', 'unidades.id_unidad')
            ->leftJoin('cotizaciones', 'cotizaciones.requisicion_id', '=', 'requisiciones.id_requisicion')
            ->leftJoin('orden_compras', 'orden_compras.cotizacion_id', '=', 'cotizaciones.id_cotizacion')
            ->leftJoin('proveedores', 'orden_compras.proveedor_id', '=', 'proveedores.id_proveedor')
            ->where(function ($query) use ($fInicio, $fFin) {
                $query->whereBetween('requisiciones.created_at', [$fInicio, $fFin])
                    ->orWhere('orden_compras.created_at', null);
            })
            ->where('orden_compras.estado', '=', null);

        // Construir la consulta con INNER JOIN
        $queryPagados= Requisiciones::select('requisiciones.id_requisicion','requisiciones.notas','requisiciones.estado as estadoReq','requisiciones.created_at as fechaReq',
            'orden_compras.id_orden','orden_compras.created_at as fecha_orden', 'orden_compras.estado as estadoOrd','orden_compras.costo_total',
            'users.nombres', 'users.apellidoP', 'users.departamento',
            'unidades.id_unidad','unidades.tipo','unidades.n_de_permiso',
            'proveedores.nombre')
            ->leftJoin('cotizaciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
            ->leftJoin('orden_compras','orden_compras.cotizacion_id','cotizaciones.id_cotizacion')
            ->leftJoin('proveedores','orden_compras.proveedor_id','proveedores.id_proveedor')
            ->join('users', 'requisiciones.usuario_id', '=', 'users.id')
            ->leftJoin('unidades','requisiciones.unidad_id','unidades.id_unidad')
            ->where(function($query) use ($fInicio, $fFin) {
                $query->whereBetween('requisiciones.created_at', [$fInicio, $fFin])
                    ->orWhere('orden_compras.created_at', null);
            })
            ->where('orden_compras.estado', '=', 'Pagado');

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
        $sheet->setCellValue('A7', 'Mes');
        $sheet->setCellValue('B7', 'Semana');
        $sheet->setCellValue('C7', 'Rango');
        $sheet->setCellValue('D7', 'Fecha Requisicion');
        $sheet->setCellValue('E7', 'Área');
        $sheet->setCellValue('F7', 'Solicitante');
        $sheet->setCellValue('G7', 'Requisicion');
        $sheet->setCellValue('H7', 'Estado');
        $sheet->setCellValue('I7', 'orden compra');
        $sheet->setCellValue('J7', 'Proveedor');
        $sheet->setCellValue('K7', 'Costo');
        $sheet->setCellValue('L7', 'Unidad');

        // Establecer el color de fondo de los encabezados
        $sheet->getStyle('A7:L7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF99C6F1');

        // Centrar los encabezados
        $sheet->getStyle('A7:L7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a los encabezados
        $sheet->getStyle('A7:L7')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

         // Ajustar el tamaño de las columnas al contenido
         foreach (range('A', 'L') as $columnID) {
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
            $fecha = date('d/m/Y', strtotime($orden->fechaReq));

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

            $sheet->setCellValue('A' . $rowNumber, $nombreMes);
            $sheet->setCellValue('B' . $rowNumber, $numeroSemana);
            $sheet->setCellValue('C' . $rowNumber, $lunes.' - '.$viernes);
            $sheet->setCellValue('D' . $rowNumber, $fecha);
            $sheet->setCellValue('E' . $rowNumber, $orden->departamento);
            $sheet->setCellValue('F' . $rowNumber, $nombreCompleto);
            $sheet->setCellValue('G' . $rowNumber, $orden->id_requisicion);
            $sheet->setCellValue('H' . $rowNumber, $orden->estadoReq);
            $sheet->setCellValue('I' . $rowNumber, $orden->id_orden);
            $sheet->setCellValue('J' . $rowNumber, $orden->nombre);
            $sheet->setCellValue('K' . $rowNumber, $orden->costo_total);
            $sheet->setCellValue('L' . $rowNumber, $unidad);

            // Centrar las celdas de la fila actual
            $sheet->getStyle('A' . $rowNumber . ':L' . $rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Añadir bordes normales a las celdas de los datos
            $sheet->getStyle('A' . $rowNumber . ':L' . $rowNumber)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Aumenta en 1 la fila.
            $rowNumber++;
        }

        // Aplicar filtros a la tabla de datos
        $sheet->setAutoFilter('A7:L7');

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
        $sheet->setCellValue('A7', 'Mes');
        $sheet->setCellValue('B7', 'Semana');
        $sheet->setCellValue('C7', 'Rango');
        $sheet->setCellValue('D7', 'Fecha Requisicion');
        $sheet->setCellValue('E7', 'Área');
        $sheet->setCellValue('F7', 'Solicitante');
        $sheet->setCellValue('G7', 'Requisicion');
        $sheet->setCellValue('H7', 'Estado');
        $sheet->setCellValue('I7', 'orden compra');
        $sheet->setCellValue('J7', 'Proveedor');
        $sheet->setCellValue('K7', 'Costo');
        $sheet->setCellValue('L7', 'Unidad');

        // Establecer el color de fondo de los encabezados
        $sheet->getStyle('A7:L7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF99C6F1');

        // Centrar los encabezados
        $sheet->getStyle('A7:L7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a los encabezados
        $sheet->getStyle('A7:L7')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

         // Ajustar el tamaño de las columnas al contenido
         foreach (range('A', 'L') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Calcular el total de costos pendientes
        $totalPendientes = $datosGastosFinalizados->sum('costo_total');

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

            $sheet->setCellValue('A' . $rowNumber, $nombreMes);
            $sheet->setCellValue('B' . $rowNumber, $numeroSemana);
            $sheet->setCellValue('C' . $rowNumber, $lunes.' - '.$viernes);
            $sheet->setCellValue('D' . $rowNumber, $fecha);
            $sheet->setCellValue('E' . $rowNumber, $orden->departamento);
            $sheet->setCellValue('F' . $rowNumber, $nombreCompleto);
            $sheet->setCellValue('G' . $rowNumber, $orden->id_requisicion);
            $sheet->setCellValue('H' . $rowNumber, $orden->estadoReq);
            $sheet->setCellValue('I' . $rowNumber, $orden->id_orden);
            $sheet->setCellValue('J' . $rowNumber, $orden->nombre);
            $sheet->setCellValue('K' . $rowNumber, $orden->costo_total);
            $sheet->setCellValue('L' . $rowNumber, $unidad);

            // Centrar las celdas de la fila actual
            $sheet->getStyle('A' . $rowNumber . ':L' . $rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Añadir bordes normales a las celdas de los datos
            $sheet->getStyle('A' . $rowNumber . ':L' . $rowNumber)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Aumenta en 1 la fila.
            $rowNumber++;
        }

        // Aplicar filtros a la tabla de datos
        $sheet->setAutoFilter('A7:L7');

        // Calcular y escribir el total de los costos al final de la tabla
        $sheet->setCellValue('J' . $rowNumber, 'Total');
        $sheet->setCellValue('K' . $rowNumber, '=SUM(K8:K' . ($rowNumber - 1) . ')');

        // Formato de moneda para la celda del total
        $sheet->getStyle('K' . $rowNumber)->getNumberFormat()->setFormatCode('$#,##0.00');

        // Centrar las celdas del total
        $sheet->getStyle('J' . $rowNumber . ':K' . $rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Añadir bordes gruesos a la fila del total
        $sheet->getStyle('J' . $rowNumber . ':K' . $rowNumber)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

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
