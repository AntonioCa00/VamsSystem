@extends('plantillaAdm')

@section('Contenido')

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
                            <th>{{$orden->fecha_creacion}}</th>
                            <th>
                                @if($orden->estadoComp === null)
                                    {{-- <a class="btn btn-success" href="#" data-toggle="modal" data-target="#Finalizar{{$orden->id_orden}}">
                                        Registrar pago
                                    </a>
                                    <!-- Logout Modal-->
                                    <div class="modal fade" id="Finalizar{{$orden->id_orden}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">¿Ha tomado una decisión?</h5>
                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">X</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">Selecciona confirmar para finalizar proceso</div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">cancelar</button>
                                                    <form action="{{route('FinalizarC',$orden->id_orden)}}" method="POST">
                                                        @csrf
                                                        {!!method_field('PUT')!!}
                                                        <button type="submit" class="btn btn-primary">Confirmar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
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
                                @if(empty($orden->comprobante_pago))
                                    Sin comprobante
                                @else
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
