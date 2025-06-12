@extends('plantillaAdm')

@section('Contenido')

<!-- Mensaje de exito al eliminar una orden de compra -->
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

<!-- Mensaje de exito al finalizar una requisicion  -->
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

<!-- Mensaje de exito al crear una orden de compra -->
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
                            <th>Folio Req:</th>
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
                        <!-- Iterar sobre las ordenes de compra -->
                        @foreach ($ordenes as $orden)
                        <tr>
                            <th>{{$orden->id_orden}}</th>
                            <th class="text-center">{{$orden->id_requisicion}}</th>
                            <th class="text-center"> 
                                <!-- Enlace para abrir el PDF de la requisicion -->
                                <a href="{{ asset($orden->reqPDF) }}" target="_blank">
                                    <img class="imagen-container" src="{{ asset('img/req.jpg') }}" alt="Abrir PDF">
                                </a>
                            </th>                        
                            <th>{{$orden->proveedor}}</th>
                            <th>${{$orden->costo_total}}</th>
                            <!-- Mostrar si la orden de compra esta pagada o pendiente -->
                            @if (empty($orden->comprobante_pago))
                                <th class="text-warning">Pendiente</th>                                
                            @else
                                <th class="text-success">Pagado</th>
                            @endif
                            <!-- Enlace para abrir el PDF de la orden de compra -->
                            <th class="text-center">
                                <a href="{{ asset($orden->ordPDF) }}" target="_blank">
                                    <img class="imagen-container" src="{{ asset('img/compra.jpg') }}" alt="Abrir PDF">
                                </a>
                            </th>
                            <th>{{$orden->fecha_creacion}}</th>
                            <th>
                                <!-- Validar si la orden de compra esta pendiente -->
                                @if($orden->estadoComp === null)     
                                    <!--Si la orden de compra esta pendiente, mostrar botones para pagar o eliminar -->                       
                                    <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#eliminarOrd{{$orden->id_orden}}">
                                        Eliminar
                                    </a>
                                    <!-- Logout Modal-->
                                    <div class="modal fade" id="eliminarOrd{{$orden->id_orden}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                    aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">¿Ha tomado una decisión?</h5>
                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">X</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">Selecciona confirmar para eliminar esta orden de compra</div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">cancelar</button>
                                                    <form action="{{route('deleteOrd',['id' => $orden->id_orden, 'sid' => $orden->id_requisicion])}}" method="POST">
                                                        @csrf
                                                        {!!method_field('PUT')!!}
                                                        <button type="submit" class="btn btn-primary">Confirmar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                <!-- Si la orden de compra ya esta pagada, mostrar el enlace para ver el comprobante de pago -->
                                    @if(empty($orden->comprobante_pago))
                                    <!-- Si no hay comprobante de pago, mostrar mensaje -->
                                        Sin comprobante
                                    @else
                                    <!-- Si hay comprobante de pago, mostrar enlace para descargar -->
                                        <a href="{{ asset($orden->comprobante_pago)}}" target="_blank">
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
@endsection
