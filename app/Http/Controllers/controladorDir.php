<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Unidades;
use App\Models\Proveedores;
use App\Models\Comentarios;
use App\Models\Entradas;
use App\Models\Salidas;
use App\Models\Orden_compras;
use App\Models\Requisiciones;
use App\Models\cotizaciones;
use App\Models\Almacen;
use App\Models\Logs;
use Carbon\Carbon;
use DB;

class controladorDir extends Controller
{
    public function index(){
        //Datos para graficas
        $anio_actual = date('Y');
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

        //Suma por mes
        $mesActual = now()->format('m'); 
        $TotalMes = Orden_compras::whereMonth('created_at', $mesActual)->sum('costo_total');

        //Suma por año 
        $anioActual = now()->year;
        $TotalAnio =Orden_compras::whereYear('created_at', $anioActual)->sum('costo_total');
        $completas = Requisiciones::where('estado', 'Entregado')->count();
        $pendiente = Requisiciones::where('estado','!=', 'Entregado')->where('estado','!=','Rechazado')->count();
        return view("Direccion.index",[
            'pendientes'=>$pendiente,
            'completas'=>$completas,
            'TotalMes'=>$TotalMes,
            'TotalAnio'=>$TotalAnio,
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

    public function tableRefaccion(){
        $refacciones = Almacen::get()->where("estatus",1);
        return view('Direccion.refaccion',compact('refacciones'));
    }

    public function tableUnidad()
    {   
        $unidades = Unidades::where('estatus','1')->where('estado','Activo')->orderBy('id_unidad','asc')->get();
        return view('Direccion.unidad',compact('unidades'));
    }

    public function tableEntradas(){
        $entradas = Entradas::select('entradas.id_entrada','requisiciones.pdf as reqPDF','orden_compras.pdf as ordPDF','entradas.factura','entradas.created_at')
        ->join('orden_compras','entradas.orden_id','=','orden_compras.id_orden')
        ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
        ->get();
        return view('Direccion.entradas',compact('entradas'));
    }

    public function tableSalidas(){
        $salidas = Salidas::select('salidas.id_salida','requisiciones.pdf as reqPDF','salidas.cantidad','users.nombres','almacen.nombre','almacen.marca','almacen.modelo','salidas.created_at')
        ->join('almacen','salidas.refaccion_id','=','almacen.id_refaccion')
        ->join('requisiciones','salidas.requisicion_id','=','requisiciones.id_requisicion')
        ->join('users','requisiciones.usuario_id','=','users.id')
        ->get();
        return view('Direccion.salidas',compact('salidas'));
    }

    public function tableProveedores(){
        $proveedores = Proveedores::where('estatus','1')->get();
        return view('Direccion.proveedores',compact('proveedores'));
    }

    public function editProveedor($id){
        $proveedor = Proveedores::where('id_proveedor',$id)->first();
        return view('Direccion.editarProveedor',compact('proveedor'));
    }

    public function updateProveedor(Request $req,$id){
        Proveedores::where('id_proveedor',$id)->update([
            "nombre"=>$req->input('nombre'),
            "telefono"=>$req->input('telefono'),
            "correo"=>$req->input('correo'),
            "updated_at"=>Carbon::now(),
        ]);

        return redirect('proveedores/Direccion')->with('update','update');
    }

    public function deleteProveedor($id){
        Proveedores::where('id_proveedor',$id)->update([
            "estatus"=>0,
            "updated_at"=>Carbon::now()
        ]);

        return back()->with('delete','delete');
    }

    public function tableSolicitud(){
        $solicitudes = Requisiciones::select('requisiciones.id_requisicion', 'users.nombres', 'requisiciones.unidad_id', 'requisiciones.pdf', 'requisiciones.estado', 'requisiciones.created_at as fecha_creacion')
        ->join('users', 'requisiciones.usuario_id', '=', 'users.id')
        ->where('requisiciones.estado','!=','Rechazado')
        ->orderBy('requisiciones.created_at','desc')
        ->get();
        return view('Direccion.solicitudes',compact('solicitudes'));
    }
    
    public function cotizaciones($id){
        $cotizaciones = Cotizaciones::select('cotizaciones.id_cotizacion','requisiciones.id_requisicion as requisicion_id','users.nombres as usuario','requisiciones.pdf as reqPDF','cotizaciones.pdf as cotPDF')
        ->join('requisiciones','cotizaciones.requisicion_id', '=', 'requisiciones.id_requisicion')
        ->join('users','cotizaciones.usuario_id', '=', 'users.id')
        ->where('requisicion_id', $id)
        ->where('cotizaciones.estatus','1')->get();
        return view('Direccion.cotizaciones',compact('cotizaciones','id'));
    }

    public function tableEncargado(){
        $encargados = User::where('estatus','1')->orderBy('nombre')->get()
        ->where("estatus",1);
        return view('Direccion.encargado',compact('encargados'));
    }

    public function createUser(){
        return view('Direccion.crearUser');
    }

    public function insertUser(Request $req){
        $password = $this->generateRandomPassword();

        if ($req->rol === "Otro"){            
            DB::table('users')->insert([
                "nombre"=>$req->input('nombre'),
                "telefono"=>$req->input('telefono'),
                "correo"=>$req->input('correo'),
                "password"=>$password,
                "rol"=>'General',
                "departamento"=>$req->input('otro'),
                "estatus"=>'1',
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);
    
            DB::table('logs')->insert([
                "user_id"=>session('loginId'),
                "table_name"=>"Usuarios",
                "action"=>"Se ha creado un usuario: ".$req->input('nombre'),
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);
            
            return redirect()->route('encargados')->with('creado','creado');
        } else{
            User::create([
                "nombre"=>$req->input('nombre'),
                "telefono"=>$req->input('telefono'),
                "correo"=>$req->input('correo'),
                "password"=>$password,
                "rol"=>$req->input('rol'),
                "estatus"=>'1',
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);
    
            DB::table('logs')->insert([
                "user_id"=>session('loginId'),
                "table_name"=>"Usuarios",
                "action"=>"Se ha creado un usuario: ".$req->input('nombre'),
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);
            
            return redirect()->route('encargados')->with('creado','creado');
        }
    }

    public function editUser($id){
        $encargado = User::where('id',$id)->first();
        return view('Direccion.editarUser',compact('encargado'));
    }

    public function updateUser(Request $req, $id){
        User::where('id',$id)->update([
            "Nombre"=>$req->input('nombre'),
            "telefono"=>$req->input('telefono'),
            "correo"=>$req->input('correo'),
            "password"=>$req->input('password'),
            "rol"=>$req->input('rol'),
            "estatus"=>'1',
            "updated_at"=>Carbon::now()
        ]);

        DB::table('logs')->insert([
            "user_id"=>session('loginId'),
            "table_name"=>"Usuarios",
            "action"=>"Se ha actualizado un usuario: ".$req->input('nombre'),
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        return redirect('usuarios/Direccion')->with('editado','editado');
    }

    public function deleteUser($id){
         User::where('id',$id)->update([
             "estatus"=>'0',
             "updated_at"=>Carbon::now()
         ]);

         DB::table('logs')->insert([
            "user_id"=>session('loginId'),
            "table_name"=>"Usuarios",
            "action"=>"Se ha eliminado un usuario: ".$req->input('nombre'),
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

         return redirect('usuarios/Direccion')->with('eliminado','eliminado');
    }

    public function deleteReq(Request $req, $id){
        Comentarios::create([
            "requisicion_id"=>$id,
            "usuario_id"=>session('loginId'),
            "detalles"=>$req->comentario,
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]); 

        Requisiciones::where('id_requisicion',$id)->update([
            "estado"=>"Rechazado",
            "updated_at"=>Carbon::now(),
        ]);    
        
        Logs::create([
            "user_id"=>session('loginId'),
            "requisicion_id"=>$id,
            "table_name"=>"Solicitudes",
            "action"=>"Se ha eliminado la solicitud".$id,
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        return back()->with('eliminada','eliminada');
    }

    public function validarRequisicion($id){
        Requisiciones::where('id_requisicion',$id)->update([
            "estado"=>"Aprobado",
            "updated_at"=>Carbon::now(),
        ]);

        Logs::create([
            "user_id"=>session('loginId'),
            "requisicion_id"=>$id,
            "table_name"=>"Requisiciones",
            "action"=>"Se ha aprobado su solicitud".$id,
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        return back()->with('validado','validado');
    }


    public function selectCotiza($id,$sid){
        Cotizaciones::where('id_cotizacion', '!=', $id)
        ->where('requisicion_id', $sid)
        ->update([
            "estatus" => "0",
            "updated_at" => Carbon::now()
        ]);

        Requisiciones::where('id_requisicion',$sid)->update([
            "estado" => "Validado",
            "updated_at" => Carbon::now()
        ]);

        Logs::create([
            "user_id"=>session('loginId'),
            "table_name"=>"Solicitudes",
            "requisicion_id"=>$sid,
            "action"=>"Se ha validado una cotizacion de la solicitud: ".$sid,
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);
        return redirect('solicitudes/Direccion')->with('validacion','validacion');
    }

    public function reportes() {
        $encargados = User::where('rol','General')->where('estatus','1')
        ->orderBy('nombre','asc')->get();
        $unidades = Unidades::where('estatus','1')
        ->orderBy('id_unidad','asc')->get();
        return view('Direccion.reportes',compact('encargados','unidades'));
    }

    public function reporteEnc(Request $req){

        $idEncargado = $req->encargado;
        
        $encargado = User::where('id',$idEncargado)->first();
        $solicitudes = Requisiciones::where('usuario_id',$idEncargado)->count();
        $completas = Requisiciones::where('estado','Entregado')->where('usuario_id',$idEncargado)->count();
        $Requisiciones = Requisiciones::where('usuario_id',$idEncargado)->get();
        $salidas = Salidas::select('salidas.id_salida','salidas.created_at','salidas.cantidad','requisiciones.unidad_id','almacen.nombre')
        ->join('almacen','salidas.refaccion_id','=','almacen.id_refaccion')
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

    public function reporteGen(){
        $datosEmpleado[] = [
            'idEmpleado' => session('loginId'),
            'nombre' => session('loginNombre'),
            'rol' => session('rol'),
        ];

        $compras = Orden_compras::select('orden_compras.id_orden','users.nombres','orden_compras.created_at','requisiciones.unidad_id','orden_compras.costo_total')
        ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
        ->join('users','orden_compras.admin_id','users.id')
        ->get();
        $unidades = Unidades::where('estatus','1')->get();
        $usuarios = User::where('estatus','1')->get();
        $refacciones = Almacen::where('estatus','1')->get();

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
        include(public_path('/pdf/TCPDF-main/examples/Reporte_General2.php'));
        $pdfContent = ob_get_clean();
        header('Content-Type: application/pdf');
        echo $pdfContent;
    }

    public function generateRandomPassword() {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $password = '';
    
        for ($i = 0; $i < 6; $i++) {
            $index = random_int(0, strlen($characters) - 1);
            $password .= $characters[$index];
        }
    
        return $password;
    }
}