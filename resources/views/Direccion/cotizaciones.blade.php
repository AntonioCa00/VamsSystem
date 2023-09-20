@extends('plantillaDir')

@section('contenido')

@if(session()->has('error'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'danger',
        title: 'No se cargó pdf',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">COTIZACIONES</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Cotizaciones creadas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Solicitud</th>
                            <th>Encargado</th>
                            <th>Proveedor</th>
                            <th>Costo Total</th>
                            <th>Archivo</th>
                            <th>Opciones:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cotizaciones as $cotizacion)
                        <tr>
                            <th>{{$cotizacion->solicitud_id}}</th>
                            <th>{{$cotizacion->administrador_id}}</th>
                            <th>{{$cotizacion->Proveedor}}</th>
                            <th>{{$cotizacion->Costo_total}}</th>
                            <th class="text-center">
                                <a href="{{ asset($cotizacion->archivo_pdf) }}" target="_blank">
                                    <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                </a>
                            </th>
                            <th>
                                <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#validarCotiza">
                                    Validar
                                </a>
                                <!-- Logout Modal-->
                                <div class="modal fade" id="validarCotiza" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">¿Ha tomado una decisión?</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">X</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">Selecciona confirmar para validar unicamente esta cotización</div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">cancelar</button>
                                                <form action="{{route('selectCotiza',['id' => $cotizacion->id_cotizacion, 'sid' => $cotizacion->solicitud_id])}}" method="POST">
                                                    @csrf
                                                    {!!method_field('PUT')!!}    
                                                    <button type="submit" class="btn btn-primary">confirmar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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