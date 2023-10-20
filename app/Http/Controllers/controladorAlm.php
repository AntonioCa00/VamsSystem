<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Almacen;
use App\Models\Salidas;
use App\Models\Requisiciones;
use App\Models\Entradas;
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

    public function requisiciones (){
        $solicitudes = Requisiciones::get();
        return view('Almacen.requisiciones', compact('solicitudes'));
    }

    public function entradas () {
        $entradas = Entradas::get();
        return view('Almacen.entradas', compact('entradas'));
    }

    public function almacen (){
        $refacciones = Almacen::get();
        return view('Almacen.almacen',compact ('refacciones'));
    }

    public function createRefaccion(){
        return view('Almacen.crearRefaccion');
    }

    public function salidas(){
        $salidas = Salidas::get();
        return view('Almacen.salidas',compact('salidas'));
    }
}
