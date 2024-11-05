@extends('plantillaSol')

@section('contenido')

@if(session()->has('eliminada'))
    <script type="text/javascript">
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'solicitud eliminada',
        showConfirmButton: false,
        timer: 1000
        })
    </script>
@endif

@if(session()->has('finalizada'))
    <script type="text/javascript">
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Se ha finalizado el proceso de la requisicion',
        showConfirmButton: false,
        timer: 1000
        })
    </script>
@endif

@if(session()->has('orden'))
    <script type="text/javascript">
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Orden de compra creada',
        showConfirmButton: false,
        timer: 1000
        })
    </script>
@endif

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
                            <th>ID orden:</th>
                            <th>Comprador:</th>
                            <th>Requisicion:</th>
                            <th>Proveedor:</th>
                            <th>Costo total:</th>
                            <th>Estado:</th>
                            <th>Orden de compra</th>
                            <th>Fecha de creacion:</th>
                            <th>Opciones:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ordenes as $orden)
                        <tr>
                            <th>{{$orden->id_orden}}</th>
                            <th>{{$orden->nombres}}</th>
                            <th>{{$orden->id_requisicion}}</th>
                            <th>{{$orden->proveedor}}</th>
                            <th>${{$orden->costo_total}}</th>
                            @if ($orden->estado === "Pagado")
                                <th class="text-success">{{$orden->estado}}</th>
                            @else
                                <th class="text-warning">Pendiente</th>
                            @endif
                            <th class="text-center">
                                <a href="{{ asset($orden->ordPDF) }}" target="_blank">
                                    <img class="imagen-container" src="{{ asset('img/compra.jpg') }}" alt="Abrir PDF">
                                </a>
                            </th>
                            <th>{{$orden->created_at}}</th>
                            <th>
                                @if(empty($orden->comprobante_pago))
                                    Sin comprobante
                                @else
                                    <a href="{{ asset($orden->comprobante_pago)}}" target="_blank">
                                        Comprobante pago
                                    </a>
                                @endif
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
