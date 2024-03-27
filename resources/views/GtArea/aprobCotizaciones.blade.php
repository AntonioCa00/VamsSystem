@extends('plantillaGtArea')

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
            <h6 class="m-0 font-weight-bold text-primary">Cotizacion pre validada</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Encargado que cotizó:</th>
                            <th>Requisición:</th>                            
                            <th>Cotización:</th>
                            <th>Opciones:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>{{$validada->usuario}}</th>
                            <th class="text-center">
                                <a href="{{ asset($validada->reqPDF) }}" target="_blank">
                                    <img class="imagen-container" src="{{ asset('img/req.jpg') }}" alt="Abrir PDF">
                                </a>    
                            </th>                            
                            <th class="text-center">
                                <a href="{{ asset($validada->cotPDF) }}" target="_blank">
                                    <img class="imagen-container" src="{{ asset('img/cot.jpg') }}" alt="Abrir PDF">
                                </a>
                            </th>
                            <th>
                                <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#validarCotiza{{$validada->id_cotizacion}}">
                                    Validar
                                </a>
                                <!-- Validate Modal-->
                                <div class="modal fade" id="validarCotiza{{$validada->id_cotizacion}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                                                <form action="{{route('selectCotiza',['id' => $validada->id_cotizacion, 'sid' => $validada->requisicion_id])}}" method="POST">                                                
                                                    @csrf
                                                    {!!method_field('PUT')!!}    
                                                    <button type="submit" class="btn btn-primary">confirmar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#EliminarCotiza{{$validada->id_cotizacion}}">
                                    Rechazar cotización
                                </a>
                                <!-- Validate Modal-->
                                <div class="modal fade" id="EliminarCotiza{{$validada->id_cotizacion}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">¿Ha tomado una decisión?</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">X</span>
                                                </button>
                                            </div>
                                        <div class="modal-body">Selecciona confirmar para eliminar esta cotización</div>                                                                                            
                                            <form action="{{route('rechazaFin', ['id' => $validada->id_cotizacion, 'sid' => $validada->requisicion_id])}}" method="POST">                                                
                                                @csrf
                                                {!!method_field('PUT')!!} 
                                                <div class="form-group">
                                                    <label for="exampleFormControlInput1">Razón del rechazo:</label>
                                                    <input name="comentario" type="text" class="form-control" placeholder="Razón por la cual rechaza la solicitud" required>
                                                </div>                                               
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">confirmar</button>
                                            </form>                                            
                                        </div>
                                    </div>
                                </div>
                            </th>                
                        </tr>

                        <tr>
                            <th> <h6 class="m-0 font-weight-bold text-primary">Otras cotizaciones</h6></th>
                        </tr>
                        @foreach ($cotizaciones as $cotizacion)
                        <tr>
                            <th>{{$cotizacion->usuario}}</th>
                            <th class="text-center">
                                <a href="{{ asset($cotizacion->reqPDF) }}" target="_blank">
                                    <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                </a>    
                            </th>                            
                            <th class="text-center">
                                <a href="{{ asset($cotizacion->cotPDF) }}" target="_blank">
                                    <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                </a>
                            </th>
                            <th>
                                <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#EliminarCotizar{{$cotizacion->id_cotizacion}}">
                                    Eliminar
                                </a>
                                <!-- Validate Modal-->
                                <div class="modal fade" id="EliminarCotizar{{$cotizacion->id_cotizacion}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">¿Ha tomado una decisión?</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">X</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">Selecciona confirmar para eliminar esta cotización</div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">cancelar</button>
                                                <form action="{{route('deleteCotizacion',$cotizacion->id_cotizacion)}}" method="POST">                                                
                                                    @csrf
                                                    {!!method_field('DELETE')!!}                                                                                                       
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
@endsection