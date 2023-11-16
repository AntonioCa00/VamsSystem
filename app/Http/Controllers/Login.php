<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Session;

class Login extends Controller
{
    
    public function login(){
        return view("login");
    }

    public function loginUser (Request $req){
        $user = User::where('nombre', '=', $req->nombre)->first();
        if ($user){
            if($user->password == $req->contrasena){

                $req->session()->put('loginId',$user->id);
                $req->session()->put('loginNombre',$user->nombre);
                $req->session()->put('rol', $user->rol);


                if($user->rol == "Compras"){
                    return redirect('inicio/Compras')->with('entra','entra');
                } elseif ($user->rol == "Direccion") {
                    return redirect('inicio/Direccion')->with('entra','entra');
                } elseif ($user->rol == "Almacen"){
                    return redirect('inicio/Almacen')->with('entra','entra');
                } else{
                    return redirect('almacen')->with('entra','entra');
                }
            } else{
                return back()->with('contras','contras');    
            }
        } else {
            return back()->with('error','error');
        }
    }

    public function logout(){
        if(Session::has('loginId')){
            Session::pull('loginId');
            session()->forget('datos');
            session()->forget('ordenCom');
            session()->forget('entrada');
            session()->forget('salida');
            session()->forget('datosAlm');
            return redirect('/');
        }
    }
}
