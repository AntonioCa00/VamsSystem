@extends('plantillaAdm')

@section('Contenido')
    <!-- Mensaje de exito al eliminar una orden de compra -->
    @if (session()->has('eliminada'))
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

    <!-- Mensaje de exito al finalizar una requisicion  -->
    @if (session()->has('finalizada'))
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

    <!-- Mensaje de exito al crear una orden de compra -->
    @if (session()->has('orden'))
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
                                <th class="col-1">Folio orden:</th>
                                <th class="col-1">Folio Req:</th>
                                <th class="col-3">Proveedor:</th>
                                <th class="col-1">Costo total:</th>
                                <th class="col-1">Estado:</th>
                                <th class="col-1">Orden Compra</th>
                                <th class="col-1">Fecha de creacion:</th>
                                <th>Opciones:</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Iterar sobre las ordenes de compra -->
                            @foreach ($ordenes as $orden)
                                <tr>
                                    <th>{{ $orden->id_orden }}</th>
                                    <th class="text-center">{{ $orden->id_requisicion }}</th>
                                    <th>{{ $orden->proveedor }}</th>
                                    <th>${{ $orden->costo_total }}</th>
                                    <!-- Mostrar si la orden de compra esta pagada o pendiente -->
                                    @if (empty($orden->comprobante_pago))
                                        <th class="text-warning">Pendiente</th>
                                    @else
                                        <th class="text-success">Pagado</th>
                                    @endif
                                    <!-- Enlace para abrir el PDF de la orden de compra -->
                                    <th class="text-center">
                                        <a href="{{ asset($orden->ordPDF) }}" target="_blank">
                                            <img class="imagen-container" src="{{ asset('img/compra.jpg') }}"
                                                alt="Abrir PDF">
                                        </a>
                                    </th>
                                    <th>{{ $orden->fecha_creacion }}</th>
                                    <th>
                                        <!-- Enlace para abrir el PDF de la caratula de la orden de compra -->
                                        @if ($orden->tipo_pago == 1)
                                            @if ($orden->estadoComp === null)
                                                <a class="btn btn-success btnRegistrarPago"
                                                    href="#"
                                                    data-toggle="modal"
                                                    data-target="#modalRegistrarPago"
                                                    data-url="{{ route('FinalizarCompra', $orden->id_orden) }}">
                                                    Registrar pago
                                                </a>
                                            
                                                <a class="btn btn-primary btnEliminarOrden"
                                                    href="#"
                                                    data-toggle="modal"
                                                    data-target="#modalEliminarOrden"
                                                    data-url="{{ route('deleteOrd', [$orden->id_orden, $orden->id_requisicion]) }}">
                                                    Eliminar
                                                </a>
                                            @else 
                                                <a class="btn btn-info btnEditarComprobante"
                                                    href="#"
                                                    data-toggle="modal"
                                                    data-target="#modalEditarComprobante"

                                                    data-url="{{ route('editComprobantePagoC', $orden->id_orden) }}"
                                                    data-comprobante="{{ $orden->comprobante_pago ? asset($orden->comprobante_pago) : '' }}">

                                                    Editar Comprobante
                                                </a>
                                            @endif
                                        @else
                                            <!-- Si la orden de compra ya esta pagada, mostrar el enlace para ver el comprobante de pago -->
                                            @if (empty($orden->comprobante_pago))
                                                <!-- Si no hay comprobante de pago, mostrar mensaje -->
                                                Sin comprobante
                                            @else
                                                <!-- Si hay comprobante de pago, mostrar enlace para descargar -->
                                                <a href="{{ asset($orden->comprobante_pago) }}" target="_blank">
                                                    Comprobante pago
                                                </a>
                                            @endif   
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

    @include('Compras.modals.modalEditarComprobante')
    @include('Compras.modals.modalRegistrarPago')
    @include('Compras.modals.modalEliminarOrden')

    <script src="{{ asset('js/compras.js') }}"></script>

@endsection
