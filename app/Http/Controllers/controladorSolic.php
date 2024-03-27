<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;
use Carbon\Carbon;
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
        $completas = Requisiciones::where('estado', 'Comprado')->where('usuario_id',session('loginId'))->count();

        // Cuenta el número de requisiciones pendientes (estado diferente de 'Comprado') para el usuario actual
        $pendiente = Requisiciones::where('estado','!=', 'Comprado')
        ->where('estado','!=', 'Rechazado')
        ->where('usuario_id',session('loginId'))
        ->count();

        // Devuelve la vista 'Solicitante.index', pasando los conteos de requisiciones completas y pendientes
        return view("Solicitante.index",[
            'pendientes'=>$pendiente,
            'completas'=>$completas]);
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
        $unidad = Requisiciones::select('id_unidad','marca','modelo','notas')
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

        $N_articulos = Articulos::where('requisicion_id',$rid)->count();

        if($N_articulos == 0){
            Requisiciones::where('id_requisicion',$rid)->update([
                "estado"=>"Incompleta",
                "updated_at"=>Carbon::now(),
            ]);
        }
        // Redirige al usuario a la página anterior tras la eliminación
        return back();
    }

    public function updateSolicitud (Request $req, $id){

        $N_articulos = Articulos::where('requisicion_id',$id)->count();

        if($N_articulos == 0 ){
            return back ()->with('error','error');
        } else {
            // Recopilación de información de la requisición y generación del nuevo PDF
            $notas = $req->Notas;
            $datos = Requisiciones::select('requisiciones.id_requisicion','requisiciones.unidad_id','requisiciones.created_at','requisiciones.pdf','requisiciones.notas','requisiciones.usuario_id','users.nombres','users.apellidoP','users.apellidoM','users.rol','users.departamento')
            ->join('users','requisiciones.usuario_id','=','users.id')
            ->where('requisiciones.id_requisicion',$id)
            ->first();

            //Guarda la ruta del archivo PDF de la requisicion
            $fileToDelete = public_path($datos->pdf);

            //Si existe el archivo lo elimina 
            if (file_exists($fileToDelete)) {
                unlink($fileToDelete);
            }

            //Recupera los articulos por requisicion
            $articulos = Articulos::where('requisicion_id',$id)->get();

            //Valida si la requisicion tiene una unidad asignada y recupera su información
            if(!empty($datos->unidad_id)){
                $unidad = Unidades::where('id_unidad',$datos->unidad_id)->first();
            }

            // Nombre y ruta del archivo en laravel
            $nombreArchivo = 'requisicion_' . $datos->id_requisicion . '.pdf';
            $rutaDescargas = 'requisiciones/' . $nombreArchivo;

            // Incluir el archivo Requisicion.php y pasar la ruta del archivo como una variable
            ob_start(); // Iniciar el búfer de salida
            include(public_path('/pdf/TCPDF-main/examples/RequisicionEditada.php'));
            ob_end_clean();    

            // Actualización del estado de la requisición a 'Solicitado'
            Requisiciones::where('id_requisicion',$id)->update([
                "estado"=>'Solicitado',
                "pdf"=>$rutaDescargas,
                "notas"=>$notas,
                "updated_at"=>Carbon::now(),
            ]);

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

    public function pagosFijos(){
        $pagos = Pagos_Fijos::select('pagos_fijos.*','servicios.id_servicio','servicios.nombre_servicio','proveedores.nombre','pagos_fijos.comprobante_pago')
        ->where('pagos_fijos.usuario_id',session('loginId'))
        ->join('servicios','pagos_fijos.servicio_id','servicios.id_servicio')
        ->join('proveedores','servicios.proveedor_id','proveedores.id_proveedor')
        ->orderBy('id_pago','desc')
        ->get();

        $servicios = Servicios::select('servicios.id_servicio','servicios.nombre_servicio','proveedores.id_proveedor','proveedores.nombre')
        ->join('proveedores','servicios.proveedor_id','=','proveedores.id_proveedor')
        ->orderBy('servicios.nombre_servicio','asc')
        ->where('servicios.estatus','1')
        ->where('servicios.usuario_id',session('loginId'))
        ->get();

        $proveedores = Proveedores::where('estatus','1')
        ->orderBy('nombre','asc')
        ->get();

        return view('Solicitante.pagos',compact('pagos','servicios','proveedores'));
    }

    public function crearPago(){
        $servicios = Servicios::select('servicios.id_servicio','servicios.nombre_servicio','proveedores.nombre')
        ->join('proveedores','servicios.proveedor_id','=','proveedores.id_proveedor')
        ->orderBy('servicios.nombre_servicio','asc')
        ->where('servicios.estatus','1')
        ->get();

        $proveedores = Proveedores::where('estatus','1')
        ->orderBy('nombre','asc')
        ->get();

        return view('Solicitante.crearPago',compact('servicios','proveedores'));
    }

    public function createServicio(Request $req){
        Servicios::create([
            "nombre_servicio"=>$req->nombre,
            "proveedor_id"=>$req->proveedor,
            "usuario_id"=>session('loginId'),
            "estatus"=>1,
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        return back()->with('servicio','servicio');
    }

    public function editServicio(Request $req, $id){
        Servicios::where('id_servicio',$id)->update([
            "nombre_servicio"=>$req->input('nombre'),
            "proveedor_id"=>$req->input('proveedor'),
            "updated_at"=>Carbon::now(),
        ]);

        return back()->with('servEditado','servEditado');
    }

    public function deleteServicio ($id){
        Servicios::where('id_servicio',$id)->update([
            "estatus"=>"0",
            "updated_at"=>Carbon::now(),
        ]);

        return back()->with('servDelete','servDelete');
    }

    public function createPago (Request $req){
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
        Pagos_Fijos::where('id_pago',$id)->delete();

        return back()->with('eliminado','eliminado');
    }

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
            $ajuste_frenos + $engrasado_chasis) / 12; // Dividiendo por 12 para obtener el promedio de los 12 parámetros

            $unidad->tiempo = $promedio; // Agregar el promedio como un nuevo atributo
        });
        
        // Carga y muestra la vista con el listado de unidades activas
        return view('Solicitante.mantenimiento',compact('unidades'));
    }

    public function infoMantenimiento ($id){
        $unidad = Unidades::where('id_unidad',$id)->first();
        $kmInicial = $unidad->kilometraje;
        $servicio = CamionServicioPreventivo::where('unidad_id',$id)->first();

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

        return view('Solicitante.infoMantenimiento',compact('unidad','datos'));
    }

    public function actualizarkms(Request $request)
    {
        // Validar que se haya subido un archivo
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Obtener el archivo subido
        $file = $request->file('file');

        // Cargar el archivo Excel
        $spreadsheet = IOFactory::load($file);

        // Obtener la primera hoja del archivo
        $sheet = $spreadsheet->getActiveSheet();

        // Obtener el número total de filas y columnas
        $highestRow = $sheet->getHighestDataRow();
        $highestColumn = $sheet->getHighestDataColumn();        

        // Iterar sobre las filas y leer los datos
        for ($row = 1; $row <= $highestRow; $row++) {
            // Obtener los datos de la celda A (id_unidad) y G (kilometraje) de cada fila
            $id_unidad = $sheet->getCell('A' . $row)->getValue();
            $kilometraje = $sheet->getCell('G' . $row)->getValue();

            // Verificar si la unidad existe en la base de datos
            $unidad = Unidades::where('id_unidad', $id_unidad)->first();

            if ($unidad) {
                // Si la unidad existe, actualizar el kilometraje
                Unidades::where('id_unidad',$unidad->id_unidad)->update([
                    "kilometraje" => $kilometraje,
                    "updated_at" => Carbon::now(),
                ]);
            }
        }

        return redirect()->back()->with('importado', 'importado');
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
}