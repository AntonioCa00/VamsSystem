<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Almacen;
use App\Models\Salidas;
use App\Models\Requisiciones;
use App\Models\Entradas;
use App\Models\Orden_compras;
use App\Models\Logs;
use Session;
use DB;
use Carbon\Carbon;

class controladorAlm extends Controller
{
    /**
    * Display a listing of the resource.
    */
    public function index(){
        $almacen = Almacen::where('estatus','1')->get();
        $completas = Requisiciones::where('estado', 'Entregado')->count();
        $pendiente = Requisiciones::where('estado','=', 'En Almacen')
        ->orWhere('estado','=','Entrada Pendiente')->count();
        return view("Almacen.index",[
            'pendientes'=>$pendiente,
            'completas'=>$completas,
            'almacen'=>$almacen]);
    }

    public function requisiciones(){
        $ordenes = Orden_compras::select('orden_compras.id_orden','requisiciones.id_requisicion','users.nombres','requisiciones.unidad_id','requisiciones.estado','requisiciones.pdf as reqPDF','proveedores.nombre as proveedor','orden_compras.costo_total','orden_compras.pdf as ordPDF', 'requisiciones.created_at')
        ->join('users','orden_compras.admin_id','=','users.id')
        ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
        ->join('proveedores','orden_compras.proveedor_id','=','proveedores.id_proveedor')
        ->where('requisiciones.estado','Comprado')
        ->orWhere('requisiciones.estado','Entrada Pendiente')
        ->orWhere('requisiciones.estado','Entrada/Salida Pendiente')
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

        $refaccion = Almacen::where('clave',$req->input('clave'))->first();

        if (empty($refaccion)){
        $entrada = session()->get('entrada', []);
        $clave = $req->input('clave');
        $ubicacion = $req->input('ubicacion');
        $descripcion = $req->input('descripcion');
        $medida = $req->input('medida');
        $marca = $req->input('marca');
        $cantidad = $req->input('cantidad');

        $entrada[] = [
            'clave'=>$clave,
            'ubicacion'=>$ubicacion,
            'descripcion'=>$descripcion,
            'medida'=>$medida,
            'marca' => $marca,
            'cantidad' => $cantidad,        
        ];

        session()->put('entrada', $entrada);
        return back();
        } else{
            return back()->with('duplicado','duplicado');
        }
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
                        $query->where('clave', $entradas[$i]['clave'])
                              ->Where('ubicacion', $entradas[$i]['ubicacion'])
                              ->Where('medida', $entradas[$i]['medida'])
                              ->Where('marca', $entradas[$i]['marca']);
                    })->first();
                
                    if (!$refaccion) {
                        // El artículo no existe en la base de datos, crear uno nuevo
                        DB::table('almacen')->insert([
                            "clave" => $entradas[$i]['clave'],
                            "ubicacion" => $entradas[$i]['ubicacion'],
                            "descripcion" => $entradas[$i]['descripcion'],
                            "medida" => $entradas[$i]['medida'],
                            "marca" => $entradas[$i]['marca'],
                            "cantidad" => $entradas[$i]['cantidad'],
                            "entrada_id"=>$entrada->id_entrada
                        ]);
                    } else {
                        // El artículo ya existe en la base de datos, actualizar el stock
                        Almacen::where(function($query) use ($i, $entradas) {
                            $query->where('clave', $entradas[$i]['clave'])
                                  ->Where('ubicacion', $entradas[$i]['ubicacion'])
                                  ->Where('medida', $entradas[$i]['medida'])
                                  ->Where('marca', $entradas[$i]['marca']);
                        })->update([
                            "estatus"=>"1",
                            "cantidad" => $refaccion->cantidad + $entradas[$i]['cantidad'],
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

                if ($req->input('entrada') === "Completo"){
                    requisiciones::where('id_requisicion',$idRequisicion->id_requisicion)->update([
                        "estado"=>"En Almacen",
                        "updated_at"=>Carbon::now()
                    ]);
    
                    Logs::create([
                        "user_id"=>session('loginId'),
                        "requisicion_id"=>$idRequisicion->id_requisicion,
                        "table_name"=>"Entradas",
                        "action"=>"Han entrado sus refacciones a almacen de la requisicion: ".$idRequisicion->id_requisicionid,
                        "created_at"=>Carbon::now(),
                        "updated_at"=>Carbon::now()
                    ]);
                } else {
                    requisiciones::where('id_requisicion',$idRequisicion->id_requisicion)->update([
                        "estado"=>"Entrada Pendiente",
                        "updated_at"=>Carbon::now()
                    ]);
    
                    Logs::create([
                        "user_id"=>session('loginId'),
                        "requisicion_id"=>$idRequisicion->id_requisicion,
                        "table_name"=>"Entradas",
                        "action"=>"Han entrado algunas de sus refacciones a almacen de la requisicion: ".$idRequisicion->id_requisicionid,
                        "created_at"=>Carbon::now(),
                        "updated_at"=>Carbon::now()
                    ]);
                }          

                session()->forget('entrada');
                return redirect('almacen/Almacen')->with('agregado','agregado');
            } else {
                return back()->with('error', 'No se ha seleccionado ningún archivo.');
            }
        }
    }

    public function entradas () {
        $entradas = Entradas::get();

        // Obtener los IDs de las entradas
        $ids_entradas = $entradas->pluck('id_entrada');
        
        $almacen = Almacen::join('entradas','almacen.entrada_id','=','entradas.id_entrada')
        ->join('orden_compras','entradas.orden_id','=','orden_compras.id_orden')
        ->whereIn('entrada_id', $ids_entradas)->get();
        return view('Almacen.entradas', compact('entradas'));
    }

    public function almacen (){
        $refacciones = Almacen::where('estatus', '1')->orderBy('clave', 'asc')->get();
        return view('Almacen.almacen',compact ('refacciones'));
    }

    public function createRefaccion(){
        return view('Almacen.crearRefaccion');
    }

    public function insertRefaccion(Request $req){

        $ultima_ref = Almacen::where('ubicacion', $req->ubicacion)
        ->orderBy('clave', 'desc')
        ->value('clave');

        return $ultima_ref+1;

        Almacen::create([
            "clave" => $ultima_ref+1,
            "ubicacion" => $req->ubicacion,
            "descripcion" => $req->descripcion,
            "medida" => $req->medida,
            "marca" => $req->marca, 
            "cantidad" => $req->cantidad,            
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        return redirect()->route('almacenAlm')->with('agregado','agregado');
    }

    public function editRefaccion($id){
        $refaccion = Almacen::where('clave',$id)->first();
        return view('Almacen.editarRefaccion',compact('refaccion'));
    }

    public function updateRefaccion(Request $req, $id){
        Almacen::where('clave',$id)->update([
            "clave" => $req->clave,
            "ubicacion" => $req->ubicacion,
            "descripcion" => $req->descripcion,
            "medida" => $req->medida,
            "marca" => $req->marca,          
            "updated_at"=>Carbon::now()
        ]);

        return redirect()->route('almacenAlm')->with('editado','editado');
    }

    public function deleteRefaccion($id){
        Almacen::where('clave',$id)->update([
            "estatus"=>"0",
            "updated_at"=>Carbon::now()
        ]);

        return back()->with('eliminado','eliminado');
    }

    public function salidas(){
        $salidas = Requisiciones::select('requisiciones.id_requisicion', 'orden_compras.id_orden', 'users.nombres', 'requisiciones.unidad_id', 'requisiciones.pdf', 'requisiciones.estado', 'requisiciones.created_at as fecha_creacion')
        ->join('users', 'requisiciones.usuario_id', '=', 'users.id')
        ->join('cotizaciones', 'cotizaciones.requisicion_id', '=', 'requisiciones.id_requisicion')
        ->join('orden_compras', 'orden_compras.cotizacion_id', '=', 'cotizaciones.id_cotizacion')
        ->join('entradas', 'entradas.orden_id', '=', 'orden_compras.id_orden')
        ->where('requisiciones.estado', 'En Almacen')
        ->orWhere('requisiciones.estado','Entrada Pendiente')
        ->orWhere('requisiciones.estado','Salida Pendiente')
        ->orWhere('requisiciones.estado','Entrada/Salida Pendiente')
        ->groupBy('requisiciones.id_requisicion', 'orden_compras.id_orden', 'users.nombres', 'requisiciones.unidad_id', 'requisiciones.pdf', 'requisiciones.estado', 'requisiciones.created_at')
        ->get();
        return view('Almacen.salidas',compact('salidas'));
    }

    public function crearSalida($id){
        $refacciones = Almacen::select('requisiciones.id_requisicion','requisiciones.pdf','almacen.clave','almacen.ubicacion','almacen.descripcion','almacen.medida','almacen.marca')
        ->where('requisiciones.id_requisicion',$id)
        ->join('entradas','almacen.entrada_id','=','entradas.id_entrada')
        ->join('orden_compras','entradas.orden_id','=','orden_compras.id_orden')
        ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
        ->get();

        $salida = session()->get('salida', []);
        return view('Almacen.crearSalida',compact('refacciones','salida'));
    }

    public function ArraySalida(Request $req){

        $refaccion = Almacen::where('clave',$req->input('refaccion'))->first();
        
        if($refaccion->cantidad >= $req->input('cantidad') && $req->input('cantidad') > 0){
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

    public function createSalida(Request $req,$id){

        $salida = session()->get('salida', []);
        if (empty($salida)){
            return back()->with('vacio','vacio');
        } else{
            for ($i = 0; $i < count($salida); $i++) {
                $refaccion = Almacen::where(function($query) use ($i, $salida) {
                    $query->where('clave', $salida[$i]['id']);            
                })->first();
                if ($refaccion->cantidad < $salida[$i]['cantidad']){
                    return back()->with('insuficiente','insuficiente');
                } else{

                    Salidas::create([
                        "requisicion_id"=>$id,
                        "cantidad"=>$salida[$i]['cantidad'],
                        "usuario_id"=>session('loginId'),
                        "refaccion_id"=>$salida[$i]['id'],
                        "created_at"=>Carbon::now(),
                        "updated_at"=>Carbon::now()
                    ]);

                    Almacen::where('clave',$salida[$i]['id'])->update([
                        "cantidad"=>$refaccion->cantidad - $salida[$i]['cantidad'],
                        "updated_at"=>Carbon::now()
                    ]);

                    $requisicion = Requisiciones::where('id_requisicion',$id)->first();
                    if ($requisicion->estado === "Entrada Pendiente"){
                        if ($req->input('entrada') === "Completo"){
                            Requisiciones::where('id_requisicion',$id)->update([
                                "estado"=>"Entrada Pendiente",
                                "updated_at"=>Carbon::now()
                            ]);
                        } else {
                            Requisiciones::where('id_requisicion',$id)->update([
                                "estado"=>"Entrada/Salida Pendiente",
                                "updated_at"=>Carbon::now()
                            ]);
                        }
                    } elseif($requisicion->estado === "Entrada/Salida Pendiente") {
                        if ($req->input('entrada') === "Completo"){
                            Requisiciones::where('id_requisicion',$id)->update([
                                "estado"=>"Entrada Pendiente",
                                "updated_at"=>Carbon::now()
                            ]);
                        } else {
                            Requisiciones::where('id_requisicion',$id)->update([
                                "estado"=>"Entrada/Salida Pendiente",
                                "updated_at"=>Carbon::now()
                            ]);
                        }
                    } elseif ($requisicion->estado === "En Almacen"){
                        if ($req->input('entrada') === "Completo"){
                            Requisiciones::where('id_requisicion',$id)->update([
                                "estado"=>"Entregado",
                                "updated_at"=>Carbon::now()
                            ]);
                        } else {
                            Requisiciones::where('id_requisicion',$id)->update([
                                "estado"=>"Salida Pendiente",
                                "updated_at"=>Carbon::now()
                            ]);
                        }
                    }                           
                }

                Logs::create([
                    "user_id"=>session('loginId'),
                    "requisicion_id"=>$id,
                    "table_name"=>"Salidas",
                    "action"=>"Se ha registrado una nueva salida de requisicion".$id,
                    "created_at"=>Carbon::now(),
                    "updated_at"=>Carbon::now()
                ]);
            }
        }
        session()->forget('salida');
        return redirect('salidas/Almacen')->with('salida','salida');

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

        $refaccion = Almacen::where('clave',$req->input('refaccion'))->first();

        if($refaccion->cantidad >= $req->input('cantidad') && $req->input('cantidad') > 0){
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
    
    public function createSalidaAlm(Request $req, $id){
        $salida = session()->get('salida', []);
        if (empty($salida)){
            return back()->with('vacio','vacio');
        } else{
            for ($i = 0; $i < count($salida); $i++) {
                $refaccion = Almacen::where(function($query) use ($i, $salida) {
                    $query->where('clave', $salida[$i]['id']);            
                })->first();
                if ($refaccion->cantidad < $salida[$i]['cantidad']){
                    return back()->with('insuficiente','insuficiente');
                } else{
                    Salidas::create([
                        "requisicion_id"=>$id,
                        "cantidad"=>$salida[$i]['cantidad'],
                        "usuario_id"=>session('loginId'),
                        "refaccion_id"=>$salida[$i]['id'],
                        "created_at"=>Carbon::now(),
                        "updated_at"=>Carbon::now()
                    ]);

                    Almacen::where('clave',$salida[$i]['id'])->update([
                        "cantidad"=>$refaccion->cantidad - $salida[$i]['cantidad'],
                        "updated_at"=>Carbon::now()
                    ]);

                    if ($req->input('entrada') === "Completo"){
                        Requisiciones::where('id_requisicion',$id)->update([
                            "estado"=>"Entregado",
                            "updated_at"=>Carbon::now()
                        ]);
                    }else{
                        Requisiciones::where('id_requisicion',$id)->update([
                            "estado"=>"Salida Pendiente",
                            "updated_at"=>Carbon::now()
                        ]);
                    } 
                }
            }
            session()->forget('salida');
            return redirect('almacen/Almacen')->with('salida','salida');
        }              
    }
}