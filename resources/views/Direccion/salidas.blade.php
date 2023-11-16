@extends('plantillaDir')

@section('contenido')

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">SALIDAS</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">SALIDAS en uso</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID_salida:</th>
                            <th>Requisicion:</th>
                            <th>Cantidad:</th>
                            <th>Refacción:</th>
                            <th>Fecha salida:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salidas as $salida)
                        <tr>
                            <th>{{$salida->id_salida}}</th>
                            <th>
                                <a href="{{ asset($salida->reqPDF) }}" target="_blank">
                                    <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                </a>                                 
                            </th>
                            <th>{{$salida->cantidad}}</th>
                            <th>{{$salida->nombre}} {{$salida->marca}} {{$salida->modelo}}</th>
                            <th>{{$salida->created_at}}</th>
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