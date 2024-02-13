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

    /* 
        TODO: Función que valida la existencia del usuario en la base de datos y permitir o rechazar el acceso

        @returns redireccion a la vista permitida
    */
    public function loginUser (Request $req){
        $user = User::where('nombres', '=', $req->nombre)->first(); //Hace la consulta basada en el nombre de usuario
        if ($user){ //valida que exista un usuario llamdado así
            if($user->password == $req->contrasena){  //Compara las contraseñas para iniciar sesion

                //Agrega todos los datos del usuario a la session activa
                $req->session()->put('loginId',$user->id);
                $req->session()->put('loginNombres',$user->nombres);
                $req->session()->put('loginApepat',$user->apellidoP);
                $req->session()->put('loginApemat',$user->apellidoM);
                $req->session()->put('rol', $user->rol);
                $req->session()->put('departamento', $user->departamento);

                //Asigna una redireccion a la vista correspondiente segun su rol
                if($user->rol == "Compras"){
                    return redirect('inicio/Compras')->with('entra','entra');
                }elseif ($user->rol == "Gerencia General"){
                    return redirect('inicio/GerenciaGeneral')->with('entra','entra');            
                }elseif ($user->rol == "Gerente Area") {
                    return redirect('inicio/GtArea')->with('entra','entra');
                } elseif ($user->rol == "Almacen"){
                    return redirect('inicio/Almacen')->with('entra','entra');
                } else{                    
                    return redirect('inicio')->with('entra','entra');
                }
            } else{
                //en caso de no coincidir las contraseñas regresa un mensaje de revisar contraseñas
                return back()->with('contras','contras');    
            }
        } else { 
            //en caso de no encontrar el nombre de usuario regresa un mensaje de revisar información
            return back()->with('error','error');
        }
    }

    /*
    TODO: Función que cierra la sesion iniciada
    
    @return redireccion a la vista del login
    */
    public function logout(){
        if(Session::has('loginId')){ //valida si existe una session creada
            Session::pull('loginId'); //Termina la session
            session()->flush(); //Elimina la informacion almacenada en la session del usuario
            return redirect('/');
        }
    }
}