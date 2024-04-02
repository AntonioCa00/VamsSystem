@extends('plantillaGtArea')

@section('contenido')

@if(session()->has('pagado'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Se ha registrado el comprobante de pago',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

@if ($errors->any())
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'warning',
        title: 'Debe de ser un archivo tipo PDF',
        showConfirmButton: false,
        timer: 1500
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
                            <th class="text-center">
                                <a href="{{ asset($orden->ordPDF) }}" target="_blank">
                                    <img class="imagen-container" src="{{ asset('img/compra.jpg') }}" alt="Abrir PDF">
                                </a>
                            </th>
                            <th>{{$orden->created_at}}</th>
                            <th>                        
                                @if($orden->estadoComp === null)
                                    <a class="btn btn-success" href="#" data-toggle="modal" data-target="#Finalizar{{$orden->id_orden}}">
                                        Registrar pago
                                    </a>
                                    <!-- Logout Modal-->
                                    <div class="modal fade" id="Finalizar{{$orden->id_orden}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Registrar pago de orden de compra</h5>
                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">X</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{route('FinalizarC',$orden->id_orden)}}" method="POST" enctype="multipart/form-data">
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