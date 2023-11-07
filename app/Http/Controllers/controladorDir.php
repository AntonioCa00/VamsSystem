<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Unidades;
use App\Models\Entradas;
use App\Models\Requisiciones;
use App\Models\cotizaciones;
use App\Models\Almacen;
use Carbon\Carbon;
use DB;

class controladorDir extends Controller
{
    public function index(){
        return view("Direccion.index");
    }

    public function tableRefaccion(){
        $refacciones = Almacen::get()->where("estatus",1);
        return view('Direccion.refaccion',compact('refacciones'));
    }

    public function tableUnidad()
    {   
        $unidades = Unidades::where('estatus','1')->where('estado','Activo')->get();
        return view('Direccion.unidad',compact('unidades'));
    }

    public function CreateUnidad(){
        return view('Direccion.crearUnidad');
    }

    public function insertUnidad(Request $req){

        Unidades::create([
        "id_unidad"=>$req->input('id_unidad'),
        "tipo"=>$req->input('tipo'),
        "estado"=>$req->input('estado'),
        "anio_unidad"=>$req->input('anio_unidad'),
        "marca"=>$req->input('marca'),
        "kilometraje"=>$req->input('kms'),
        "estatus"=>"1",
        "created_at"=>Carbon::now(),
        "updated_at"=>Carbon::now()
        ]);

        DB::table('logs')->insert([
            "user_id"=>session('loginId'),
            "table_name"=>"Unidades",
            "action"=>"Se ha registrado una nueva unidad:".$req->input('id_unidad'),
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        return redirect()->route('unidades')->with('regis','regis');
    }

    public function editUnidad($id){
        $unidad= Unidades::where('id_unidad',$id)->first();
        return view('Direccion.editarUnidad',compact('unidad'));
    }

    public function updateUnidad(Request $req, $id){
        Unidades::where('id_unidad',$id)->update([
            "id_unidad"=>$req->input('id_unidad'),
            "tipo"=>$req->input('tipo'),
            "estado"=>$req->input('estado'),
            "anio_unidad"=>$req->input('anio_unidad'),
            "marca"=>$req->input('marca'),
            "kilometraje"=>$req->input('kms'),
            "estatus"=>"1",
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        return redirect()->route('unidades')->with('update','update');
    }

    public function deleteUnidad($id){        
        Unidades::where('id_unidad',$id)->update([
            "estatus"=>"0",
            "updated_at"=>Carbon::now()->format('Y-m-d')
        ]);
        return back()->with('eliminado','eliminado');
    }

    public function bajaUnidad($id){        
        Unidades::where('id_unidad',$id)->update([            
            "estado"=>"Inactivo",
            "updated_at"=>Carbon::now()->format('Y-m-d')
        ]);
        return back()->with('baja','baja');
    }

    public function activarUnidad(){
        $unidades = Unidades::where("estado",'Inactivo')->get();
        return view('Direccion.activaUnidad',compact('unidades'));
    }

    public function activateUnidad($id){
        Unidades::where('id_unidad',$id)->update([
            "estado"=>"Activo",
            "updated_at"=>Carbon::now()
        ]);

        DB::table('logs')->insert([
            "user_id"=>session('loginId'),
            "table_name"=>"Unidades",
            "action"=>"Se ha activado una unidad:".$id,
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        return redirect()->route('unidades')->with('activado','activado');
    }

    public function tableEntradas(){
        $entradas = Entradas::select('entradas.id_entrada','requisiciones.pdf as reqPDF','orden_compras.pdf as ordPDF','entradas.factura','entradas.created_at')
        ->join('orden_compras','entradas.orden_id','=','orden_compras.id_orden')
        ->join('cotizaciones','orden_compras.cotizacion_id','=','cotizaciones.id_cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','=','requisiciones.id_requisicion')
        ->get();
        return view('Direccion.entradas',compact('entradas'));
    }

    public function tableSolicitud(){
        $solicitudes = Requisiciones::select('requisiciones.id_requisicion', 'users.nombre', 'requisiciones.unidad_id', 'requisiciones.pdf', 'requisiciones.estado', 'requisiciones.created_at as fecha_creacion')
        ->join('users', 'requisiciones.usuario_id', '=', 'users.id')
        ->where('requisiciones.estado','!=','Eliminado')
        ->get();
        return view('Direccion.solicitudes',compact('solicitudes'));
    }
    
    public function cotizaciones($id){
        $cotizaciones = Cotizaciones::select('cotizaciones.id_cotizacion','requisiciones.id_requisicion as requisicion_id','users.nombre as usuario','requisiciones.pdf as reqPDF','cotizaciones.pdf as cotPDF')
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
        User::create([
            "Nombre"=>$req->input('nombre'),
            "telefono"=>$req->input('telefono'),
            "correo"=>$req->input('correo'),
            "password"=>$password,
            "rol"=>$req->input('rol'),
            "estatus"=>'1',
            "created_at"=>Carbon::now()->format('Y-m-d'),
            "updated_at"=>Carbon::now()->format('Y-m-d')
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
            "updated_at"=>Carbon::now()->format('Y-m-d')
        ]);

        DB::table('logs')->insert([
            "user_id"=>session('loginId'),
            "table_name"=>"Usuarios",
            "action"=>"Se ha actualizado un usuario: ".$req->input('nombre'),
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        return redirect('tabla-encargados')->with('editado','editado');
    }

    public function deleteUser($id){
         User::where('id',$id)->update([
             "estatus"=>'0',
             "updated_at"=>Carbon::now()->format('Y-m-d')
         ]);

         DB::table('logs')->insert([
            "user_id"=>session('loginId'),
            "table_name"=>"Usuarios",
            "action"=>"Se ha eliminado un usuario: ".$req->input('nombre'),
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

         return redirect('tabla-encargados')->with('eliminado','eliminado');
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

        DB::table('logs')->insert([
            "user_id"=>session('loginId'),
            "table_name"=>"Solicitudes",
            "action"=>"Se ha validado una cotizacion de la solicitud: ".$sid,
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);
        return redirect('solicitudes/Direccion')->with('validacion','validacion');
    }
}