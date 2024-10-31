<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\Controller; // Asegúrate de incluir esta línea correctamente
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;
use Carbon\Carbon;
use App\Models\Cotizaciones;
use App\Models\Requisiciones;
use App\Models\Unidades;
use App\Models\Salidas;
use App\Models\Almacen;
use App\Models\Proveedores;
use App\Models\Servicios;
use App\Models\Pagos_Fijos;
use App\Models\Articulos;
use App\Models\CamionServicioPreventivo;
use App\Models\Logs;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Session;

class controladorSolic extends Controller
{

    /*
     TODO: Método del controlador para mostrar la página de inicio del solicitante.

      Este método recupera el número de requisiciones completas y pendientes
      para el usuario actualmente autenticado y las pasa a la vista para su visualización.
      Las requisiciones completas son aquellas con un estado 'Comprado',mientras que las
      pendientes son todas las demás a excepcion de las rechazadas.
    */
    public function index(){
        // Cuenta el número de requisiciones completas (estado 'Comprado') para el usuario actual
        $completas = Requisiciones::where('estado', 'Finalizado')->where('usuario_id',session('loginId'))->count();

        // Cuenta el número de requisiciones pendientes (estado diferente de 'Comprado') para el usuario actual
        $pendiente = Requisiciones::where('estado','!=', 'Finalizado')
        ->where('estado','!=', 'Rechazado')
        ->where('usuario_id',session('loginId'))
        ->count();

        // Devuelve la vista 'Solicitante.index', pasando los conteos de requisiciones completas y pendientes
        return view("Solicitante.index",[
            'pendientes'=>$pendiente,
            'completas'=>$completas]);
    }

    public function tableSolicitudes(){
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
        return view('Solicitante.solicitudes',compact('solicitudes'));
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
        return view('Solicitante.cotizaciones',compact('cotizaciones','id'));
    }

    public function selectCotiza($id,$sid){

        Cotizaciones::where('id_cotizacion', '!=', $id)
            ->where('requisicion_id', $sid)
            ->update([
            "estatus" => "0",
            "updated_at" => Carbon::now()
        ]);

        // Actualizar el estado de la solicitud a "Pre Validado"
        Requisiciones::where('id_requisicion',$sid)->update([
            "estado" => "Validado",
            "updated_at" => Carbon::now()
        ]);

        // Redirige al usuario a la lista de solicitudes con una sesión flash indicando que la cotización ha sido pre-validada o validada.
        return redirect('requisiciones/consulta')->with('validacion','validacion');
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
    public function rechazarCont(Request $req, $id,$sid){
        // Actualización del estatus de todas las cotizaciones asociadas a la solicitud
        Cotizaciones::where('id_cotizacion', $id)
        ->delete();

        // Redirige al usuario a la lista de solicitudes con un mensaje de confirmación del rechazo.
        return back()->with('rechazaC','rechazaC');
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

        // Retorna la vista 'Solicitante.requisiciones', pasando las solicitudes obtenidas
        return view('Solicitante.requisiciones',compact('solicitudes'));
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
        return view('Solicitante.crearSolicitud',compact('unidades','datos'));
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
            return redirect('solicitud')->with('solicitado','solicitado');
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
        $unidad = Requisiciones::select('id_unidad','marca','modelo','notas','requisiciones.mantenimiento as mant')
        ->leftJoin('unidades','requisiciones.unidad_id','=','unidades.id_unidad')
        ->where('requisiciones.id_requisicion',$id)
        ->first();

        // Recuperación de todas las unidades activas
        $unidades = Unidades::where('estado','Activo')->where('estatus','1')->get();

        // Carga de la vista de edición con los datos recopilados
        return view('Solicitante.editRequisicion',compact('articulos','unidades','unidad','id'));
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
                    "notas"=>$notas,
                    "estado"=>'Solicitado',
                    "mantenimiento"=>$mantenimiento,
                    "updated_at"=>Carbon::now(),
                ]);
            } else{
                Requisiciones::where('id_requisicion',$id)->update([
                    "estado"=>'Solicitado',
                    "pdf"=>$rutaDescargas,
                    "notas"=>$notas,
                    "updated_at"=>Carbon::now(),
                ]);
            }

            // Redirección al usuario con mensaje de éxito
            return redirect('solicitud')->with('editado','editado');
        }
    }

    /*
      TODO: Elimina una solicitud específica de la base de datos.

      Este método busca una requisición por su ID único y la elimina de la base de datos.
      Es útil para permitir que los usuarios eliminen solicitudes que ya no son necesarias o fueron creadas por error.
      Después de eliminar la solicitud, el método redirige al usuario a la página anterior con un mensaje
      de confirmación para indicar que la acción se ha completado con éxito.

      @param  int  $id  El ID de la requisición que se desea eliminar.

      Redirige al usuario a la página anterior con una sesión flash que indica que la solicitud ha sido eliminada.
    */
    public function deleteSolicitud($id){
        // Elimina la requisición específica por su ID
        Requisiciones::where('id_requisicion',$id)->delete();

        // Redirige al usuario a la página anterior con un mensaje de confirmación
        return back()->with('eliminado','eliminado');
    }

    /*
      Recupera y muestra una lista detallada de todos los pagos fijos y servicios asociados al usuario actual.

      Este método consulta la base de datos para obtener un listado completo de los pagos fijos que están registrados bajo el ID
      del usuario que ha iniciado sesión. Los datos incluyen detalles del pago, información del servicio asociado y el proveedor
      correspondiente. Además, se recuperan los servicios activos asociados al mismo usuario, proporcionando una vista comprensiva
      de los compromisos financieros y las obligaciones de servicio del usuario.

      Devuelve la vista 'Solicitante.pagos', pasando los datos de los pagos, servicios y proveedores para su visualización.
    */
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
        return view('Solicitante.pagos',compact('pagos','servicios','proveedores'));
    }

    /*
      Prepara y muestra la vista para la creación de un nuevo pago.

      Este método recupera todos los servicios activos y sus proveedores correspondientes para que los usuarios puedan seleccionar
      de una lista al momento de crear un nuevo pago fijo. La selección de servicios y proveedores solo incluye aquellos que están activos,
      asegurando que los datos presentados estén actualizados y sean relevantes. Estos datos se utilizan para llenar los campos en el formulario
      de creación de pago, simplificando el proceso de entrada de datos por parte del usuario.

      Devuelve la vista 'Solicitante.crearPago', pasando las listas de servicios y proveedores activos para su visualización y selección.
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
        return view('Solicitante.crearPago',compact('servicios','proveedores'));
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

        return redirect()->route('pagos')->with('pago','pago');
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

    public function deletePago($id){
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
      TODO: Recupera todas las refacciones disponibles en el almacén y las muestra en la vista.

      Este método consulta la base de datos para obtener todos los registros de la tabla 'Almacen',
      que representa las refacciones o artículos disponibles en el almacén. Una vez obtenidos estos datos,
      los pasa a la vista 'Solicitante.almacen' para que puedan ser visualizados por el usuario.
      Es útil para proporcionar una lista completa de los artículos disponibles en el almacén
      y permitir que los usuarios vean lo que hay en stock.

      Retorna la vista 'Solicitante.almacen', pasando los datos de las refacciones disponibles.
    */
    public function almacen(){
        // Obtiene todos los registros de refacciones disponibles en el almacén
        $refacciones = Almacen::get();

        // Retorna la vista 'Solicitante.almacen', pasando la lista de refacciones
        return view('Solicitante.almacen',compact('refacciones'));
    }

    /*
      Recupera y muestra un listado de unidades activas, excluyendo una unidad específica.

      Este método consulta la base de datos para obtener un listado de todas las unidades que están marcadas como activas
      (estatus '1'), excluyendo la unidad con ID '1' por razones específicas de negocio o de la aplicación. Además, las unidades
      activas se ordenan en orden ascendente por su ID para facilitar su visualización y gestión.

      Retorna la vista 'Solicitanteunidad', pasando el listado de unidades activas para su visualización.
    */
    public function tableUnidades(){
        // Recupera las unidades activas, excluyendo la unidad con ID '1' y ordenándolas por ID de manera ascendente
        $unidades = Unidades::where('estatus','1')
        ->where('id_unidad','!=','1')
        ->where('estado','Activo')->orderBy('id_unidad','asc')
        ->get();

        // Carga y muestra la vista con el listado de unidades activas
        return view('Solicitante.unidad',compact('unidades'));
    }

    /*
      Muestra la vista para la creación de una nueva unidad.

      Este método se encarga de cargar y presentar la vista que contiene el formulario utilizado para la creación
      de nuevas unidades dentro del sistema. La vista proporcionará los campos necesarios para capturar la información
      esencial de la nueva unidad.

      Retorna la vista 'SolicitantecrearUnidad', que contiene el formulario para la creación de una nueva unidad.
    */
    public function CreateUnidad(){
        // Cargar y mostrar la vista con el formulario de creación de unidad
        return view('Solicitante.crearUnidad');
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
        return redirect()->route('unidadesSoli')->with('regis','regis');
    }

    /*
      Muestra la vista para editar los detalles de una unidad específica.

      Este método se encarga de recuperar los detalles de una unidad específica, identificada por su ID, de la base de datos.
      La recuperación de esta información es crucial para pre-rellenar el formulario de edición en la vista con los datos actuales
      de la unidad, permitiendo así que los administradores o los usuarios con los permisos adecuados realicen cambios en la información
      de la unidad como tipo, estado, año, marca, modelo, características, número de serie, y número de permiso.

      @param  int  $id  El ID de la unidad cuyos detalles se van a editar.

      Retorna la vista 'SolicitanteeditarUnidad', pasando los detalles de la unidad específica para su edición.
    */
    public function editUnidad($id, $from){
        // Recupera los detalles de la unidad específica por su ID
        $unidad= Unidades::where('id_unidad',$id)->first();

        // Almacenar la URL de origen en la sesión
        session(['url_origen' => $from]);

        // Carga y muestra la vista con el formulario de edición de unidad, pasando los detalles de la unidad
        return view('Solicitante.editarUnidad',compact('unidad'));
    }

    /*
      Actualiza los detalles de una unidad específica en la base de datos con la información proporcionada por el formulario.

      Este método recibe datos de un formulario a través de una petición HTTP, incluyendo el ID de la unidad, tipo, estado,
      año de la unidad, marca, modelo, características, número de serie, y número de permiso. Utiliza estos datos para
      actualizar el registro de la unidad específica en la base de datos, identificado por el ID proporcionado.

      @param  int  $id  El ID de la unidad que se va a actualizar.

      Redirige al usuario a la lista de unidades con una sesión flash que indica que la unidad ha sido actualizada exitosamente.
    */
    public function updateUnidad(Request $req, $id ){
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

        // Obtener la URL de origen desde la sesión
        $url_origen = session('url_origen', 'default_route');

        // Redirigir a la URL de origen
        if ($url_origen == 'unidades') {
            return redirect()->route('unidadesSoli')->with('update', 'update');
        } elseif ($url_origen == 'mantenimiento') {
            return redirect()->route('manteniento')->with('update', 'update');
        }
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

      Retorna la vista 'SolicitanteactivaUnidad', pasando el listado de unidades inactivas para su visualización y potencial activación.
    */
    public function activarUnidad(){
        // Recupera las unidades marcadas como inactivas
        $unidades = Unidades::where("estado",'Inactivo')->get();

        // Carga y muestra la vista con el listado de unidades inactivas
        return view('Solicitante.activaUnidad',compact('unidades'));
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
        return redirect()->route('unidadesSoli')->with('activado','activado');
    }
}
