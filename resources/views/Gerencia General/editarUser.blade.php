@extends('plantillaGerGen')

@section('contenido')

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">USUARIOS</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Editar usuario</h6>
        </div>
        <div class="card-body">
            <h3 class="text-center">Datos de registro</h3>
            <form action="{{route('updateUser',$encargado->id)}}" method="POST">
                {!!method_field('PUT')!!}    
                @csrf
                <div class="form-group">
                    <label for="exampleFormControlInput1">Nombre(s):</label>
                    <input name="nombres" type="text" class="form-control" value="{{$encargado->nombres}}" placeholder="Nombre(s) del usuario">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Apellido Paterno:</label>
                    <input name="apepat" type="text" class="form-control" value="{{$encargado->apellidoP}}" placeholder="Apellido paterno del usuario" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Apellido Materno:</label>
                    <input name="apemat" type="text" class="form-control" value="{{$encargado->apellidoM}}" placeholder="Apellido materno del usuario" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Telefono:</label>
                    <input name="telefono" type="text" class="form-control" value="{{$encargado->telefono}}" placeholder="No° telefonico del usuario">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Correo:</label>
                    <input name="correo" type="text" class="form-control" value="{{$encargado->correo}}" placeholder="Correo electronico del usuario">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Contraseña:</label>
                    <input name="password" type="text" class="form-control" value="{{$encargado->password}}" placeholder="Contraseña del usuario">
                </div>

                <div class="form-group">
                    <label for="exampleFormControlSelect1">Rol del usuario:</label>
                    <select name="rol" class="form-control">
                        <option value="{{$encargado->rol}}"selected>{{$encargado->rol}}</option>
                        <option disabled value="">Selecciona el rol que tendrá el usuario</option>
                        <option>Gerencia General</option>
                        <option>Gerente Area</option>
                        <option>Compras</option>
                        <option>Otro</option>
                    </select>
                </div>

                @if ($encargado->rol === "Gerencia General" || $encargado->rol === "Compras")
                    <div class="form-group">
                        <label for="exampleFormControlInput1">¿A que departamento pertence?</label>
                        <select name="dpto" class="form-control">
                            <option value="{{$encargado->departamento}}"selected>{{$encargado->departamento}}</option>
                            <option disabled value="">Selecciona el departamento al que pertenece el usuario</option>
                            <option>Direccion</option>
                            <option>Compras</option>
                            <option>Finanzas</option>
                            <option>Logística</option>
                            <option>Mantenimiento</option>
                            <option>RH</option>            
                            <option>Ventas</option>
                        </select>
                    </div>
                @else 
                    <div class="form-group">
                        <label for="exampleFormControlInput1">¿A que departamento pertence?</label>
                        <select name="dpto" class="form-control" required>
                            <option value="{{$encargado->departamento}}"selected>{{$encargado->departamento}}</option>
                            <option disabled value="">Selecciona el departamento al que pertenece el usuario</option>
                            <option>Direccion</option>
                            <option>Compras</option>
                            <option>Finanzas</option>
                            <option>Logística</option>
                            <option>Mantenimiento</option>
                            <option>RH</option>            
                            <option>Ventas</option>
                        </select>
                    </div>
                @endif                
                <button type="submit" class="btn btn-primary">Editar usuario</button>
            </form>
        </div>
    </div>
</div>
@endsection