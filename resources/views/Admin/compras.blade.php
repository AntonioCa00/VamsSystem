@extends('plantillaAdm')

@section('Contenido')

@if(session()->has('comprado'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Compra registrada',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">COMPRAS</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a class="btn btn-primary" href="{{route('createCompra')}}">Hacer nueva compra</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Administrador:</th>
                            <th>Fecha de compra:</th>
                            <th>Unidad:</th>
                            <th>Solicitud:</th>
                            <th>Refaccion:</th>
                            <th>Costo:</th>
                            <th>Cantidad:</th>
                            <th>Factura:</th>
                            <th>Opciones:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($compras as $compra)
                        <tr>
                            <th>{{$compra->Administrador}}</th>
                            <th>{{$compra->fechaCompra}}</th>
                            <th>{{$compra->id_unidad}}</th>                            
                            <th>{{$compra->descripcion}}</th>
                            <th>{{$compra->refaccion}}</th>
                            <th>{{$compra->costo}}</th>                            
                            <th>{{$compra->cantidad}}</th>
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