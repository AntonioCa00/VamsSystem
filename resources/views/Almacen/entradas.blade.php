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
                            <th>Cantidad:</th>
                            <th>Compra:</th>
                            <th>Fecha:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($entradas as $entrada)
                        <tr>
                            <th>{{$entrada->id_entrada}}</th>
                            <th>{{$entrada->cantidad}}</th>
                            <th>{{$entrada->compra_id}}</th>
                            <th>{{$entrada->created_at}}</th>
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