<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Models\Solicitudes;

class controladorEncargado extends Controller
{
    public function index(){
        return view('Encargado.index');
    }

    public function charts(){
        $Octubre = DB::table('compras')
            ->select(DB::raw('SUM(costo) as octubre'))
            ->whereBetween('created_at', ['2023-10-01 00:00:00', '2023-10-31 23:59:59'])
            ->get();
    
        $Septiembre = DB::table('compras')
            ->select(DB::raw('SUM(costo) as septiembre'))
            ->whereBetween('created_at', ['2023-09-01 00:00:00', '2023-09-30 23:59:59'])
            ->get();
    
        $Agosto = DB::table('compras')
            ->select(DB::raw('SUM(costo) as agosto'))
            ->whereBetween('created_at', ['2023-08-01 00:00:00', '2023-08-31 23:59:59'])
            ->get();
    
        $Julio = DB::table('compras')
            ->select(DB::raw('SUM(costo) as julio'))
            ->whereBetween('created_at', ['2023-07-01 00:00:00', '2023-07-31 23:59:59'])
            ->get();
    
        return view('Encargado.charts', [
            'octubre' => $Octubre,
            'septiembre' => $Septiembre,
            'agosto' => $Agosto,
            'julio' => $Julio,
        ]);
    }    

    //VISTAS DE LAS TABLAS

    public function tableUnidad()
    {   
        $unidades = DB::table('unidades')->get();
        return view('Encargado.unidad',compact('unidades'));
    }

    public function tableRefaccion(){
        $refacciones = DB::table('refacciones')->get();
        return view('Encargado.refaccion',compact('refacciones'));
    }

    public function tableSalidas(){
        $salidas = DB::table('vista_salidas')->get();
        return view('Encargado.salidas',compact('salidas'));
    }
    
    public function tableCompras(){
        $compras = DB::table('vista_compras')->get();
        return view('Encargado.compras',compact('compras'));
    }

    public function tableSolicitud(){

        $solicitudes = DB::table('solicitudes')
        ->select('solicitudes.id_solicitud', 'users.nombre as encargado', 'solicitudes.created_at', 'solicitudes.estado', 'solicitudes.unidad_id', 'solicitudes.Descripcion', 'refacciones.descripcion as refaccion')
        ->join('users','solicitudes.encargado_id','=','users.id')
        ->join('refacciones','solicitudes.refaccion_id','=','refacciones.id_refaccion')
        ->where('solicitudes.encargado_id','=',session('loginId'))
        ->get();
        return view('Encargado.solicitudes',compact('solicitudes'));
    }

    public function createSolicitud(){
        $unidades = DB::table('unidades')->select('id_unidad as IdUnidad')->get();
        $refacciones = DB::table('refacciones')->select('id_refaccion','nombre')->get();

        //return $unidades;
        return view('Encargado.crearSolicitud',compact('unidades','refacciones'));
    }

    public function insertSolicitud(Request $req){

        Solicitudes::create([
            "encargado_id"=>session('loginId'),
            "estado"=>"En proceso",
            "unidad_id"=>$req->input('Unidad'),
            "descripcion"=>$req->input('Descripcion'),
            "refaccion_id"=>$req->input('Refaccion'),
            "estatus"=>"1",
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
            ]);

            DB::table('logs')->insert([
                "user_id"=>session('loginId'),
                "table_name"=>"Solicitudes",
                "action"=>"Se ha registrado una nueva solicitud:"."$req->input('Descripcion')",
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);

        return redirect()->route('solicitudesEnc')->with('solicitado','solicitado');
    }
}
