@extends('plantillaAdm')

@section('Contenido')

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
                            <th>Fecha de salida:</th>
                            <th>Encargado:</th>
                            <th>Estado_solicitud:</th>
                            <th>Unidad:</th>
                            <th>Descripcion</th>
                            <th>Refaccion:</th>
                            <th>Cantidad:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salidas as $salida)
                        <tr>
                            <th>{{$salida->id_salida}}</th>
                            <th>{{$salida->fecha_salida}}</th>
                            <th>{{$salida->encargado}}</th>
                            <th>{{$salida->estado}}</th>
                            <th>{{$salida->id_unidad}}</th>
                            <th>{{$salida->descripcion}}</th>
                            <th>{{$salida->refaccion}}</th>
                            <th>{{$salida->cantidad}}</th>
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