<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Almacen;
use App\Models\Salidas;
use App\Models\Requisiciones;
use App\Models\Entradas;
use App\Models\Orden_compras;
use Session;
use DB;
use Carbon\Carbon;

class controladorAlm extends Controller
{
    /**
    * Display a listing of the resource.
    */
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
        $pendiente = Requisiciones::where('estado','!=', 'Entregado')->count();
        return view("Almacen.index",[
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

    public function requisiciones(){
        $ordenes = Orden_compras::select('orden_compras.id_orden','requisiciones.id_requisicion','users.nombre','requisiciones.unidad_id','requisiciones.estado','requisiciones.pdf as reqPDF','proveedores.nombre as proveedor','orden_compras.costo_total','orden_compras.pdf as ordPDF', 'requisiciones.created_at')
        ->join('users','orden_compras.admin_id','=','users.id')
        ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
        ->join('proveedores','orden_compras.proveedor_id','=','proveedores.id_proveedor')
        ->where('requisiciones.estado','Comprado')
        ->orderBy('requisiciones.created_at','desc')
        ->get();
        return view ('Almacen.requisiciones',compact('ordenes'));
    }

    public function createEntrada($id){
        $entrada = session()->get('entrada', []);
        $orden = Orden_compras::select('orden_compras.id_orden','orden_compras.pdf as comPDF')
        ->where('id_orden',$id)
        ->first();
        $refacciones = Almacen::get();
        return view('Almacen.crearEntrada',compact('entrada','orden','id','refacciones'));
    }

    public function ArrayRefaccion(Request $req){
        $entrada = session()->get('entrada', []);
        $nombre = $req->input('Nombre');
        $marca = $req->input('Marca');
        $anio = $req->input('Anio');
        $modelo = $req->input('Modelo');
        $descripcion = $req->input('Descripcion');
        $cantidad = $req->input('cantidad');

        $entrada[] = [
            'nombre'=>$nombre,
            'marca'=>$marca,
            'anio'=>$anio,
            'modelo'=>$modelo,
            'descripcion' => $descripcion,
            'cantidad' => $cantidad,        
        ];

        session()->put('entrada', $entrada);

        return back();
    }

    public function deleteArrayRef($index){
        $entrada = session()->get('entrada', []);

        if (isset($entrada[$index])) {
            unset($entrada[$index]);
        }
        session()->put('entrada', $entrada);

        return back();
    }

    public function entradaAlm(Request $req, $id){

        $entradas = session()->get('entrada', []);
        if (empty($entradas)){
            return back()->with('vacio','vacio');
        } else {
            if ($req->hasFile('archivo') && $req->file('archivo')->isValid()){
                $archivo = $req->file('archivo');
                $nombreArchivo = uniqid() . '.' . $archivo->getClientOriginalExtension();
        
                $archivo->storeAs('facturas', $nombreArchivo, 'public');
                $archivo_pdf = 'facturas/' . $nombreArchivo;
    
                Entradas::create([
                    "orden_id"=>$id,
                    "factura"=>$archivo_pdf,
                    "created_at"=>Carbon::now(),
                    "updated_at"=>Carbon::now()
                ]);
    
                $entrada = Entradas::select('id_entrada')
                ->where('orden_id',$id)
                ->first();
    
                for ($i = 0; $i < count($entradas); $i++) {
                    $refaccion = Almacen::where(function($query) use ($i, $entradas) {
                        $query->where('nombre', $entradas[$i]['nombre'])
                              ->Where('marca', $entradas[$i]['marca'])
                              ->Where('anio', $entradas[$i]['anio'])
                              ->Where('modelo', $entradas[$i]['modelo']);
                    })->first();
                
                    if (!$refaccion) {
                        // El artículo no existe en la base de datos, crear uno nuevo
                        DB::table('almacen')->insert([
                            "nombre" => $entradas[$i]['nombre'],
                            "marca" => $entradas[$i]['marca'],
                            "anio" => $entradas[$i]['anio'],
                            "modelo" => $entradas[$i]['modelo'],
                            "descripcion" => $entradas[$i]['descripcion'],
                            "stock" => $entradas[$i]['cantidad'],
                            "entrada_id"=>$entrada->id_entrada
                        ]);
                    } else {
                        // El artículo ya existe en la base de datos, actualizar el stock
                        Almacen::where(function($query) use ($i, $entradas) {
                            $query->where('nombre', $entradas[$i]['nombre'])
                                  ->orWhere('marca', $entradas[$i]['marca'])
                                  ->orWhere('anio', $entradas[$i]['anio'])
                                  ->orWhere('modelo', $entradas[$i]['modelo']);
                        })->update([
                            "estatus"=>"1",
                            "stock" => $refaccion->stock + $entradas[$i]['cantidad'],
                            "updated_at" => Carbon::now()
                        ]);
                    }
                }

                $idRequisicion = Entradas::join('orden_compras', 'entradas.orden_id', '=', 'orden_compras.id_orden')
                ->join('cotizaciones', 'orden_compras.cotizacion_id', '=', 'cotizaciones.id_cotizacion')
                ->join('requisiciones', 'cotizaciones.requisicion_id', '=', 'requisiciones.id_requisicion')
                ->where('entradas.id_entrada','=', $entrada->id_entrada)
                ->select('requisiciones.id_requisicion')
                ->first();

                requisiciones::where('id_requisicion',$idRequisicion->id_requisicion)->update([
                    "estado"=>"En Almacen",
                    "updated_at"=>Carbon::now()
                ]);

                session()->forget('entrada');
                return redirect('almacen/Almacen')->with('agregado','agregado');
            } else {
                return back()->with('error', 'No se ha seleccionado ningún archivo.');
            }
        }
    }

    public function entradas () {
        $entradas = Entradas::get();
        return view('Almacen.entradas', compact('entradas'));
    }

    public function almacen (){
        $refacciones = Almacen::where('estatus', '1')->orderBy('nombre', 'asc')->get();
        return view('Almacen.almacen',compact ('refacciones'));
    }

    public function createRefaccion(){
        return view('Almacen.crearRefaccion');
    }

    public function insertRefaccion(Request $req){
        Almacen::create([
            "nombre" => $req->nombre,
            "marca" => $req->marca,
            "anio" => $req->anio,
            "modelo" => $req->modelo,
            "descripcion" => $req->descripcion,
            "stock" => $req->cantidad,
            "entrada_id"=> 0,
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        return redirect()->route('almacenAlm')->with('agregado');
    }

    public function editRefaccion($id){
        $refaccion = Almacen::where('id_refaccion',$id)->first();
        return view('Almacen.editarRefaccion',compact('refaccion'));
    }

    public function updateRefaccion(Request $req, $id){
        Almacen::where('id_refaccion',$id)->update([
            "nombre"=> $req->nombre,
            "marca"=> $req->marca,
            "anio"=> $req->anio,
            "modelo"=> $req->modelo,
            "descripcion"=> $req->descripcion,
            "updated_at"=>Carbon::now()
        ]);

        return redirect()->route('almacenAlm')->with('editado','editado');
    }

    public function deleteRefaccion($id){
        Almacen::where('id_refaccion',$id)->update([
            "estatus"=>"0",
            "updated_at"=>Carbon::now()
        ]);

        return back()->with('eliminado','eliminado');
    }

    public function salidas(){
        $salidas = Requisiciones::select('requisiciones.id_requisicion', 'orden_compras.id_orden', 'entradas.id_entrada', 'users.nombre', 'requisiciones.unidad_id', 'requisiciones.pdf', 'requisiciones.estado', 'requisiciones.created_at as fecha_creacion')
        ->join('users', 'requisiciones.usuario_id', '=', 'users.id')
        ->join('cotizaciones', 'cotizaciones.requisicion_id', '=', 'requisiciones.id_requisicion')
        ->join('orden_compras', 'orden_compras.cotizacion_id', '=', 'cotizaciones.id_cotizacion')
        ->join('entradas', 'entradas.orden_id', '=', 'orden_compras.id_orden')
        ->where('requisiciones.estado', 'En Almacen')
        ->get();
        return view('Almacen.salidas',compact('salidas'));
    }

    public function crearSalida($id){
        $refacciones = Almacen::select('requisiciones.id_requisicion','requisiciones.pdf','almacen.id_refaccion','almacen.nombre','almacen.marca','almacen.anio','almacen.modelo','almacen.descripcion')
        ->where('orden_compras.id_orden',$id)
        ->join('entradas','almacen.entrada_id','=','entradas.id_entrada')
        ->join('orden_compras','entradas.orden_id','=','orden_compras.id_orden')
        ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
        ->where('orden_compras.id_orden',$id)
        ->get();

        $salida = session()->get('salida', []);
        return view('Almacen.crearSalida',compact('refacciones','salida'));
    }

    public function ArraySalida(Request $req){

        $refaccion = Almacen::where('id_refaccion',$req->input('refaccion'))->first();
        
        if($refaccion->stock >= $req->input('cantidad') && $req->input('cantidad') > 0){
            $salida = session()->get('salida', []);
            $refaccion = $req->input('refaccion');
            $nombre = $req->input('nombre');
            $cantidad = $req->input('cantidad');

            $salida[] = [
                'id'=>$refaccion,
                'nombre'=>$nombre,
                'cantidad' => $cantidad,        
            ];

            session()->put('salida', $salida);

            return back();
        }else {
            return back()->with('insuficiente','insuficiente');
        }
    }

    public function deleteArraySal($index){
        $salida = session()->get('salida', []);

        if (isset($salida[$index])) {
            unset($salida[$index]);
        }
        session()->put('salida', $salida);

        return back();
    }

    public function createSalida($id){
        $salida = session()->get('salida', []);
        if (empty($salida)){
            return back()->with('vacio','vacio');
        } else{
            for ($i = 0; $i < count($salida); $i++) {
                $refaccion = Almacen::where(function($query) use ($i, $salida) {
                    $query->where('id_refaccion', $salida[$i]['id']);            
                })->first();
                if ($refaccion->stock < $salida[$i]['cantidad']){
                    return back()->with('insuficiente','insuficiente');
                } else{
                    Almacen::where('id_refaccion',$salida[$i]['id'])->update([
                        "stock"=>$refaccion->stock - $salida[$i]['cantidad'],
                        "updated_at"=>Carbon::now()
                    ]);

                    Requisiciones::where('id_requisicion',$id)->update([
                        "estado"=>"Entregado",
                        "updated_at"=>Carbon::now()
                    ]);
                }
            }
            session()->forget('salida');
            return redirect('salidas/Almacen')->with('salida','salida');
        }              
    }

    public function requisicionesAlm(){
        $requisiciones = Requisiciones::whereNotExists(function ($query) {
            $query->select('*')
                ->from('cotizaciones')
                ->whereRaw('cotizaciones.requisicion_id = requisiciones.id_requisicion');
        })->where('estado', 'En Almacen')->get();        
        return view('Almacen.requisicionesAlm',compact('requisiciones'));     

    }

    public function crearSalidaAlm($id){
        $salida = session()->get('salida', []);
        $refacciones = Almacen::where('estatus','1')->get();
        $requisicion = Requisiciones::where('id_requisicion',$id)->first();
        return view('Almacen.crearSalidaAlm',compact('requisicion','salida','refacciones','id'));
    }

    public function ArryaSalidaAlm(Request $req){

        $refaccion = Almacen::where('id_refaccion',$req->input('refaccion'))->first();

        if($refaccion->stock >= $req->input('cantidad') && $req->input('cantidad') > 0){
            $salida = session()->get('salida', []);
            $refaccion = $req->input('refaccion');
            $nombre = $req->input('nombre');
            $cantidad = $req->input('cantidad');

            $salida[] = [
                'id'=>$refaccion,
                'nombre'=>$nombre,
                'cantidad' => $cantidad,        
            ];

            session()->put('salida', $salida);

            return back();
        }else {
            return back()->with('insuficiente','insuficiente');
        }
    }

    public function deleteArraySalAlm($index){
        $salida = session()->get('salida', []);

        if (isset($salida[$index])) {
            unset($salida[$index]);
        }
        session()->put('salida', $salida);

        return back();
    }
    
    public function createSalidaAlm($id){
        $salida = session()->get('salida', []);
        if (empty($salida)){
            return back()->with('vacio','vacio');
        } else{
            for ($i = 0; $i < count($salida); $i++) {
                $refaccion = Almacen::where(function($query) use ($i, $salida) {
                    $query->where('id_refaccion', $salida[$i]['id']);            
                })->first();
                if ($refaccion->stock < $salida[$i]['cantidad']){
                    return back()->with('insuficiente','insuficiente');
                } else{
                    Almacen::where('id_refaccion',$salida[$i]['id'])->update([
                        "stock"=>$refaccion->stock - $salida[$i]['cantidad'],
                        "updated_at"=>Carbon::now()
                    ]);

                    Requisiciones::where('id_requisicion',$id)->update([
                        "estado"=>"Entregado",
                        "updated_at"=>Carbon::now()
                    ]);
                }
            }
            session()->forget('salida');
            return redirect('almacen/Almacen')->with('salida','salida');
        }              
    }
}