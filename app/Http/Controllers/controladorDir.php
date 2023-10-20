<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Requisiciones;
use App\Models\cotizaciones;
use Carbon\Carbon;
use DB;

class controladorDir extends Controller
{
    public function index(){
        return view("Direccion.index");
    }

    public function tableSolicitud(){
        $solicitudes = Requisiciones::select('requisiciones.id_requisicion', 'users.nombre', 'requisiciones.unidad_id', 'requisiciones.pdf', 'requisiciones.estado', 'requisiciones.created_at as fecha_creacion')
        ->join('users', 'requisiciones.usuario_id', '=', 'users.id')
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
