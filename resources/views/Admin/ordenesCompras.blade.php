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
                            <th>Cotizacion:</th>
                            <th>Proveedor:</th>
                            <th>Costo total:</th>
                            <th>Orden de compra</th>
                            <th>Fecha de creacion:</th>
                            <th>Opciones:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ordenes as $orden)
                        <tr>
                            <th>{{$orden->id_orden}}</th>
                            <th>{{$orden->nombre}}</th>
                            <th class="text-center">
                                <a href="{{ asset($orden->cotPDF) }}" target="_blank">
                                    <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                </a>
                            </th>
                            <th>{{$orden->proveedor}}</th>
                            <th>${{$orden->costo_total}}</th>
                            <th class="text-center">
                                <a href="{{ asset($orden->ordPDF) }}" target="_blank">
                                    <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                </a>
                            </th>
                            <th>{{$orden->created_at}}</th>
                            <th>

                                @if($orden->estado === "Comprado")
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
                                <a href="#" class="btn btn-primary" onclick="return false;" style="pointer-events: none; background-color: gray; cursor: not-allowed;">Eliminar</a>                                
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