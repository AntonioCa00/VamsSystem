@extends('plantilla')

@section('Contenido')

@if(session()->has('regis'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Unidad registrada',
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
        title: 'Unidad Editada',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">UNIDADES</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a class="btn btn-primary" href="{{route('CreateUnidad')}}">Registrar nueva unidad</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID_unidad</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Año Unidad</th>
                            <th>Marca</th>
                            <th>Opciones:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($unidades as $unidad)
                        <tr>
                            <th>{{$unidad->id_unidad}}</th>
                            <th>{{$unidad->tipo}}</th>
                            <th>{{$unidad->estado}}</th>
                            <th>{{$unidad->anio_unidad}}</th>
                            <th>{{$unidad->marca}}</th>
                            <th>
                                <a href="{{route('editUnidad',$unidad->id_unidad)}}" class="btn btn-primary">Editar</a>
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