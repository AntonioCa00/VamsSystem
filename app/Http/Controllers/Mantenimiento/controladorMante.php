<?php

namespace App\Http\Controllers\Mantenimiento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unidades;
use App\Models\unidadServicio;
use App\Models\Mantenimientos;
use App\Models\refMantenimientos;
use App\Models\programaciones;
use App\Models\historialMant;
use Carbon\Carbon;
use DB;

class controladorMante extends Controller
{
    public function mantenimiento (){
        // Recupera las unidades que cumplen con los criterios especificados y las ordena por 'id_unidad'
        // Subconsulta para obtener el último servicio de cada unidad
        $ultimoServicio = unidadServicio:: select('unidad_id', DB::raw('MAX(created_at) as ultima_fecha'))
        ->groupBy('unidad_id');

        // Consulta principal que utiliza la subconsulta
        $unidades = DB::table('unidades')
        ->leftJoinSub($ultimoServicio, 'ultimo_servicio', function($join) {
            $join->on('unidades.id_unidad', '=', 'ultimo_servicio.unidad_id');
        })
        ->leftJoin('unidad_servicios as servicios', function($join) {
            $join->on('unidades.id_unidad', '=', 'servicios.unidad_id')
                ->on('ultimo_servicio.ultima_fecha', '=', 'servicios.created_at');
        })
        ->select('unidades.id_unidad','unidades.tipo','unidades.estado','unidades.anio_unidad','unidades.marca','unidades.modelo','unidades.n_de_permiso','unidades.kilometraje', 'servicios.km_mantenimiento', 'servicios.contador', 'servicios.created_at as fecha_ultimo_servicio')
        ->where('unidades.estatus', '1')
        ->where('unidades.id_unidad', '!=', '1')
        ->where('unidades.estado', 'Activo')
        ->orderBy('unidades.id_unidad', 'asc')
        ->get();

        $unidades->each(function ($unidad) {
            if (empty($unidad->fecha_ultimo_servicio)){
                unidadServicio::create([
                    'unidad_id'=>$unidad->id_unidad,
                    'km_mantenimiento'=>$unidad->kilometraje,
                    'contador'=>0,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ]);
            }

            $diferenciaKm = $unidad->kilometraje - $unidad->km_mantenimiento;
            $porcentajeAbs = (abs($diferenciaKm) / 15000) * 100;

            // Como queremos que el porcentaje disminuya conforme se acercan los valores y que 15 km o más sea el 0% y 0 km sea el 100%, hacemos:
            $porcentajeP = 100 - $porcentajeAbs;

            // Aseguramos que el porcentaje no sea menor que 0% ni mayor que 100%
            $porcentaje = max(0, min(100, $porcentajeP));

            $unidad->porcentaje = $porcentaje;
        });

        // Carga y muestra la vista con el listado de unidades activas
        return view('Solicitante.mantenimiento',compact('unidades'));
    }

    /*
      Obtiene y muestra la información detallada sobre el mantenimiento de una unidad específica.

      Este método recupera información relevante sobre una unidad específica, incluyendo detalles de la unidad,
      su último servicio realizado, las refacciones necesarias basadas en el tipo de unidad, la programación de mantenimiento,
      y cualquier alerta de mantenimiento reciente.

      @param int $id El ID de la unidad de la que se desea obtener información de mantenimiento.

      Devuelve una vista con la información de la unidad, refacciones, programación y alertas de mantenimiento.
    */
    public function infoMantenimiento ($id){
        // Subconsulta para obtener el último servicio de la unidad específica
        $ultimoServicio = unidadServicio::select('unidad_id', DB::raw('MAX(created_at) as ultima_fecha'))
            ->where('unidad_id', $id); // Filtrar por la unidad específica

        // Consulta principal que utiliza la subconsulta
        $unidad = DB::table('unidades')
            ->leftJoinSub($ultimoServicio, 'ultimo_servicio', function($join) {
                $join->on('unidades.id_unidad', '=', 'ultimo_servicio.unidad_id');
            })
            ->leftJoin('unidad_servicios as servicios', function($join) {
                $join->on('unidades.id_unidad', '=', 'servicios.unidad_id')
                    ->on('ultimo_servicio.ultima_fecha', '=', 'servicios.created_at');
            })
            ->select('unidades.id_unidad','unidades.tipo','unidades.n_de_permiso','unidades.estado','unidades.anio_unidad','unidades.marca','unidades.modelo', 'unidades.kilometraje', 'servicios.km_mantenimiento', 'servicios.contador', 'servicios.created_at as fecha_ultimo_servicio')
            ->where('unidades.estatus', '1')
            ->where('unidades.id_unidad', $id) // Filtrar por la unidad específica
            ->where('unidades.estado', 'Activo')
            ->orderBy('unidades.id_unidad', 'asc')
            ->first();

        // Obtener refacciones según el tipo de unidad
        if ($unidad->tipo === "CAMIÓN"){
            $refacciones = refMantenimientos::where('tipo_mant',1)->get();
        } else {
            $refacciones = refMantenimientos::where('tipo_mant',2)->get();
        }

        // Calcular ciclo de cambio de cada refacción
        $refacciones->each(function ($refaccion) {
            $ciclo_ref = $refaccion->tiempo_cambio /15000;

            $refaccion->ciclo = $ciclo_ref;
        });

        // Obtener la programación de mantenimiento más reciente
        $programacion = programaciones::where('unidad_id', $id)
        ->where(function($query) {
            $query->where('estatus', '1')
                ->orWhere('estatus', '2');
        })
        ->orderBy('unidad_id', 'desc')
        ->first();

        if (!empty($programacion)){
            // Convertir la fecha programada al formato deseado
            $programacion->fecha_progra2 = Carbon::createFromFormat('Y-m-d', $programacion->fecha_progra)->format('d/m/Y');
            // Obtener la fecha actual
            $hoy = Carbon::now();
            // Calcular la diferencia en días, permitiendo negativos
            $diferenciaDias = $hoy->diffInDays($programacion->fecha_progra, false);
            // Asignar la diferencia de días a la variable
            $programacion->dias = $diferenciaDias + 1;
        }

        // Obtener los comentarios de mantenimiento más reciente
        $alert = DB::table('historial_mants')
        ->select('historial_mants.created_at','historial_mants.notas')
        ->join('programaciones','historial_mants.programacion_id','=','programaciones.id_programacion')
        ->where('programaciones.unidad_id',$unidad->id_unidad)
        ->where('historial_mants.estatus','2')
        ->orderBy('historial_mants.created_at','desc')
        ->first();

        // Retornar la vista con los datos obtenidos
        return view('Solicitante.infoMantenimiento',compact('unidad','refacciones','programacion','alert'));
    }

    /*
      Programa un mantenimiento para una unidad específica.

      Este método crea una nueva programación de mantenimiento para una unidad específica. Utiliza la información proporcionada en la solicitud
      (fecha de programación, notas) y el ID de la unidad para registrar la programación en la base de datos. La programación se marca inicialmente
      con un estatus de "1" (activo).

      @param int $id El ID de la unidad para la cual se está programando el mantenimiento.

      Redirige al usuario a la página anterior con una notificación que indica que la programación del mantenimiento ha sido exitosa.
    */
    public function programMant(Request $req, $id){

        // Crear una nueva programación de mantenimiento con los datos proporcionados en la solicitud
        programaciones::create([
            'fecha_progra'=>$req->date,
            'unidad_id'=>$id,
            'notas'=>$req->notas,
            'estatus'=>'1',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

        // Redirigir al usuario a la página anterior con una notificación de éxito
        return back()->with('programado','programado');
    }

    /*
      Reprograma un mantenimiento existente para una unidad específica.

      Este método actualiza una programación de mantenimiento existente para una unidad específica. Utiliza la información proporcionada en la solicitud
      (fecha de reprogramación, notas) y los IDs de la unidad y la programación para realizar la actualización en la base de datos. La programación se marca
      con un estatus de "2" (reprogramado).

      @param int $unidad El ID de la unidad para la cual se está reprogramando el mantenimiento.
      @param int $progra El ID de la programación de mantenimiento que se va a actualizar.

      Redirige al usuario a la página anterior con una notificación que indica que la reprogramación del mantenimiento ha sido exitosa.
    */
    public function reprogramMant(Request $req, $unidad,$progra){

        // Actualizar la programación de mantenimiento existente con los datos proporcionados en la solicitud
        programaciones::where('id_programacion',$progra)->update([
            'fecha_progra'=>$req->date,
            'unidad_id'=>$unidad,
            'notas'=>$req->notas,
            'estatus'=>'2',
        ]);

        // Redirigir al usuario a la página anterior con una notificación de éxito
        return back()->with('programado','programado');
    }

    /*
      Registra un mantenimiento realizado para una unidad específica.

      Este método registra los detalles de un mantenimiento realizado para una unidad. Primero, obtiene la información de la unidad y su tipo
      (CAMIÓN u otro). Luego, actualiza el contador de mantenimientos y decide el tipo de mantenimiento basado en el tipo de unidad. Si el mantenimiento
      no está programado, se registra con una programación nula. Finalmente, actualiza los datos de la unidad y su mantenimiento, y marca la programación
      como completada si no es nula.

      @param string $progra El ID de la programación de mantenimiento, o "Sp" si no hay programación, o "No hay" si no se necesita actualización de programación.

      Redirige al usuario a la página de mantenimiento con una notificación que indica que el mantenimiento ha sido registrado exitosamente.
    */
    public function registrarMant(Request $req,$progra){
        // Obtener información de la unidad y su tipo
        $unid = programaciones::select('unidades.id_unidad','unidades.tipo')
        ->join('unidades','programaciones.unidad_id','unidades.id_unidad')
        ->where('programaciones.id_programacion',$progra)
        ->first();

        // Obtener el contador de mantenimientos de la unidad
        $contador = unidadServicio::where('unidad_id',$unid->id_unidad)->first();
        $contadorfinal = $contador->contador +1;

        // Determinar el tipo de mantenimiento basado en el tipo de unidad
        $mantenimiento = ($unid->tipo === "CAMIÓN") ? 1 : 2;

        // Manejar caso de mantenimiento sin programación
        $programacion = ($progra == 'Sp') ? null : $progra;

        // Registrar el mantenimiento en el historial
        historialMant::create([
            'programacion_id'=>$programacion,
            'mantenimiento_id'=>$mantenimiento,
            'estatus'=>$req->estatus,
            'km_final'=>$req->kms,
            'ciclo'=>$contador->contador,
            'notas'=>$req->notas,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);

        // Actualizar el kilometraje de la unidad
        Unidades::where('id_unidad',$unid->id_unidad)->update([
            'kilometraje'=>$req->kms,
            'updated_at'=>Carbon::now()
        ]);

        // Actualizar el mantenimiento de la unidad
        unidadServicio::where('unidad_id',$unid->id_unidad)->update([
            'km_mantenimiento'=>$req->kms,
            'contador'=>$contadorfinal,
            'updated_at'=>Carbon::now(),
        ]);

        // Marcar la programación como completada si no es nula
        if ($progra != "No hay"){
            Programaciones::where('id_programacion',$progra)->update([
                'estatus'=>'0',
                'updated_at'=>Carbon::now(),
            ]);
        }

        // Redirigir al usuario a la página de mantenimiento con una notificación de éxito
        return redirect('mantenimiento')->with('registrado','registrado');
    }

    /*
      Actualiza el kilometraje de una unidad específica.

      Este método actualiza el kilometraje de una unidad con el valor proporcionado en la solicitud. Se utiliza para mantener
      el registro del kilometraje actualizado, lo cual es esencial para la programación de mantenimientos y otras operaciones
      relacionadas con la gestión de flotas. La fecha de la última actualización también se registra automáticamente.

      @param int $id El ID de la unidad cuyo kilometraje se va a actualizar.

      Redirige al usuario a la página anterior con una notificación que indica que el kilometraje ha sido actualizado exitosamente.
    */
    public function updateKilom(Request $req, $id){
        // Obtener el nuevo kilometraje de la solicitud
        $kilometraje = $req->kilometraje;

        // Actualizar el kilometraje de la unidad
        Unidades::where('id_unidad',$id)->update([
            "kilometraje"=>$kilometraje,
            "updated_at"=>Carbon::now(),
        ]);

        // Redirigir al usuario a la página anterior con una notificación de éxito
        return back()->with('kilometraje','kilometraje');
    }

    /*
      Actualiza los kilometrajes de varias unidades desde un archivo Excel.

      Este método permite la carga masiva de datos de kilometraje para diferentes unidades. A través de un archivo Excel,
      se puede actualizar el kilometraje de múltiples unidades en la base de datos. Se valida que el archivo sea del tipo
      correcto (Excel), se lee la primera hoja del archivo, y se itera sobre las filas para actualizar los kilometrajes.
      La fecha de la última actualización se registra automáticamente.

      @param \Illuminate\Http\Request $request La solicitud HTTP que contiene el archivo Excel.

      Redirige al usuario a la página anterior con una notificación que indica que los kilometrajes han sido importados exitosamente.
    */
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

        // Redirigir al usuario a la página anterior con una notificación de éxito
        return back()->with('importado', 'importado');
    }

    /*
      Muestra la vista del calendario con la información de las unidades activas.

      Este método obtiene una lista de unidades activas, excluyendo una unidad específica (con ID 1), y las pasa a la vista
      'Solicitante.calendario'. La consulta filtra las unidades por estado activo y por estado general de activación,
      y las ordena por su ID en orden ascendente. Los datos seleccionados incluyen el ID de la unidad, número de permiso,
      tipo, estado, año de la unidad, marca, modelo y kilometraje.

    */
    public function calendario()
    {
        // Obtener las unidades activas, excluyendo la unidad con ID 1
        $unidades = Unidades::select('unidades.id_unidad','unidades.n_de_permiso','unidades.tipo','unidades.estado','unidades.anio_unidad','unidades.marca','unidades.modelo', 'unidades.kilometraje')
        ->where('unidades.estatus', '1')
        ->where('unidades.id_unidad', '!=', '1')
        ->where('unidades.estado', 'Activo')
        ->orderBy('unidades.id_unidad', 'asc')
        ->get();

        // Devolver la vista 'Solicitante.calendario' con las unidades obtenidas
        return view('Solicitante.calendario',compact('unidades'));
    }

    /*
      Obtiene los eventos de mantenimiento programados y los devuelve en formato JSON.

      Este método recupera los registros de la tabla 'programaciones' que están activos o en estado de reprogramación (estatus 1 o 2),
      y los une con la tabla 'unidades' para obtener detalles adicionales sobre cada unidad. Luego, los datos se formatean en un
      formato adecuado para ser utilizados en un calendario de eventos, añadiendo información relevante como el título, fecha de inicio,
      estado y una URL para más detalles sobre la unidad específica. Finalmente, se devuelve una respuesta JSON con los eventos.

      @return \Illuminate\Http\JsonResponse Una respuesta JSON que contiene los eventos de mantenimiento programados.
    */
    public function getEvents()
    {
        // Obtener las programaciones activas o en reprogramación y mapear los datos para el calendario de eventos
        $events = programaciones::
        join('unidades', 'programaciones.unidad_id', 'unidades.id_unidad')
        ->where('programaciones.estatus', '1')
        ->orWhere('programaciones.estatus', '2')
        ->get()->map(function ($event) {
            // Crear el título del evento basado en el tipo de unidad
            $title = 'Unidad: ';
            if ($event->tipo != 'AUTOMOVIL') {
                $title .= $event->n_de_permiso; // Suponiendo que 'n_de_permiso' es la columna para el permiso del camión
            } else{
                $title .= $event->id_unidad; // Suponiendo que 'placas' es la columna para las placas del automóvil
            }

            return [
                'id' => $event->id_programacion,
                'title' => $title,
                'start' => $event->fecha_progra,
                'status' => $event->estatus,
                'url' => route('infoMantenimiento', ['id' => $event->unidad_id]) // URL de redirección a la información de mantenimiento
            ];
        });

        // Devolver la respuesta JSON con los eventos
        return response()->json($events);
    }

    /*
      Programa o actualiza un mantenimiento para una unidad específica.

      Este método verifica si ya existe una programación activa o en reprogramación para la unidad especificada.
      Si no existe ninguna programación, crea una nueva con los datos proporcionados. Si ya existe una programación,
      actualiza la fecha programada.

      Redirige al usuario a la página anterior con una notificación que indica que el mantenimiento ha sido programado o actualizado.
    */
    public function programMantC(Request $req){

        // Obtener la unidad proporcionada en la solicitud
        $unidad = $req->unidad;

        // Buscar la programación activa o en reprogramación para la unidad especificada
        $programacion = programaciones::where('unidad_id',$unidad)
        ->where(function($query) {
            $query->where('estatus', '1')
                ->orWhere('estatus', '2');
        })->orderBy('unidad_id', 'desc')
        ->first();

        // Verificar si existe una programación para decidir si se crea una nueva o se actualiza la existente
        if (empty($programacion)){
            // Si no existe una programación, crear una nueva
            programaciones::create([
                'fecha_progra'=>$req->date,
                'unidad_id'=>$unidad,
                'notas'=>$req->notas,
                'estatus'=>'1',
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ]);
        } else {
            // Si ya existe una programación, actualizar la fecha programada
            programaciones::where('id_programacion',$programacion->id_programacion)->update([
                'fecha_progra'=>$req->date,
                'updated_at'=>Carbon::now()
            ]);
        }
        // Redirigir al usuario de regreso a la página anterior con un mensaje de éxito
        return back()->with('programado','programado');
    }
}
