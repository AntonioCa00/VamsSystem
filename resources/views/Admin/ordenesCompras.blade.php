@extends('plantillaAdm')

@section('Contenido')

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">ORDENES DE COMPRAS</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Ordenes autorizadas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID_salida:</th>
                            <th>Comprador:</th>
                            <th>Cotizacion:</th>
                            <th>Proveedor:</th>
                            <th>Costo total:</th>
                            <th>Orden de compra</th>
                            <th>Fecha de creacion:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ordenes as $orden)
                        <tr>
                            <th>{{$orden->id_orden}}</th>
                            <th>{{$orden->nombre}}</th>
                            <th class="text-center">
                                <a href="{{ asset($orden->cotPDF) }}" target="_blank">
                                    <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                </a>
                            </th>
                            <th>{{$orden->proveedor}}</th>
                            <th>{{$orden->costo_total}}</th>
                            <th class="text-center">
                                <a href="{{ asset($orden->ordPDF) }}" target="_blank">
                                    <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                </a>
                            </th>
                            <th>{{$orden->created_at}}</th>
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