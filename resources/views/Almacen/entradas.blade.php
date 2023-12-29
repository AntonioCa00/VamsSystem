@extends('plantillaAlm')

@section('contenido')

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">ENTRADAS</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">ENTRADAS REGISTRADAS</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID_entrada:</th>
                            <th>Orden de compra:</th>
                            <th>Fecha:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($entradas as $entrada)
                        <tr>
                            <th>{{$entrada->id_entrada}}</th>
                            
                            <th><a href="{{ asset($entrada->factura) }}" target="_blank">
                                <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                            </a></th>
                            <th>{{$entrada->created_at}}</th>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection