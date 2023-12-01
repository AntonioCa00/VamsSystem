<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Almacen;
use App\Models\Entradas;
use App\Models\Salidas;
use App\Models\User;
use App\Models\Unidades;
use App\Models\Proveedores;
use App\Models\Requisiciones;   
use App\Models\Cotizaciones;
use App\Models\Orden_Compras;
use DB;
use Carbon\Carbon;

class controladorAdmin extends Controller
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
        return view("Admin.index",[
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

    //VISTAS DE LAS TABLAS

    public function tableRefaccion(){
        $refacciones = Almacen::get()->where("estatus",1);
        return view('Admin.refaccion',compact('refacciones'));
    }

    public function tableEntradas(){
        $entradas = Entradas::select('entradas.id_entrada','requisiciones.pdf as reqPDF','orden_compras.pdf as ordPDF','entradas.factura','entradas.created_at')
        ->join('orden_compras','entradas.orden_id','=','orden_compras.id_orden')
        ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
        ->get();
        return view('Admin.entradas',compact('entradas'));
    }

    public function tableUnidad(){
        $unidades = Unidades::where('estatus','1')->where('estado','Activo')->orderBy('id_unidad','asc')->get();
        return view('Admin.unidad',compact('unidades'));
    }

    public function tableSalidas(){
        $salidas = Salidas::select('salidas.id_salida','requisiciones.pdf as reqPDF','salidas.cantidad','users.nombre','almacen.nombre','almacen.marca','almacen.modelo','salidas.created_at')
        ->join('almacen','salidas.refaccion_id','=','almacen.id_refaccion')
        ->join('requisiciones','salidas.requisicion_id','=','requisiciones.id_requisicion')
        ->join('users','requisiciones.usuario_id','=','users.id')
        ->get();
        return view('Admin.salidas',compact('salidas'));
    }
    
    public function tableCompras(){
        $compras = DB::table('vista_compras')->get();
        return view('Admin.compras',compact('compras'));
    }

    public function tableSolicitud(){
        $solicitudes = Requisiciones::select('requisiciones.id_requisicion', 'users.nombre', 'requisiciones.unidad_id', 'requisiciones.pdf', 'requisiciones.estado', 'requisiciones.created_at as fecha_creacion')
        ->join('users', 'requisiciones.usuario_id', '=', 'users.id')
        ->where(function($query) {
            $query->where('requisiciones.estado', '=', 'Solicitado')
                  ->orWhere('requisiciones.estado', '=', 'Cotizado')
                  ->orWhere('requisiciones.estado', '=', 'Validado');
        })
        ->orderBy('requisiciones.created_at','desc')
        ->get();
        return view('Admin.solicitudes',compact('solicitudes'));
    }

    //VISTAS DE FORMULARIOS
    public function validarSoli($id){
        Requisiciones::where('id_solicitud', $id)->update([
            "estado" => "Validado",
            "updated_at" => Carbon::now()
        ]);

        DB::table('logs')->insert([
            "user_id"=>session('loginId'),
            "table_name"=>"Solicitudes",
            "action"=>"Se ha validado una solicitud:".$id,
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        return back()->with('validado','validado');    
    }

    public function createCotiza($id){
        $cotizaciones = Cotizaciones::select('cotizaciones.id_cotizacion','requisiciones.pdf as reqPDF','cotizaciones.pdf as cotPDF')
        ->join('requisiciones','cotizaciones.requisicion_id', '=', 'requisiciones.id_requisicion')
        ->where('cotizaciones.requisicion_id', $id)->where('cotizaciones.estatus','1')->get();
        return view('Admin.crearCotizacion',compact('cotizaciones','id'));
    }

    public function insertCotiza(Request $req){
        if ($req->hasFile('archivo') && $req->file('archivo')->isValid()){
            $archivo = $req->file('archivo');
            $nombreArchivo = uniqid() . '.' . $archivo->getClientOriginalExtension();
    
            $archivo->storeAs('archivos', $nombreArchivo, 'public');
            $archivo_pdf = 'archivos/' . $nombreArchivo;

            Cotizaciones::create([
                "requisicion_id"=>$req->input('requisicion'),
                "usuario_id"=>session('loginId'),
                "pdf"=>$archivo_pdf,
                "estatus"=>"1",
                "created_at"=>Carbon::now(),        
                "updated_at"=>Carbon::now()
            ]);
    
            Requisiciones::where('id_requisicion',$req->input('requisicion'))->update([
                "estado" => "Cotizado",
                "updated_at" => Carbon::now()
            ]);
    
            DB::table('logs')->insert([
                "user_id"=>session('loginId'),
                "table_name"=>"Solicitudes",
                "action"=>"Se ha hecho una cotizacion en la solicitud:".$req->input('solicitud'),
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]); 
            return back()->with('cotizacion','cotizacion');
        } else {
            return back()->with('error', 'No se ha seleccionado ningún archivo.');
        }
    }

    public function deleteCotiza($id){
        Cotizaciones::where('id_cotizacion', $id)->delete();

        return back()->with('eliminado','eliminado');    
    }

    public function createCompra(){
        $solicitudes = DB::table('vista_solicitudes')->where('estado','Validado')->get();
        return view('Admin.crearCompra',compact('solicitudes'));
    }

    public function insertCompra(Request $req){
        Compras::create([
            "requisicion_id"=>$req->input('solicitudId'),
            "costo"=>$req->input('costo'),
            "factura"=>$req->input('factura'),
            "estatus"=>'1',
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now(),
            "admin_id"=>session('loginId')
        ]);

        DB::table('logs')->insert([
            "user_id"=>session('loginId'),
            "table_name"=>"Compras",
            "action"=>"Se ha registrado una nueva compra:".$req->input('factura'),
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        Solicitudes::where('id_requisicion',$req->input('solicitudId'))->update([
            "estado"=>"Comprado",
            "updated_at" => Carbon::now()
        ]);

        return redirect('tabla-compras  ')->with('comprado','comprado');
    }   

    public function tableProveedor(){
        $proveedores = Proveedores::where('estatus',1)->get();
        return view('Admin.proveedores',compact('proveedores'));
    }

    public function createProveedor(){
        return view('Admin.crearProveedor');
    }

    public function insertProveedor(Request $req){
        Proveedores::create([
            "nombre"=>$req->input('nombre'),
            "telefono"=>$req->input('telefono'),
            "correo"=>$req->input('correo'),
            "estatus"=>"1",
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        return redirect('proveedores/Compras')->with('insert','insert');
    }

    public function editProveedor($id){
        $proveedor = Proveedores::where('id_proveedor',$id)->first();
        return view('Admin.editarProveedor',compact('proveedor'));
    }

    public function updateProveedor(Request $req,$id){
        Proveedores::where('id_proveedor',$id)->update([
            "nombre"=>$req->input('nombre'),
            "telefono"=>$req->input('telefono'),
            "correo"=>$req->input('correo'),
            "updated_at"=>Carbon::now(),
        ]);

        return redirect('proveedores')->with('update','update');
    }

    public function deleteProveedor($id){
        Proveedores::where('id_proveedor',$id)->update([
            "estatus"=>0,
            "updated_at"=>Carbon::now()
        ]);

        return back()->with('delete','delete');
    }

    public function deleteReq($id){
        Requisiciones::where('id_requisicion',$id)->update([
            "estado"=>"Rechazado",
            "updated_at"=>Carbon::now(),
        ]);

        return back()->with('eliminada','eliminada');
    }

    public function ordenCompra($id){
        $cotizacion = Cotizaciones::select('cotizaciones.id_cotizacion','cotizaciones.pdf as cotPDF','requisiciones.pdf as reqPDF')
        ->join('requisiciones','cotizaciones.requisicion_id','=', 'requisiciones.id_requisicion')
        ->where('cotizaciones.estatus',1)
        ->where('requisiciones.id_requisicion',$id)
        ->first();

        $coti = $cotizacion['id_cotizacion'];

        $proveedores = Proveedores::select('id_proveedor','nombre')
        ->where('id_proveedor',1)->get();

        $ordenCom = session()->get('ordenCom', []);
        return view('Admin.ordenCompra',compact('cotizacion','proveedores','ordenCom','coti','id'));
        
    }

    public function ArrayOrdenComp(Request $req){
        $ordenCom = session()->get('ordenCom', []);
        $cantidad = $req->input('Cantidad');
        $descripcion = $req->input('Descripcion');
        $precio = $req->input('PrecioUni');
        $notas = $req->input('Notas');

        $ordenCom[] = [
            'cantidad' => $cantidad,
            'descripcion' => $descripcion,
            'precio'=>$precio,
        ];

        session()->put('ordenCom', $ordenCom);

        return back();
    }

    public function deleteArray($index){
        $ordenCom = session()->get('ordenCom', []);

        if (isset($ordenCom[$index])) {
            unset($ordenCom[$index]);
        }
        session()->put('ordenCom', $ordenCom);

        return back();
    }

    public function insertOrdenCom(Request $req){
        $datosOrden = session()->get('ordenCom', []);

        if (empty($datosOrden)){
            return back()->with('vacio','vacio');
        }else {
            $Nota = $req->input('Notas');
            $proveedor = $req->input('Proveedor');

            $datosEmpleado[] = [
                'idEmpleado' => session('loginId'),
                'nombre' => session('loginNombre'),
                'rol' => session('rol'),
            ];

            // Serializar los datos del empleado y almacenarlos en un archivo
            $datosSerializados = serialize($datosEmpleado);
            $rutaArchivo = storage_path('app/datos_empleados.txt');
            file_put_contents($rutaArchivo, $datosSerializados);

            // Nombre y ruta del archivo en laravel
            $numeroUnico = time(); // Genera un timestamp único
            $nombreArchivo = 'ordenCompra_' . $numeroUnico . '.pdf';
            $rutaDescargas = 'ordenesCompra/' . $nombreArchivo;

            $datosProveedor = Proveedores::where('id_proveedor',$proveedor)->first();

            $nombre = $datosProveedor['nombre'];
            $telefono = $datosProveedor['telefono'];
            $correo = $datosProveedor['correo'];
            $idProveedor = $datosProveedor['id_proveedor'];
            if($datosProveedor['estatus'] === 1){
                $estatus = 'Activo';
            } else {
                $estatus = 'Inactivo';
            }
            
            // Incluir el archivo Requisicion.php y pasar la ruta del archivo como una variable
            ob_start(); // Iniciar el búfer de salida
            include(public_path('/pdf/TCPDF-main/examples/orden_compra.php'));
            ob_end_clean();    
            

            $totalGastos = 0;

            // Itera sobre el array y suma los valores de la columna 'precio'
            foreach ($datosOrden as $subarray) {
                // Verifica si la clave 'precio' existe en el subarray antes de sumar
                if (isset($subarray['precio'])) {
                    $totalGastos += $subarray['precio']*$subarray['cantidad'];
                }
            }
            
            Orden_compras::create([
                "admin_id"=>session('loginId'),
                "cotizacion_id" => $req->input('cotizacion'),
                "proveedor_id"=>$req->input('Proveedor'),
                "costo_total"=>$totalGastos,
                "pdf" => $rutaDescargas,
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now(),
            ]);

            Requisiciones::where('id_requisicion',$req->input('requisicion'))->update([
                "estado"=>"Comprado",
                "updated_at"=>Carbon::now(),
            ]);

            DB::table('logs')->insert([
                "user_id"=>session('loginId'),
                "table_name"=>"Orden_compras",
                "action"=>"Se ha registrado una nueva orden de compra: ".$Nota,
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);

            session()->forget('datosOrden');

            return redirect('ordenesCompras');
        }
    }

    public function ordenesCompras(){
        $ordenes = Orden_compras::select('orden_compras.id_orden','requisiciones.id_requisicion','requisiciones.estado','users.nombre','cotizaciones.pdf as cotPDF','proveedores.nombre as proveedor','orden_compras.costo_total','orden_compras.pdf as ordPDF', 'orden_compras.created_at')
        ->join('users','orden_compras.admin_id','=','users.id')
        ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
        ->join('proveedores','orden_compras.proveedor_id','=','proveedores.id_proveedor')
        ->where('requisiciones.estado','!=','Rechazado')
        ->orderBy('requisiciones.created_at','desc')
        ->get();
        return view ('Admin.ordenesCompras',compact('ordenes'));
    }

    public function deleteOrd($id,$sid){
        Orden_compras::where('id_orden',$id)->delete();

        return back()->with('eliminada','eliminada');

        Requisiciones::where('requisicion_id',$sid)->update([
            "estado"=>"Validado",
            "updated_at"=>Carbon::now()
        ]);

    }
}