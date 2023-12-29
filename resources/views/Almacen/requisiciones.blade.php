@extends('plantillaAlm')

@section('contenido')

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">SOLICITUDES</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">REQUISICIONES REGISTRADAS</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Codigo:</th>
                            <th>Unidad:</th>  
                            <th>Encargado:</th>                                                  
                            <th>Descripcion:</th>
                            <th>Estado:</th>
                            <th>Total:</th>
                            <th>Orden Compra:</th>
                            <th>Fecha creacion:</th>
                            <th>Opciones:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ordenes as $orden)
                        <tr>
                            <th>{{$orden->id_requisicion}}</th>
                            <th>{{$orden->unidad_id}}</th>
                            <th>{{$orden->nombre}}</th>
                            <th class="text-center">
                                <a href="{{ asset($orden->reqPDF) }}" target="_blank">
                                    <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                </a>
                            </th>
                            <th>{{$orden->estado}}</th>
                            <th>${{$orden->costo_total}}</th>
                            <th class="text-center">
                                <a href="{{ asset($orden->ordPDF) }}" target="_blank">
                                    <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                </a>
                            </th>
                            <th>{{$orden->created_at}}</th>
                            <th>
                                <a href="{{route('createEntrada',$orden->id_orden)}}" class="btn btn-primary">Registrar entrada</a>
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