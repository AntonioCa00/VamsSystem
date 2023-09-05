<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class controladorBD extends Controller
{

    public function login(){
        return view("login");
    }

    public function index(){
        return view("index");
    }

    public function charts(){
        $Octubre = DB::table('compra')
            ->select(DB::raw('SUM(costo) as octubre'))
            ->whereBetween('fecha_compra', ['2023-10-01 00:00:00', '2023-10-31 23:59:59'])
            ->get();
    
        $Septiembre = DB::table('compra')
            ->select(DB::raw('SUM(costo) as septiembre'))
            ->whereBetween('fecha_compra', ['2023-09-01 00:00:00', '2023-09-30 23:59:59'])
            ->get();
    
        $Agosto = DB::table('compra')
            ->select(DB::raw('SUM(costo) as agosto'))
            ->whereBetween('fecha_compra', ['2023-08-01 00:00:00', '2023-08-31 23:59:59'])
            ->get();
    
        $Julio = DB::table('compra')
            ->select(DB::raw('SUM(costo) as julio'))
            ->whereBetween('fecha_compra', ['2023-07-01 00:00:00', '2023-07-31 23:59:59'])
            ->get();
    
        return view('charts', [
            'octubre' => $Octubre,
            'septiembre' => $Septiembre,
            'agosto' => $Agosto,
            'julio' => $Julio,
        ]);
    }    

    public function tableUnidad()
    {   
        $unidades = DB::table('unidad')->get();
        return view('unidad',compact('unidades'));
    }

    public function tableEncargado(){
        $encargados = DB::table('encargado')->get();
        return view('encargado',compact('encargados'));
    }

    public function tableRefaccion(){
        $refacciones = DB::table('refaccion')->get();
        return view('refaccion',compact('refacciones'));
    }

}
