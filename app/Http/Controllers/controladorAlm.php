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
    public function index()
    {
        return view("Almacen.index");
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
                        Almacen::create([
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
        $refacciones = Almacen::get()->where('estatus','1')->orderBy('nombre','asc')->get();
        return view('Almacen.almacen',compact ('refacciones'));
    }

    public function createRefaccion(){
        return view('Almacen.crearRefaccion');
    }

    public function salidas(){
        $salidas = Requisiciones::select('requisiciones.id_requisicion','orden_compras.id_orden', 'entradas.id_entrada', 'users.nombre', 'requisiciones.unidad_id', 'requisiciones.pdf', 'requisiciones.estado', 'requisiciones.created_at as fecha_creacion')
        ->join('users', 'requisiciones.usuario_id', '=', 'users.id')
        ->join('cotizaciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
        ->join('orden_compras','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
        ->join('entradas','entradas.orden_id','=','orden_compras.id_orden')
        ->where('estado','En Almacen')
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
            return redirect('salidas/Almacen')->with('salida','salida');
        }              
    }
}