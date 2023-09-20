@extends('plantillaGen')

@section('contenido')

@if(session()->has('solicitado'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'solicitud registrada',
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
            <a class="btn btn-primary" href="{{route('createSolicitud')}}">Crear solicitud</a>
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
                        @foreach ($solicitudes as $solicitud)
                        <tr>
                            <th>{{$solicitud->id_solicitud}}</th>
                            <th>{{$solicitud->encargado}}</th>
                            <th>{{$solicitud->created_at}}</th>
                            <th>{{$solicitud->estado}}</th>
                            <th>{{$solicitud->unidad_id}}</th>
                            <th>{{$solicitud->Descripcion}}</th>
                            <th>                                
                                @if($solicitud->estado === "Solicitado")
                                    <a href="" class="btn btn-primary">Eliminar</a>
                                @else 
                                    <a href="#" class="btn btn-primary" onclick="return false;" style="pointer-events: none; background-color: gray; cursor: not-allowed;">Eliminar</a>                                
                                @endif
                                <a href="" class="btn btn-primary">Editar</a>
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