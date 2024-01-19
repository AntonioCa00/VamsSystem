<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Orden_compras;
use App\Models\Requisiciones;
use App\Models\Unidades;
use App\Models\Cotizaciones;
Use Carbon\Carbon;
use DB;

use Illuminate\Http\Request;

class controladorGerenciaGen extends Controller
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
        $completas = Requisiciones::where('estado', 'Comprado')->count();
        $pendiente = Requisiciones::where('estado','!=', 'Comprado')->where('estado','!=','Rechazado')->count();
        return view("Gerencia General.index",[
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

    public function tableSolicitud(){
        $solicitudes = Requisiciones::select('requisiciones.id_requisicion', 'users.nombres', 'requisiciones.unidad_id', 'requisiciones.pdf', 'requisiciones.estado','orden_compras.pdf as ordenCompra', 'requisiciones.created_at as fecha_creacion')
        ->join('users', 'requisiciones.usuario_id', '=', 'users.id')
        ->leftJoin('cotizaciones','cotizaciones.requisicion_id','requisiciones.id_requisicion')
        ->leftJoin('orden_compras','orden_compras.cotizacion_id','cotizaciones.id_cotizacion')
        ->orderBy('requisiciones.created_at','desc')
        ->get();

        return view('Gerencia General.solicitudes',compact('solicitudes'));
    }

    public function tableEncargado(){
        $encargados = User::where('estatus','1')->orderBy('nombres')->get()
        ->where("estatus",1);
        return view('Gerencia General.encargado',compact('encargados'));
    }

    public function createUser(){
        return view('Gerencia General.crearUser');
    }

    public function insertUser(Request $req){
        $password = $this->generateRandomPassword();

        if($req->rol === "Otro"){
            DB::table('users')->insert([
                "nombres"=>$req->input('nombres'),
                "apellidoP"=>$req->input('apepat'),
                "apellidoM"=>$req->input('apemat'),
                "telefono"=>$req->input('telefono'),
                "correo"=>$req->input('correo'),
                "password"=>$password,
                "rol"=>'General',
                "departamento"=>$req->input('departamento'),
                "estatus"=>'1',
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);
        }elseif ($req->rol === "Gerente Area"){
            DB::table('users')->insert([
                "nombres"=>$req->input('nombres'),
                "apellidoP"=>$req->input('apepat'),
                "apellidoM"=>$req->input('apemat'),
                "telefono"=>$req->input('telefono'),
                "correo"=>$req->input('correo'),
                "password"=>$password,
                "rol"=>$req->input('rol'),
                "departamento"=>$req->input('departamento'),
                "estatus"=>'1',
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);
        } else {
            DB::table('users')->insert([
                "nombres"=>$req->input('nombres'),
                "apellidoP"=>$req->input('apepat'),
                "apellidoM"=>$req->input('apemat'),
                "telefono"=>$req->input('telefono'),
                "correo"=>$req->input('correo'),
                "password"=>$password,
                "rol"=>$req->input('rol'),
                "estatus"=>'1',
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]); 
        }
        return redirect()->route('encargados')->with('creado','creado');
    }

    public function editUser($id){
        $encargado = User::where('id',$id)->first();
        return view('Gerencia General.editarUser',compact('encargado'));
    }

    public function updateUser(Request $req, $id){
        User::where('id',$id)->update([
            "nombres"=>$req->input('nombres'),
            "apellidoP"=>$req->input('apepat'),
            "apellidoM"=>$req->input('apemat'),
            "telefono"=>$req->input('telefono'),
            "correo"=>$req->input('correo'),
            "password"=>$req->input('password'),
            "rol"=>$req->input('rol'),
            "estatus"=>'1',
            "updated_at"=>Carbon::now()
        ]);

        return redirect()->route('encargados')->with('editado','editado');
    }

    public function deleteUser($id){
         User::where('id',$id)->update([
             "estatus"=>'0',
             "updated_at"=>Carbon::now()
         ]);

         return redirect()->route('encargados')->with('eliminado','eliminado');
    }
    
    public function unidadesGerGen(){
        $unidades = Unidades::where('estatus','1')
        ->where('id_unidad','!=','1')
        ->where('estado','Activo')
        ->orderBy('id_unidad','asc')->get();
        
        return view('Gerencia General.unidad',compact('unidades'));
    }

    public function deleteSolicitud($id){
        Requisiciones::where('id_requisicion',$id)->delete();

        return back()->with('eliminado','eliminado');  
    }

    //Funcion para poder visualizar las cotizaciones por requisicion
    public function cotizaciones($id){
        $cotizaciones = Cotizaciones::select('requisiciones.id_requisicion','cotizaciones.id_cotizacion','requisiciones.PDF','cotizaciones.PDF as cotizacion')
        ->join('requisiciones','cotizaciones.requisicion_id','requisiciones.id_requisicion')
        ->where('requisicion_id',$id)
        ->get();

        return view('Gerencia General.cotizaciones',compact('cotizaciones'));
    }

    public function generateRandomPassword() {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $password = '';
    
        for ($i = 0; $i < 6; $i++) {
            $index = random_int(0, strlen($characters) - 1);
            $password .= $characters[$index];
        }
    
        return $password;
    }
}