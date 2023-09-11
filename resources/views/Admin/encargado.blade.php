@extends('plantilla')

@section('Contenido')

@if(session()->has('creado'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Usuario registrado',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">ENCARGADOS</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a class="btn btn-primary" href="{{route('createUser')}}">Registrar nuevo encargado</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID_encargado</th>
                            <th>Nombre</th>
                            <th>Telefono</th>
                            <th>Correo</th>
                            <th>Rol:</th>
                            <th>Contraseña</th>
                            <th>Opciones:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($encargados as $encargado)
                        <tr>
                            <th>{{$encargado->id}}</th>
                            <th>{{$encargado->Nombre}}</th>
                            <th>{{$encargado->telefono}}</th>
                            <th>{{$encargado->correo}}</th>
                            <th>{{$encargado->rol}}</th>
                            <th>{{$encargado->password}}</th>
                            <th>
                                <a href="" class="btn btn-primary">Editar</a>
                                <a href="" class="btn btn-primary">Eliminar</a>
                            </th>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

@endsection