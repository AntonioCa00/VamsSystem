@extends('plantillaGtArea')

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
                    <label for="exampleFormControlInput1">Nombre completo:</label>
                    <input name="nombre" type="text" class="form-control" value="{{$encargado->nombres}}" placeholder="Nombre completo del usuario">
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
                        <option>Administrador</option>
                        <option>Encargado</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Editar usuario</button>
            </form>
        </div>
    </div>
</div>
@endsection