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
use App\Models\Articulos;
use Carbon\Carbon;
use DB;

class controladorGtArea extends Controller
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

        $completas = Requisiciones::join('users','requisiciones.usuario_id','=','users.id')
        ->where('requisiciones.estado', 'Comprado')
        ->count();

        $pendiente = Requisiciones::join('users','requisiciones.usuario_id','=','users.id')
        ->where('users.departamento',session('departamento'))
        ->where('requisiciones.estado','!=', 'Comprado')
        ->where('requisiciones.estado','!=','Rechazado')
        ->count();

        return view("GtArea.index",[
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
        return view('GtArea.refaccion',compact('refacciones'));
    }

    public function tableUnidad()
    {   
        $unidades = Unidades::where('estatus','1')
        ->where('id_unidad','!=','1')
        ->where('estado','Activo')->orderBy('id_unidad','asc')->get();
        return view('GtArea.unidad',compact('unidades'));
    }

    public function tableEntradas(){
        $entradas = Entradas::select('entradas.id_entrada','requisiciones.pdf as reqPDF','orden_compras.pdf as ordPDF','entradas.factura','entradas.created_at')
        ->join('orden_compras','entradas.orden_id','=','orden_compras.id_orden')
        ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
        ->get();
        return view('GtArea.entradas',compact('entradas'));
    }

    public function tableSalidas(){
        $salidas = Salidas::select('salidas.id_salida','requisiciones.pdf as reqPDF','salidas.cantidad','users.nombres','almacen.clave','almacen.ubicacion','almacen.descripcion','salidas.created_at')
        ->join('almacen','salidas.refaccion_id','=','almacen.clave')
        ->join('requisiciones','salidas.requisicion_id','=','requisiciones.id_requisicion')
        ->join('users','requisiciones.usuario_id','=','users.id')
        ->get();
        return view('GtArea.salidas',compact('salidas'));
    }

    public function tableProveedores(){
        $proveedores = Proveedores::where('estatus','1')->get();
        return view('GtArea.proveedores',compact('proveedores'));
    }

    public function editProveedor($id){
        $proveedor = Proveedores::where('id_proveedor',$id)->first();
        return view('GtArea.editarProveedor',compact('proveedor'));
    }

    public function updateProveedor(Request $req,$id){
        Proveedores::where('id_proveedor',$id)->update([
            "nombre"=>$req->input('nombre'),
            "telefono"=>$req->input('telefono'),
            "correo"=>$req->input('correo'),
            "updated_at"=>Carbon::now(),
        ]);

        return redirect('proveedores/GtArea')->with('update','update');
    }

    public function deleteProveedor($id){
        Proveedores::where('id_proveedor',$id)->update([
            "estatus"=>0,
            "updated_at"=>Carbon::now()
        ]);

        return back()->with('delete','delete');
    }

    public function tableSolicitud(){
        $solicitudes = Requisiciones::where('requisiciones.estado','!=','Rechazado')
        ->select('requisiciones.id_requisicion','requisiciones.created_at','requisiciones.unidad_id','requisiciones.estado','us.departamento','us.nombres','requisiciones.created_at','requisiciones.pdf', 'comentarios.detalles','users.rol',DB::raw('MAX(comentarios.created_at) as fechaCom'))
        ->join('users as us','requisiciones.usuario_id','us.id')
        ->leftJoin('comentarios','requisiciones.id_requisicion','=','comentarios.requisicion_id')
        ->leftJoin('users','users.id','=','comentarios.usuario_id')        
        ->orderBy('requisiciones.created_at','desc')
        ->groupBY('requisiciones.id_requisicion')
        ->get();

        return view('GtArea.solicitudes',compact('solicitudes'));
    }

    //Regresa la vista con la lista de articulos de la requisicion inicial
    public function aprobarArt($id){
        $articulos = Articulos::where('requisicion_id',$id)
        ->leftJoin('unidades','articulos.unidad_id','=','unidades.id_unidad')
        ->get();
        $unidades = Unidades::where('estado','Activo')
        ->where('estatus','1')
        ->get();
        return view('GtArea.aprobarSolicitud',compact('articulos','unidades'));
    }

    //Edita los datos de los articulos pedidos
    public function editarArt(Request $req, $id){

        if(session('departamento')==="Mantenimiento"){
            DB::table('articulos')->where('id',$id)->update([
                "cantidad"=>$req->editCantidad,
                "unidad"=>$req->editUnidadM,
                "descripcion"=>$req->editDescripcion,
                "unidad_id"=>$req->editUnidad,
                "updated_at"=>Carbon::now()
            ]);            
        } else{
            Articulos::where('id',$id)->update([
                "cantidad"=>$req->editCantidad,
                "unidad"=>$req->editUnidad,
                "descripcion"=>$req->editDescripcion,
                "updated_at"=>Carbon::now()
            ]);
        }        

        return back();
    }

    //Elimina de la lista el articulo que no se aprueba
    public function rechazaArt($id){
        Articulos::where('id',$id)->delete();
        return back();
    }

    //regresa los articulos definitivos que fueron aprobados    
    public function aprobar(Request $req,$rid){

        if(!empty($req->Comentarios)){
            Comentarios::create([
                "requisicion_id"=>$rid,
                "usuario_id"=>session('loginId'),
                "detalles"=>$req->Comentarios,
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]); 
        }

        $notas = $req->Comentarios;
        $datos = Requisiciones::select('requisiciones.id_requisicion','requisiciones.unidad_id','requisiciones.created_at','requisiciones.pdf','requisiciones.usuario_id','users.nombres','users.apellidoP','users.apellidoM','users.rol','users.departamento')
        ->join('users','requisiciones.usuario_id','=','users.id')
        ->where('requisiciones.id_requisicion',$rid)
        ->first();

        $fileToDelete = public_path($datos->pdf);

        if (file_exists($fileToDelete)) {
            unlink($fileToDelete);
        }

        $articulos = Articulos::where('requisicion_id',$rid)
        ->leftJoin('unidades','articulos.unidad_id','=','unidades.id_unidad')
        ->get();

        // Nombre y ruta del archivo en laravel
        $nombreArchivo = 'requisicion_' . $datos->id_requisicion . '.pdf';
        $rutaDescargas = 'requisiciones/' . $nombreArchivo;

        // Incluir el archivo Requisicion.php y pasar la ruta del archivo como una variable
        ob_start(); // Iniciar el búfer de salida
        include(public_path('/pdf/TCPDF-main/examples/RequisicionAprobada.php'));
        ob_end_clean();    

        Requisiciones::where('id_requisicion',$rid)->update([
            "estado"=>'Aprobado',
            "pdf"=>$rutaDescargas,
            "updated_at"=>Carbon::now(),
        ]);
        if (!empty($req->input('Comentarios'))){
            Comentarios::create([
                "requisicion_id"=>$rid,
                "usuario_id"=>session('loginId'),
                "detalles"=>$req->input('Comentarios'),
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);       
        }
        return redirect('solicitudes/GtArea')->with('aprobado','aprobado');
    }
    
    public function cotizaciones($id){
        $cotizaciones = Cotizaciones::select('cotizaciones.id_cotizacion','requisiciones.id_requisicion as requisicion_id','users.nombres as usuario','requisiciones.pdf as reqPDF','cotizaciones.pdf as cotPDF')
        ->join('requisiciones','cotizaciones.requisicion_id', '=', 'requisiciones.id_requisicion')
        ->join('users','cotizaciones.usuario_id', '=', 'users.id')
        ->where('requisicion_id', $id)
        ->where('cotizaciones.estatus','0')->get();
        return view('GtArea.cotizaciones',compact('cotizaciones','id'));
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
            "action"=>"Se ha rechazado la solicitud: ".$id,
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

    public function selectCotiza(Request $request,$id){
        $req = Requisiciones::where('id_requisicion',$id)->first();
        if ($req->estado === "Pre Validado"){
            Requisiciones::where('id_requisicion',$id)->update([
                "estado" => "Validado",
                "updated_at" => Carbon::now()
            ]);

            Logs::create([
                "user_id"=>session('loginId'),
                "table_name"=>"Solicitudes",
                "requisicion_id"=>$id,
                "action"=>"Se ha pre validado una cotizacion de la solicitud: ".$id,
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);
        } else{
            $cotizaciones = $request->input('cotizaciones_seleccionadas');
            foreach ($cotizaciones as $cotizacionId) {
                // Actualiza el estado de la cotización
                Cotizaciones::where('id_cotizacion',$cotizacionId)->update([
                    "estatus"=>1,
                    "updated_at"=>Carbon::now(),
                ]);
            }

            Requisiciones::where('id_requisicion',$id)->update([
                "estado" => "Pre Validado", 
                "updated_at" => Carbon::now()
            ]);

            Logs::create([
                "user_id"=>session('loginId'),
                "table_name"=>"Solicitudes",
                "requisicion_id"=>$id,
                "action"=>"Se ha pre validado una cotizacion de la solicitud: ".$id,
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);
        }
        return redirect('solicitudes/GtArea')->with('validacion','validacion');
    }

    public function aprobCotiza($id){
        $validadas = Cotizaciones::select('cotizaciones.id_cotizacion','requisiciones.id_requisicion as requisicion_id','users.nombres as usuario','requisiciones.pdf as reqPDF','cotizaciones.pdf as cotPDF')
        ->join('requisiciones','cotizaciones.requisicion_id', '=', 'requisiciones.id_requisicion')
        ->join('users','cotizaciones.usuario_id', '=', 'users.id')
        ->where('requisicion_id', $id)
        ->where('cotizaciones.estatus','1')->get();

        $cotizaciones = Cotizaciones::select('cotizaciones.id_cotizacion','requisiciones.id_requisicion as requisicion_id','users.nombres as usuario','requisiciones.pdf as reqPDF','cotizaciones.pdf as cotPDF')
        ->join('requisiciones','cotizaciones.requisicion_id', '=', 'requisiciones.id_requisicion')
        ->join('users','cotizaciones.usuario_id', '=', 'users.id')
        ->where('requisicion_id', $id)
        ->where('cotizaciones.estatus','0')->get();
        return view('GtArea.aprobCotizaciones',compact('cotizaciones','id','validadas'));
    }

    public function rechazarFin(Request $req, $id){
        Comentarios::create([
            "requisicion_id"=>$id,
            "usuario_id"=>session('loginId'),
            "detalles"=>$req->input('comentario'),
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        Cotizaciones::where('requisicion_id', $id)
            ->update([
            "estatus" => "0",
            "updated_at" => Carbon::now()
        ]);

        Requisiciones::where('id_requisicion',$id)->update([
            "estado"=>"Cotizado",
            "updated_at"=>Carbon::now()
        ]);

        return redirect('solicitudes/GtArea')->with('rechazaC','rechazaC');
    }

    public function deleteCotiza($id){
        Cotizaciones::where('id_cotizacion', $id)->delete();

        return back()->with('eliminado','eliminado');    
    }

    public function reportes() {
        $encargados = User::where('rol','General')->where('estatus','1')
        ->orderBy('nombre','asc')->get();
        $unidades = Unidades::where('estatus','1')
        ->orderBy('id_unidad','asc')->get();
        return view('GtArea.reportes',compact('encargados','unidades'));
    }

    public function reporteEnc(Request $req){

        $idEncargado = $req->encargado;
        
        $encargado = User::where('id',$idEncargado)->first();
        $solicitudes = Requisiciones::where('usuario_id',$idEncargado)->count();
        $completas = Requisiciones::where('estado','Entregado')->where('usuario_id',$idEncargado)->count();
        $Requisiciones = Requisiciones::where('usuario_id',$idEncargado)->get();
        $salidas = Salidas::select('salidas.id_salida','salidas.created_at','salidas.cantidad','requisiciones.unidad_id','almacen.nombre')
        ->join('almacen','salidas.refaccion_id','=','almacen.clave')
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
}