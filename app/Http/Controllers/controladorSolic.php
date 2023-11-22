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
    //VISTAS DE LAS TABLAS
    
    public function tableRequisicion(){
        $solicitudes = Requisiciones::where('usuario_id',session('loginId'))
        ->orderBy('created_at','desc')
        ->where('estado','!=','Eliminado')
        ->get();
        return view('Solicitante.requisiciones',compact('solicitudes'));
    }

    public function createSolicitud(){
        $datos = session()->get('datos', []);
        $unidades = Unidades::select('id_unidad')->where('estado','Activo')->where('estatus','1')->get();
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
                'dpto' =>session('dpto')
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

            return redirect('solicitud')->with('solicitado','solicitado');
        }
    }

    public function deleteSolicitud($id){
        requisiciones::where('id_requisicion',$id)->delete();

        return back()->with('eliminado','eliminado');  
    }

    public function solicitudAlm(){
        $datosAlm = session()->get('datosAlm', []);
        $refacciones = Almacen::where('estatus','1')->get();
        $unidades = Unidades::select('id_unidad')->where('estado','Activo')->where('estatus','1')->get();
        return view('Solicitante.solicAlm',compact('refacciones','datosAlm','unidades'));
    }

    public function ArraySolicitudAlm(Request $req){

        $refaccion = Almacen::where('id_refaccion',$req->input('refaccion'))->first();

        if($refaccion->stock >= $req->input('cantidad') && $req->input('cantidad') > 0){
            $datosAlm = session()->get('datosAlm', []);
            $refaccion = $req->input('refaccion');
            $nombre = $req->input('nombre');
            $cantidad = $req->input('cantidad');

            $datosAlm[] = [
                'id'=>$refaccion,
                'nombre'=>$nombre,
                'cantidad' => $cantidad,        
            ];

            session()->put('datosAlm', $datosAlm);

            return back();
        } else{
            return back()->with('insuficiente','insuficiente');
        }
    }

    public function deleteArraySolAlm($index){
        $datosAlm = session()->get('datosAlm', []);

        if (isset($datosAlm[$index])) {
            unset($datosAlm[$index]);
        }
        session()->put('datosAlm', $datosAlm);

        return back();
    }

    public function requisicionAlm(Request $req){
        $datosRequisicion = session()->get('datosAlm', []);

        if (empty($datosRequisicion)){
            return back()->with('vacio','vacio');
        }else {
            $Nota = $req->input('Notas');

            $datosEmpleado[] = [
                'idEmpleado' => session('loginId'),
                'nombre' => session('loginNombre'),
                'rol' => session('rol'),
                'dpto' =>session('dpto')
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
            include(public_path('/pdf/TCPDF-main/examples/RequisicionAlm.php'));
            ob_end_clean();            

            Requisiciones::create([
                "usuario_id"=>session('loginId'),
                "unidad_id" => $req->input('unidad'),
                "pdf" => $rutaDescargas,
                "estado"=> "En Almacen",
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

            session()->forget('datosAlm');

            return redirect('solicitud')->with('solicitado','solicitado');
        }
    }

    public function tableSalidas(){
        $salidas = Salidas::get()
        ->join('requisiciones','salidas.requisicion_id','=','requisicion.id_requisicion')
        ->where('requisiciones.usuario_id',session('loginId'));
        return view('Solicitante.salidas',compact('salidas'));
    }

    public function almacen(){
        $refacciones = Almacen::get();
        return view('Solicitante.almacen',compact('refacciones'));
    }
}