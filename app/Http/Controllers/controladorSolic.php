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
use Session;

class controladorSolic extends Controller
{
    public function index(){
        return view('Solicitante.index');
    }

    public function charts(){
        $Octubre = DB::table('compras')
            ->select(DB::raw('SUM(costo) as octubre'))
            ->whereBetween('created_at', ['2023-10-01 00:00:00', '2023-10-31 23:59:59'])
            ->get();
    
        $Septiembre = DB::table('compras')
            ->select(DB::raw('SUM(costo) as septiembre'))
            ->whereBetween('created_at', ['2023-09-01 00:00:00', '2023-09-30 23:59:59'])
            ->get();
    
        $Agosto = DB::table('compras')
            ->select(DB::raw('SUM(costo) as agosto'))
            ->whereBetween('created_at', ['2023-08-01 00:00:00', '2023-08-31 23:59:59'])
            ->get();
    
        $Julio = DB::table('compras')
            ->select(DB::raw('SUM(costo) as julio'))
            ->whereBetween('created_at', ['2023-07-01 00:00:00', '2023-07-31 23:59:59'])
            ->get();
    
        return view('Solicitante.charts', [
            'octubre' => $Octubre,
            'septiembre' => $Septiembre,
            'agosto' => $Agosto,
            'julio' => $Julio,
        ]);
    }    

    //VISTAS DE LAS TABLAS
    
    public function tableRequisicion(){
        $solicitudes = Requisiciones::get();
        return view('Solicitante.requisiciones',compact('solicitudes'));
    }

    public function createSolicitud(){
        $datos = session()->get('datos', []);
        $unidades = Unidades::select('id_unidad')->get();
        return view('Solicitante.crearSolicitud',compact('unidades','datos'));
    }

    public function ArraySolicitud(Request $req){
        $datos = session()->get('datos', []);
        $cantidad = $req->input('Cantidad');
        $descripcion = $req->input('Descripcion');
        $notas = $req->input('Notas');

        $datos[] = [
            'cantidad' => $cantidad,
            'descripcion' => $descripcion,
        ];

        session()->put('datos', $datos);

        return back();
    }

    public function deleteArray($index){
        $datos = session()->get('datos', []);

        if (isset($datos[$index])) {
            unset($datos[$index]);
        }
        session()->put('datos', $datos);

        return back();
    }

    public function requisicion (Request $req){

        $datosRequisicion = session()->get('datos', []);

        if (empty($datosRequisicion)){
            return back()->with('vacio','vacio');
        }else {
            $Nota = $req->input('Notas');

            $datosEmpleado[] = [
                'idEmpleado' => session('loginId'),
                'nombre' => session('loginNombre'),
                'rol' => session('rol'),
            ];

            // Serializar los datos del empleado y almacenarlos en un archivo
            $datosSerializados = serialize($datosEmpleado);
            $rutaArchivo = storage_path('app/datos_empleado.txt');
            file_put_contents($rutaArchivo, $datosSerializados);

            // Nombre y ruta del archivo en laravel
            $numeroUnico = time(); // Genera un timestamp único
            $nombreArchivo = 'requisicion_' . $numeroUnico . '.pdf';
            $rutaDescargas = 'requisiciones/' . $nombreArchivo;

            // Incluir el archivo Requisicion.php y pasar la ruta del archivo como una variable
            ob_start(); // Iniciar el búfer de salida
            include(public_path('/pdf/TCPDF-main/examples/Requisicion.php'));
            ob_end_clean();            

            Requisiciones::create([
                "usuario_id"=>session('loginId'),
                "unidad_id" => $req->input('unidad'),
                "pdf" => $rutaDescargas,
                "estado"=> "Solicitado",
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now(),
            ]);

            DB::table('logs')->insert([
                "user_id"=>session('loginId'),
                "table_name"=>"Solicitudes",
                "action"=>"Se ha registrado una nueva solicitud: ".$Nota,
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);

            session()->forget('datos');

            return redirect('solicitud');
        }
    }

    public function deleteSolicitud($id){
        requisiciones::where('id_requisicion',$id)->delete();

        return back()->with('eliminado','eliminado');  
    }

    public function tableSalidas(){
        $salidas = Salidas::get();
        return view('Solicitante.salidas',compact('salidas'));
    }

    public function almacen(){
        $refacciones = Almacen::get();
        return view('Solicitante.almacen',compact('refacciones'));
    }
}