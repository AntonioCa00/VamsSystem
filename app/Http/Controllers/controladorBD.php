<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Unidades;
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
                } else {
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
        $encargados = DB::table('users')->orderBy('nombre')->get();
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
                "action"=>"Se ha registrado una nueva unidad:"."$req->input('id_unidad')",
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);

        return redirect()->route('unidades')->with('regis','regis');
    }

    public function editUnidad($id){
        $unidad= DB::table('unidad')->where('id_unidad',$id) ->first();
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
            "action"=>"Se ha registrado un nuevo usuario:"."$req->input('id_unidad')",
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        /*

        GUARDAR USUARIO EN FLY.IO PARA USAR ESAS CREDENCIALES EN LA APP

        try {
            $client = new Client();
            // Realizar una solicitud POST a la URL de la API externa
            $response = $client->request('POST', 'https://practica2.fly.dev/insert-user', [
                'json' => [
                    'nombres' => $req->input('nombre'),
                    'apellidos' =>$req->input('nombre'),
                    'correo' => $req->input('correo'),
                    'contrasena' => $password,
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ]);
    
            // Obtener la respuesta JSON de la API externa
            $data = json_decode($response->getBody(), true);
    
            // Puedes manejar la respuesta como desees, por ejemplo, devolverla como JSON
            return redirect()->route('encargados')->with('upddate','update');
    
        } catch (\Exception $e) {
            // Manejar errores, por ejemplo, devolver un mensaje de error
            return response()->json(['error' => $e->getMessage()], 500);
        }
        */

        return redirect()->route('encargados')->with('creado','creado');

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