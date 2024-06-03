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
        ->select('unidades.id_unidad','unidades.tipo','unidades.estado','unidades.anio_unidad','unidades.marca','unidades.modelo', 'unidades.kilometraje', 'servicios.km_mantenimiento', 'servicios.contador', 'servicios.created_at as fecha_ultimo_servicio')
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
            ->select('unidades.id_unidad','unidades.tipo','unidades.estado','unidades.anio_unidad','unidades.marca','unidades.modelo', 'unidades.kilometraje', 'servicios.km_mantenimiento', 'servicios.contador', 'servicios.created_at as fecha_ultimo_servicio')
            ->where('unidades.estatus', '1')
            ->where('unidades.id_unidad', $id) // Filtrar por la unidad específica
            ->where('unidades.estado', 'Activo')
            ->orderBy('unidades.id_unidad', 'asc')
            ->first();

        if ($unidad->tipo === "CAMIÓN"){
            $refacciones = refMantenimientos::where('tipo_mant',1)->get();            
        } else {
            $refacciones = refMantenimientos::where('tipo_mant',2)->get();
        }

        $refacciones->each(function ($refaccion) {
            $ciclo_ref = $refaccion->tiempo_cambio /15000;
            
            $refaccion->ciclo = $ciclo_ref;
        });

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

        $alert = DB::table('historial_mants')
        ->select('historial_mants.created_at','historial_mants.notas')   
        ->join('programaciones','historial_mants.programacion_id','=','programaciones.id_programacion')
        ->where('programaciones.unidad_id',$unidad->id_unidad)
        ->where('historial_mants.estatus','2')
        ->orderBy('historial_mants.created_at','desc')
        ->first();

        return view('Solicitante.infoMantenimiento',compact('unidad','refacciones','programacion','alert'));
    }

    public function programMant(Request $req, $id){

        programaciones::create([
            'fecha_progra'=>$req->date,
            'unidad_id'=>$id,
            'notas'=>$req->notas,
            'estatus'=>'1',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

        return back()->with('programado','programado');
    }

    public function reprogramMant(Request $req, $unidad,$progra){

        programaciones::where('id_programacion',$progra)->update([
            'fecha_progra'=>$req->date,
            'unidad_id'=>$unidad,
            'notas'=>$req->notas,
            'estatus'=>'2',
        ]);

        return back()->with('programado','programado');
    }

    public function registrarMant(Request $req,$progra){
        $unid = programaciones::select('unidades.id_unidad','unidades.tipo')
        ->join('unidades','programaciones.unidad_id','unidades.id_unidad')
        ->where('programaciones.id_programacion',$progra)
        ->first();

        $contador = unidadServicio::where('unidad_id',$unid->id_unidad)->first();
        $contadorfinal = $contador->contador +1;

        if ($unid->tipo === "CAMIÓN"){
            $mantenimiento = 1;
        } else{
            $mantenimiento = 2;
        }

        if ($progra == 'Sp'){
            $programacion = null;
        } else{
            $programacion = $progra;
        }

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

        Unidades::where('id_unidad',$unid->id_unidad)->update([
            'kilometraje'=>$req->kms,
            'updated_at'=>Carbon::now()
        ]);
        
        unidadServicio::where('unidad_id',$unid->id_unidad)->update([
            'km_mantenimiento'=>$req->kms,
            'contador'=>$contadorfinal,
            'updated_at'=>Carbon::now(),
        ]);

        if ($progra != "No hay"){
            Programaciones::where('id_programacion',$progra)->update([
                'estatus'=>'0',
                'updated_at'=>Carbon::now(),
            ]); 
        }        

        return redirect('mantenimiento')->with('registrado','registrado');
    }

    public function updateKilom(Request $req, $id){
        $kilometraje = $req->kilometraje;

        Unidades::where('id_unidad',$id)->update([
            "kilometraje"=>$kilometraje,
            "updated_at"=>Carbon::now(),
        ]);

        return back()->with('kilometraje','kilometraje');
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

        return back()->with('importado', 'importado');
    }   

    public function calendario()
    {
        $unidades = Unidades::select('unidades.id_unidad','unidades.tipo','unidades.estado','unidades.anio_unidad','unidades.marca','unidades.modelo', 'unidades.kilometraje')
        ->where('unidades.estatus', '1')
        ->where('unidades.id_unidad', '!=', '1')
        ->where('unidades.estado', 'Activo')
        ->orderBy('unidades.id_unidad', 'asc')
        ->get();

        return view('Solicitante.calendario',compact('unidades'));
    }

    public function getEvents()
    {
        $events = programaciones::where('estatus', '1')->orwhere('estatus','2')->get()->map(function ($event) {
            return [
                'id' => $event->id_programacion,
                'title' => 'Unidad: ' . $event->unidad_id, // Usar unidad_id en lugar de notas
                'start' => $event->fecha_progra,
                'status' => $event->estatus,
                'url' => route('infoMantenimiento', ['id' => $event->unidad_id]) // URL de redirección
            ];
        });

        return response()->json($events);
    }   

    public function programMantC(Request $req){

        $unidad = $req->unidad;
        $programacion = programaciones::where('unidad_id',$unidad)
        ->where(function($query) {
            $query->where('estatus', '1')
                ->orWhere('estatus', '2');
        })->orderBy('unidad_id', 'desc')
        ->first();

        if (empty($programacion)){
            programaciones::create([
                'fecha_progra'=>$req->date,
                'unidad_id'=>$unidad,
                'notas'=>$req->notas,
                'estatus'=>'1',
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ]);
        } else { 
            programaciones::where('id_programacion',$programacion->id_programacion)->update([
                'fecha_progra'=>$req->date,
                'updated_at'=>Carbon::now()
            ]);
        }
        return back()->with('programado','programado');
    }
}