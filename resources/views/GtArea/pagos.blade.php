@extends('plantillaGtArea')

@section('contenido')

@if(session()->has('pagado'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Se ha registrado su pago!',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

@if(session()->has('eliminado'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Orden de pago rechazada!',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">PAGOS FIJOS</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Solicitudes de pagos realizadas</h6>                        
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Folio:</th>
                            <th>Solicitante:</th>
                            <th>Servicio:</th>                                                    
                            <th>Importe:</th>
                            <th>Proveedor:</th>
                            <th>Orden Pago:</th>
                            <th>Comprobante:</th>
                            <th>Opciones:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pagos as $pago)
                            <tr>
                                <th>{{$pago->id_pago}}</th>
                                <th>{{$pago->usuario}}</th>
                                <th>{{$pago->nombre_servicio}}</th>
                                <th>$ {{$pago->costo_total}}</th>
                                <th>{{$pago->nombre}}</th>                                                            
                                <th class="text-center">
                                    <a href="{{ asset($pago->pdf) }}" target="_blank">
                                        <img class="imagen-container" src="{{ asset('img/pago.jpg') }}" alt="Abrir PDF">
                                    </a>
                                </th>
                                <th class="text-center">
                                    @if (empty($pago->comprobante_pago))
                                        @if ($pago->estado === "Pagado")
                                            Sin comprobante
                                        @else 
                                            No se ha registrado un pago
                                        @endif                                        
                                    @else
                                        <a href="{{ asset($pago->comprobante_pago) }}" target="_blank">
                                            <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                        </a>
                                    @endif                                    
                                </th>
                                @if ($pago->estado === "Solicitado")
                                    <th>
                                        <a class="btn btn-success" href="#" data-toggle="modal" data-target="#EditarPago{{$pago->id_pago}}">
                                            Registrar pago
                                        </a>
                                        <!-- Logout Modal-->
                                        <div class="modal fade" id="EditarPago{{$pago->id_pago}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                        aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">{{$pago->nombre_servicio}}</h5>
                                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">X</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('registrarPago',$pago->id_pago)}}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            {!!method_field('PUT')!!}   
                                                            <div class="form-group">
                                                                <label>Favor de cargar su comprobante de pago:</label>
                                                                <input name="comprobante_pago" type="file" class="form-control">
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">Registrar pago</button>
                                                        </form>
                                                    </div>                                                
                                                </div>
                                            </div>
                                        </div>
                                        <a class="btn btn-danger" href="#" data-toggle="modal" data-target="#EliminarPago{{$pago->id_pago}}">
                                            Rechazar
                                        </a>
                                        <!-- Logout Modal-->
                                        <div class="modal fade" id="EliminarPago{{$pago->id_pago}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                        aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">¿Ha tomado una decisión?</h5>
                                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">X</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">Selecciona confirmar para rechazar esta orden de pago</div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">cancelar</button>
                                                        <form action="{{route('deletePago',$pago->id_pago)}}" method="POST">
                                                            @csrf
                                                            {!!method_field('PUT')!!}    
                                                            <button type="submit" class="btn btn-primary">confirmar</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </th>
                                @else
                                    <th>
                                        <a href="#" class="btn btn-primary" onclick="return false;" style="pointer-events: none; background-color: gray; cursor: not-allowed;">Registrar pago</a>                                                                     
                                    </th>                            
                                @endif                                
                            </tr>
                        @endforeach                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection