@extends('plantillaDir')

@section('contenido')

@if(session()->has('validacion'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'solicitud validada',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">SOLICITUDES</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Solicitudes creadas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>Encargado</th>
                            <th>Fecha solicitud</th>
                            <th>Estado</th>
                            <th>Unidad</th>
                            <th>Descripcion</th>
                            <th>Opciones:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($solicitudes as $solicitudes)
                        <tr>
                            <th>{{$solicitudes->id_solicitud}}</th>
                            <th>{{$solicitudes->encargado}}</th>
                            <th>{{$solicitudes->fecha_solicitud}}</th>
                            <th>{{$solicitudes->estado}}</th>
                            <th>{{$solicitudes->id_unidad}}</th>
                            <th>{{$solicitudes->descripcion}}</th>
                            <th>
                                <a class="btn btn-primary" href="{{route('verCotiza', $solicitudes->id_solicitud)}}">
                                    Cotizaciones
                                </a>
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