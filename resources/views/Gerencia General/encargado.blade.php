@extends('plantillaGerGen')

@section('contenido')

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

@if(session()->has('editado'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Usuario editado',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

@if(session()->has('eliminado'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Usuario eliminado',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Usuarios</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a class="btn btn-primary" href="{{route('createUser')}}">Registrar nuevo usuario</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID_usuario:</th>
                            <th>Nombre:</th>
                            <th>Telefono:</th>
                            <th>Correo:</th>
                            <th>Rol:</th>
                            <th>Departamento:</th>
                            <th>Contraseña:</th>
                            <th>Editar:</th>
                            <th>Eliminar:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($encargados as $encargado)
                        <tr>
                            <th>{{$encargado->id}}</th>
                            <th>{{$encargado->nombre}}</th>
                            <th>{{$encargado->telefono}}</th>
                            <th>{{$encargado->correo}}</th>
                            <th>{{$encargado->rol}}</th>
                            <th>{{$encargado->departamento  }}</th>
                            <th>{{$encargado->password}}</th>
                            <th>        
                                <a href="{{route('editUser',$encargado->id)}}" class="btn btn-primary">Editar</a>    
                            </th>                                                   
                            <th>
                                <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#eliminarUser{{$encargado->id}}">
                                    Eliminar
                                </a>
                                <!-- DeleteUser Modal-->
                                <div class="modal fade" id="eliminarUser{{$encargado->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">¿Ha tomado una decisión?</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">X</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">Selecciona confirmar para eliminar este usuario</div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">cancelar</button>
                                                <form action="{{route('deleteUser',$encargado->id)}}" method="POST">
                                                    @csrf
                                                    {!!method_field('PUT')!!}    
                                                    <button type="submit" class="btn btn-primary">confirmar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </th>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection