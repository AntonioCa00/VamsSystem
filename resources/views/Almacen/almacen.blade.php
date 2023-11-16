@extends('plantillaAlm')

@section('contenido')

@if(session()->has('agregado'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Articulos agregados a almacen',
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
        title: 'Articulo editado en almacen',
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
        title: 'Articulo eliminado en almacen',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">ALMACEN</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="{{route('createRefaccion')}}" class="btn btn-primary">Añadir nueva refaccion</a>
            <a class="btn btn-primary" href="{{route('requisicionesAlm')}}" style="margin-left: 75%;">Dar salida</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Codigo:</th>
                            <th>Nombre</th>
                            <th>Marca</th>
                            <th>Año</th>  
                            <th>Modelo</th>                                                      
                            <th>Descripcion</th>
                            <th>Stock:</th>
                            <th>Opciones:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($refacciones as $refaccion)
                        <tr>
                            <th>{{$refaccion->id_refaccion}}</th>
                            <th>{{$refaccion->nombre}}</th>
                            <th>{{$refaccion->marca}}</th>
                            <th>{{$refaccion->anio}}</th>
                            <th>{{$refaccion->modelo}}</th>
                            <th>{{$refaccion->descripcion}}</th>
                            <th>{{$refaccion->stock}}</th>
                            <th>    
                                <a href="{{route('editRefaccion',$refaccion->id_refaccion)}}" class="btn btn-primary">Editar</a>                                                       
                                <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#eliminarRef{{$refaccion->id_refaccion}}">
                                    Eliminar
                                </a>
                                <!-- DeleteRef Modal-->
                                <div class="modal fade" id="eliminarRef{{$refaccion->id_refaccion}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                    aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">¿Ha tomado una decisión?</h5>
                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">X</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">Selecciona confirmar para eliminar esta refacción</div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">cancelar</button>
                                                    <form action="{{route('deleteRefaccion',$refaccion->id_refaccion)}}" method="POST">
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
<!-- /.container-fluid -->

@endsection