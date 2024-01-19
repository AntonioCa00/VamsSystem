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
use App\Models\Articulos;
use App\Models\Logs;
use Session;

class controladorSolic extends Controller
{
    public function index(){
        $completas = Requisiciones::where('estado', 'Entregado')->where('usuario_id',session('loginId'))->count();
        $pendiente = Requisiciones::where('estado','!=', 'Entregado')->where('usuario_id',session('loginId'))->count();
        return view("Solicitante.index",[
            'pendientes'=>$pendiente,
            'completas'=>$completas]);
    }
    //VISTAS DE LAS TABLAS
    
    public function tableRequisicion(){
        $solicitudes = Requisiciones::where('requisiciones.usuario_id',session('loginId'))
        ->leftJoin('comentarios','requisiciones.id_requisicion','=','comentarios.requisicion_id')
        ->leftJoin('users','users.id','=','comentarios.usuario_id')
        ->select('requisiciones.id_requisicion','requisiciones.unidad_id','requisiciones.estado','requisiciones.created_at','requisiciones.pdf', 'comentarios.detalles','users.rol','comentarios.created_at as fechaCom')
        ->orderBy('requisiciones.created_at','desc')
        ->get();
        return view('Solicitante.requisiciones',compact('solicitudes'));
    }

    public function createSolicitud(){
        $datos = session()->get('datos', []);
        $unidades = Unidades::where('estado','Activo')
        ->where('estatus','1')
        ->get();
        return view('Solicitante.crearSolicitud',compact('unidades','datos'));
    }

    public function ArraySolicitud(Request $req){
        $datos = session()->get('datos', []);

        $cantidad = $req->input('Cantidad');
        $unidad = $req->input('Unidad');
        $descripcion = $req->input('Descripcion');

        $notas = $req->input('Notas');

        $datos[] = [
            'cantidad' => $cantidad,
            'unidad'=>$unidad,
            'descripcion' => $descripcion,
        ];

        session()->put('datos', $datos);

        return back();
    }

    public function editArray(Request $req, $index){
        $datos = session()->get('datos', []);
    
        $cantidadEditada = $req->input('editCantidad');
        $unidadEditada = $req->input('editUnidad');
        $descripcionEditada = $req->input('editDescripcion');
    
        if (isset($datos[$index])) {
            $datos[$index]['cantidad'] = $cantidadEditada;
            $datos[$index]['unidad'] = $unidadEditada;
            $datos[$index]['descripcion'] = $descripcionEditada;
        }
    
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
                'nombres' => session('loginNombres'),
                'apellidoP' => session('loginApepat'),
                'apellidoM' => session('loginApemat'),
                'rol' => session('rol'),
                'dpto' =>session('departamento')
            ];

            $ultimarequisicion = Requisiciones::select('id_requisicion')->latest('id_requisicion')->first();
            if (empty($ultimarequisicion)){
                $idcorresponde = 1;
            } else {
                $idcorresponde = $ultimarequisicion->id_requisicion + 1;
            }

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
            ob_start(); // Iniciar el búfer de salida para pasar las variables al PDF
            include(public_path('/pdf/TCPDF-main/examples/Requisicion.php'));
            ob_end_clean();    
            
            if(session('departamento') === "Mantenimiento"){
                DB::table('requisiciones')->insert([
                    "usuario_id"=>session('loginId'),
                    "unidad_id" => $req->input('unidad'),
                    "pdf" => $rutaDescargas,                    
                    "estado"=> "Solicitado",
                    "created_at"=>Carbon::now(),
                    "updated_at"=>Carbon::now(),
                ]);
            } else{
                Requisiciones::create([
                    "usuario_id"=>session('loginId'),
                    "pdf" => $rutaDescargas,
                    "estado"=> "Solicitado",
                    "created_at"=>Carbon::now(),
                    "updated_at"=>Carbon::now(),
                ]);
            }

            $ultimaReq = Requisiciones::where('usuario_id',session('loginId'))
            ->orderBy('created_at','desc')
            ->limit(1)
            ->first();

            //Una vez creada la requisicion, agregar los articulos a la tabla, estos ya serán los definitivos        
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

            session()->forget('datos');

            return redirect('solicitud')->with('solicitado','solicitado');
        }
    }

    public function deleteSolicitud($id){
        Requisiciones::where('id_requisicion',$id)->delete();

        return back()->with('eliminado','eliminado');  
    }

    public function solicitudAlm(){
        $datosAlm = session()->get('datosAlm', []);
        $refacciones = Almacen::where('estatus','1')->get();
        $unidades = Unidades::select('id_unidad')->where('estado','Activo')->where('estatus','1')->get();
        return view('Solicitante.solicAlm',compact('refacciones','datosAlm','unidades'));
    }

    public function ArraySolicitudAlm(Request $req){

        $refaccion = Almacen::where('clave',$req->input('refaccion'))->first();

        if($refaccion->cantidad >= $req->input('cantidad') && $req->input('cantidad') > 0){
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
        $salidas = Salidas::
        join('requisiciones','salidas.requisicion_id','=','requisiciones.id_requisicion')
        ->where('requisiciones.usuario_id',session('loginId'))
        ->get();
        return view('Solicitante.salidas',compact('salidas'));
    }

    public function almacen(){
        $refacciones = Almacen::get();
        return view('Solicitante.almacen',compact('refacciones'));
    }
}