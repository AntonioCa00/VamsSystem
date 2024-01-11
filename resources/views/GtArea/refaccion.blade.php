@extends('plantillaDir')

@section('contenido')

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">REFACCIONES</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Refacciones en inventario</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Clave:</th>
                            <th>Ubicación:</th>
                            <th>Descripcion:</th>
                            <th>Medida:</th>
                            <th>Marca:</th>
                            <th>Stock:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($refacciones as $refaccion)
                        <tr>                    
                            <th>{{$refaccion->clave}}</th>
                            <th>{{$refaccion->ubicacion}}</th>
                            <th>{{$refaccion->descripcion}}</th>
                            <th>{{$refaccion->medida}}</th>
                            <th>{{$refaccion->marca}}</th>
                            <th>{{$refaccion->cantidad}}</th>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection