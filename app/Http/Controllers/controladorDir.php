<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\cotizaciones;
use App\Models\Solicitudes;
use Carbon\Carbon;
use DB;

class controladorDir extends Controller
{
    public function index(){
        return view("Direccion.index");
    }

    public function tableSolicitud(){
        $solicitudes = DB::table('vista_solicitudes')->get();
        return view('Direccion.solicitudes',compact('solicitudes'));
    }

    public function cotizaciones($id){
        $cotizaciones = Cotizaciones::where('solicitud_id', $id)->where('estatus','1')->get();
        return view('Direccion.cotizaciones',compact('cotizaciones','id'));
    }

    public function selectCotiza($id,$sid){
        Cotizaciones::where('id_cotizacion', '!=', $id)
        ->where('solicitud_id', $sid)
        ->update([
            "estatus" => "0",
            "updated_at" => Carbon::now()
        ]);


        Solicitudes::where('id_solicitud',$sid)->update([
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
        return redirect('tabla-solicitudesDir')->with('validacion','validacion');
    }
}
