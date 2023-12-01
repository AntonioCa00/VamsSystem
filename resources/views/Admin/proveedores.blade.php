@extends('plantillaAdm')

@section('Contenido')

@if(session()->has('insert'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Proveedor registrado!',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

@if(session()->has('update'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Proveedor actualizado!',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

@if(session()->has('delete'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Proveedor eliminado!',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">PROVEEDORES</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a class="btn btn-primary" href="{{route('createProveedor')}}">Registrar nuevo proveedor</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nombre:</th>
                            <th>Telefono:</th>
                            <th>Correo:</th>                            
                            <th>Opciones:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($proveedores as $proveedor)
                        <tr>                    
                            <th>{{$proveedor->nombre}}</th>
                            <th>{{$proveedor->telefono}}</th>
                            <th>{{$proveedor->correo}}</th>
                            <th>
                                <a href="{{route('editProveedor',$proveedor->id_proveedor)}}" class="btn btn-primary">Editar</a>
                                <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#eliminarProv{{$proveedor->id_proveedor}}">
                                    Eliminar
                                </a>
                                <!-- Logout Modal-->
                                <div class="modal fade" id="eliminarProv{{$proveedor->id_proveedor}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">¿Ha tomado una decisión?</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">X</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">Selecciona confirmar para eliminar este proveedor</div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">cancelar</button>
                                                <form action="{{route('deleteProveedor',$proveedor->id_proveedor)}}" method="post">
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