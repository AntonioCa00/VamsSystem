@extends('plantillaGtArea')

@section('contenido')

@if(session()->has('validado'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Requisicion validada!',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

@if(session()->has('validacion'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Cotizacion validada!',
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
        title: 'solicitud eliminada',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">SOLICITUDES</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Solicitudes creadas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID requisicion</th>            
                            <th>Encargado:</th>
                            <th>Fecha solicitud:</th>
                            <th>Estado:</th>
                            <th>Unidad:</th>
                            <th>Requisicion:</th>
                            <th>Comentarios:</th>
                            <th>Opciones:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($solicitudes as $solicitudes)
                        <tr>
                            <th>{{$solicitudes->id_requisicion}}</th>
                            <th>{{$solicitudes->nombres}}</th>
                            <th>{{$solicitudes->fecha_creacion}}</th>
                            <th>{{$solicitudes->estado}}</th>
                            <th>{{$solicitudes->unidad_id}}</th>
                            <th>
                                <a href="{{ asset($solicitudes->pdf) }}" target="_blank">
                                    <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                </a>
                            </th>
                            <th>
                                <a href="#" data-toggle="modal" data-target="#Comentarios">
                                    <img src="{{ asset('img/comentarios.png') }}" alt="Abrir PDF">
                                </a>
                                
                                @if (empty($solicitud->detalles))
                                    <div class="modal fade" id="Comentarios" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                    aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Seguimiento de comentarios</h5>
                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">X</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">No existen comentarios de seguminiento en este momento</div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">cancelar</button>                                                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="modal fade" id="Comentarios" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                    aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Seguimiento de comentarios</h5>
                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">X</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">Comentario emitido por: <strong>{{$solicitud->rol}}</strong></div>
                                                <div class="modal-body">Comentario: <strong>{{$solicitud->detalles}}</strong></div>
                                                <div class="modal-body">Fecha del comentario: <strong>{{$solicitud->fechaCom}}</strong></div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">cancelar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </th>
                            <th>
                                <!-- Ver Cotizaciones -->
                                @if ($solicitudes->estado === "Cotizado")
                                    <a class="btn btn-primary" href="{{route('verCotiza', $solicitudes->id_requisicion)}}">
                                        Revisar cotizaciones
                                    </a>
                                <!-- Validar o Rechazar -->
                                @elseif ($solicitudes->estado === "Solicitado") 
                                <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#Aprobar{{$solicitudes->id_requisicion}}">
                                    Aprobar
                                </a>
                                <!-- Validate Modal-->
                                <div class="modal fade" id="Aprobar{{$solicitudes->id_requisicion}}"" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">¿Ha tomado una decisión?</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">X</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">Selecciona confirmar para aprobar esta requisición</div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">cancelar</button>
                                                <form action="{{route('validar',$solicitudes->id_requisicion)}}", method="POST">                                                
                                                    @csrf
                                                    {!!method_field('PUT')!!}    
                                                    <button type="submit" class="btn btn-primary">confirmar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#eliminarReq{{$solicitudes->id_requisicion}}">
                                    Rechazar
                                </a>
                                <!-- Logout Modal-->
                                <div class="modal fade" id="eliminarReq{{$solicitudes->id_requisicion}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">¿Ha tomado una decisión?</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">X</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">Selecciona confirmar para rechazar esta solicitud
                                            <form action="{{route('deleteReq',$solicitudes->id_requisicion)}}" method="POST">
                                                @csrf
                                                    {!!method_field('PUT')!!}  
                                                    <div class="form-group">
                                                        <label for="exampleFormControlInput1">Razón del rechazo:</label>
                                                        <input name="comentario" type="text" class="form-control" placeholder="Razón por la cual rechaza la solicitud" required>
                                                    </div>  
                                                </div>
                                                    <div class="modal-footer">                                                                                                        
                                                    <button type="submit" class="btn btn-primary">Confirmar</button>
                                                </form>
                                            </div>                                            
                                        </div>
                                    </div>
                                </div>
                                @else
                                <a href="#" class="btn btn-primary" onclick="return false;" style="pointer-events: none; background-color: gray; cursor: not-allowed;">Revisar Cotizaciones</a>                                
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
<!-- /.container-fluid -->

@endsection