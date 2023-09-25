<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Unidades;
use App\Models\Solicitudes;
use App\Models\Compras;
use App\Models\Cotizaciones;
use Session;
use DB;
use Carbon\Carbon;

class controladorBD extends Controller
{

    public function login(){
        return view("login");
    }

    public function loginUser (Request $req){
        $user = User::where('correo', '=', $req->correo)->first();
        if ($user){
            if($user->password == $req->contrasena){
                if($user->rol == "Administrador"){
                    $req->session()->put('loginId',$user->id);
                    $req->session()->put('loginNombre',$user->Nombre);
                    return redirect('inicio');
                } elseif ($user->rol == "Direccion") {
                    $req->session()->put('loginId',$user->id);
                    $req->session()->put('loginNombre',$user->Nombre);
                    return redirect('inicioDir');
                } else{
                    $req->session()->put('loginId',$user->id);
                    $req->session()->put('loginNombre',$user->Nombre);
                    return redirect('inicioEnc');
                }
            } else{
                return back()->with('error','error');    
            }
        } else {
            return back() ->with('error','error');
        }
    }

    public function logout(){
        if(Session::has('loginId')){
            Session::pull('loginId');
            return redirect('/');
        }
    }

    public function index(){
        return view("Admin.index");
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
    
        return view('Admin.charts', [
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
        return view('Admin.unidad',compact('unidades'));
    }

    public function tableEncargado(){
        $encargados = DB::table('users')->where('estatus','1')->orderBy('nombre')->get();
        return view('Admin.encargado',compact('encargados'));
    }

    public function tableRefaccion(){
        $refacciones = DB::table('refacciones')->get();
        return view('Admin.refaccion',compact('refacciones'));
    }

    public function tableSalidas(){
        $salidas = DB::table('vista_salidas')->get();
        return view('Admin.salidas',compact('salidas'));
    }
    
    public function tableCompras(){
        $compras = DB::table('vista_compras')->get();
        return view('Admin.compras',compact('compras'));
    }

    public function tableSolicitud(){
        $solicitudes = DB::table('vista_solicitudes')->get();
        return view('Admin.solicitudes',compact('solicitudes'));
    }

    //VISTAS DE FORMULARIOS

    public function CreateUnidad(){
        return view('Admin.crearUnidad');
    }

    public function insertUnidad(Request $req){

            Unidades::create([
            "id_unidad"=>$req->input('id_unidad'),
            "tipo"=>$req->input('tipo'),
            "estado"=>$req->input('estado'),
            "anio_unidad"=>$req->input('anio_unidad'),
            "marca"=>$req->input('marca'),
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
        return view('Admin.editarUnidad',compact('unidad'));
    }

    public function updateUnidad(Request $req, $id){
        DB::table('unidad') ->where('id_unidad',$id)-> update ([
            "tipo"=>$req->input('tipo'),
            "estado"=>$req->input('estado'),
            "ano_unidad"=>$req->input('anio_unidad'),
            "marca"=>$req->input('marca'),
        ]);

        return redirect()->route('unidades')->with('upddate','update');
    }

    public function createUser(){
        return view('Admin.crearUser');
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
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        DB::table('logs')->insert([
            "user_id"=>session('loginId'),
            "table_name"=>"Users",
            "action"=>"Se ha registrado un nuevo usuario:".$req->input('nombre'),
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);
        
        return redirect()->route('encargados')->with('creado','creado');
    }

    public function editUser($id){
        $encargado = User::where('id',$id)->first();
        return view('Admin.editarUser',compact('encargado'));
    }

    public function updateUser(Request $req, $id){
        User::where('id',$id)->update([
            "Nombre"=>$req->input('nombre'),
            "telefono"=>$req->input('telefono'),
            "correo"=>$req->input('correo'),
            "password"=>$req->input('password'),
            "rol"=>$req->input('rol'),
            "estatus"=>'1',
            "updated_at"=>Carbon::now()
        ]);

        return redirect('tabla-encargados')->with('editado','editado');
    }

    public function deleteUser($id){
        User::where('id',$id)->update([
            "estatus"=>'0',
            "updated_at"=>Carbon::now()
        ]);

        return redirect('tabla-encargados')->with('eliminado','eliminado');
    }

    public function validarSoli($id){
        Solicitudes::where('id_solicitud', $id)->update([
            "estado" => "Validado",
            "updated_at" => Carbon::now()
        ]);

        DB::table('logs')->insert([
            "user_id"=>session('loginId'),
            "table_name"=>"Solicitudes",
            "action"=>"Se ha validado una solicitud:".$id,
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        return back()->with('validado','validado');    
    }

    public function createCotiza($id){
        $cotizaciones = Cotizaciones::where('solicitud_id', $id)->where('estatus','1')->get();
        return view('Admin.crearCotizacion',compact('cotizaciones','id'));
    }

    public function insertCotiza(Request $req){
        if ($req->hasFile('archivo') && $req->file('archivo')->isValid()){
            $archivo = $req->file('archivo');
            $nombreArchivo = uniqid() . '.' . $archivo->getClientOriginalExtension();
    
            $archivo->storeAs('archivos', $nombreArchivo, 'public');
            $archivo_pdf = 'archivos/' . $nombreArchivo;

            Cotizaciones::create([
                "solicitud_id"=>$req->input('solicitud'),
                "administrador_id"=>session('loginId'),
                "Proveedor"=>$req->input('proveedor'),
                "Costo_total"=>$req->input('costo'),
                "archivo_pdf"=>$archivo_pdf,
                "estatus"=>"1",
                "created_at"=>Carbon::now(),        
                "updated_at"=>Carbon::now()
            ]);
    
            Solicitudes::where('id_solicitud',$req->input('solicitud'))->update([
                "estado" => "En proceso",
                "updated_at" => Carbon::now()
            ]);
    
            DB::table('logs')->insert([
                "user_id"=>session('loginId'),
                "table_name"=>"Solicitudes",
                "action"=>"Se ha hecho una cotizacion en la solicitud:".$req->input('solicitud'),
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]); 
            return redirect('tabla-solicitud')->with('cotizacion','cotizacion');
        } else {
            return back()->with('error', 'No se ha seleccionado ningún archivo.');
        }
    }

    public function deleteCotiza($id){
        Cotizaciones::where('id_cotizacion', $id)->update([
            "estatus" => "0",
            "updated_at" => Carbon::now()
        ]);

        return back()->with('eliminado','eliminado');    
    }

    public function createCompra(){
        $solicitudes = DB::table('vista_solicitudes')->where('estado','Validado')->get();
        return view('Admin.crearCompra',compact('solicitudes'));
    }

    public function insertCompra(Request $req){
        Compras::create([
            "solicitud_id"=>$req->input('solicitudId'),
            "costo"=>$req->input('costo'),
            "factura"=>$req->input('factura'),
            "estatus"=>'1',
            "created_at"=>Carbon::now(),        
            "updated_at"=>Carbon::now(),
            "admin_id"=>session('loginId')
        ]);

        DB::table('logs')->insert([
            "user_id"=>session('loginId'),
            "table_name"=>"Compras",
            "action"=>"Se ha registrado una nueva compra:".$req->input('factura'),
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        Solicitudes::where('id_solicitud',$req->input('solicitudId'))->update([
            "estado"=>"Comprado",
            "updated_at" => Carbon::now()
        ]);

        return redirect('tabla-compras  ')->with('comprado','comprado');
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