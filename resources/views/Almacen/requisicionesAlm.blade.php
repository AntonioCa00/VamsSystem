@extends('plantillaAlm')

@section('contenido')

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">SOLICITUDES</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">REQUISICIONES CON EXISTENCIA EN ALMACEN</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Codigo:</th>
                            <th>Unidad:</th>                                                    
                            <th>Estado:</th>
                            <th>Fecha solicitud:</th>
                            <th>Requisición:</th>
                            <th>Opciones:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requisiciones as $requisicion)
                        <tr>
                            <th>{{$requisicion->id_requisicion}}</th>                                                
                            <th>{{$requisicion->unidad_id}}</th>
                            <th>{{$requisicion->estado}}</th>
                            <th>{{$requisicion->created_at}}</th>
                            <th class="text-center">
                                <a href="{{ asset($requisicion->ordPDF) }}" target="_blank">
                                    <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                </a>
                            </th>
                            <th>
                                <a href="{{route('crearSalidaAlm',$requisicion->id_requisicion)}}" class="btn btn-primary">Dar salida</a>
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