@extends('plantillaAdm')

@section('Contenido')

@if(session()->has('cotizacion'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Cotizacion realizada',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

@if(session()->has('eliminada'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'solicitud eliminada',
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
                            <th>ID requisicion</th>            
                            <th>Encargado:</th>
                            <th>Fecha solicitud:</th>
                            <th>Estado:</th>
                            <th>Unidad:</th>
                            <th>Requisicion:</th>
                            <th>Opciones:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($solicitudes as $solicitudes)
                        <tr>
                            <th>{{$solicitudes->id_requisicion}}</th>
                            <th>{{$solicitudes->nombres}}</th>
                            <th>{{$solicitudes->fecha_creacion}}</th>
                            <th>{{$solicitudes->estado}}</th>
                            @if (empty($solicitudes->unidad_id))
                                <th>Sin unidad</th>
                            @elseif ($solicitudes->unidad_id == "1")
                                <th>No asignada</th>
                            @else 
                                <th>{{$solicitudes->unidad_id}}</th>
                            @endif
                            <th>
                                <a href="{{ asset($solicitudes->pdf) }}" target="_blank">
                                    <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                </a>
                            </th>
                            <th>
                                @if ($solicitudes->estado === "Aprobado" || $solicitudes->estado === "Cotizado")
                                    <a class="btn btn-primary" href="{{route('createCotiza', $solicitudes->id_requisicion)}}">
                                        Cargar cotizaciones
                                    </a>                                    
                                @elseif ($solicitudes->estado === "Validado" ||$solicitudes->estado ==="Comprado")
                                    <a class="btn btn-primary" href="{{route('ordenCompra',$solicitudes->id_requisicion)}}">
                                        Orden de compra
                                    </a>
                                @else 
                                    <a class="btn btn-primary" href="" onclick="return false;" style="pointer-events: none; background-color: gray; cursor: not-allowed;">
                                        Eliminar
                                    </a>
                                @endif                                
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