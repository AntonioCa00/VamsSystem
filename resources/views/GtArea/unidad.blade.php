@extends('plantillaGtArea')

@section('contenido')

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

@if(session()->has('eliminado'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Unidad Eliminada',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

@if(session()->has('activado'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Unidad Activada',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

@if(session()->has('baja'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Unidad Inactiva',
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
            <h6 class="m-0 font-weight-bold text-primary">Unidades en existencia</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Placas unidad:</th>
                            <th>Tipo:</th>
                            <th>Estado:</th>
                            <th>Año Unidad:</th>
                            <th>Marca:</th>
                            <th>Modelo:</th>
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
                            <th>{{$unidad->modelo}}</th>                        
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection