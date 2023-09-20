@extends('plantillaGen')

@section('contenido')

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">COMPRAS</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">COMPRAS en uso</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID_compra</th>
                            <th>fecha de compra</th>
                            <th>Unidad</th>
                            <th>Administrador</th>
                            <th>Costo</th>
                            <th>Refaccion</th>
                            <th>Factura</th>
                            <th>Opciones:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($compras as $compra)
                        <tr>
                            <th>{{$compra->id_compra}}</th>
                            <th>{{$compra->fecha_compra}}</th>
                            <th>{{$compra->id_unidad}}</th>
                            <th>{{$compra->Nombre}}</th>
                            <th>{{$compra->costo}}</th>
                            <th>{{$compra->refaccion}}</th>
                            <th>{{$compra->factura}}</th>
                            <th>
                                <a href="" class="btn btn-primary">Editar</a>
                                <a href="" class="btn btn-primary">Eliminar</a>
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