@extends('plantillaGtArea')

@section('contenido')

@if(session()->has('solicitado'))
    <script type="text/javascript">
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Solicitud Registrada!',
        showConfirmButton: false,
        timer: 1000
        })
    </script>
@endif

@if(session()->has('editado'))
    <script type="text/javascript">
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Solicitud Editada!',
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
        title: 'Solicitud Eliminada!',
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
            <a class="btn btn-primary" href="{{route('createSolicitudDir')}}">Crear solicitud</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Codigo:</th>
                            @if(session('departamento') === "Mantenimiento")
                                <th>Unidad:</th>
                            @endif
                            <th>Estado:</th>
                            <th>Fecha solicitud:</th>
                            <th>Requisición:</th>
                            <th>Comentarios:</th>
                            <th>Opciones:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($solicitudes as $solicitud)
                        <tr>
                            <th>{{$solicitud->id_requisicion}}</th>
                            @if(session('departamento') === "Mantenimiento")
                                <th>{{$solicitud->unidad_id}}</th>

                            @endif
                            @if ($solicitud->estado === "Incompleta")
                                <th class="text-danger">{{$solicitud->estado}}</th>
                            @else
                                <th>{{$solicitud->estado}}</th>
                            @endif
                            <th>{{$solicitud->fecha_creacion}}</th>
                            <th>
                                <a href="{{ asset($solicitud->pdf) }}" target="_blank">
                                    <img class="imagen-container" src="{{ asset('img/req.jpg') }}" alt="Abrir PDF">
                                </a>
                            </th>
                            <th class="text-center">
                                @if (empty($solicitud->detalles))
                                <a href="#" data-toggle="modal" data-target="#Comentarios{{$solicitud   ->id_requisicion}}">
                                    <img src="{{ asset('img/comente.png') }}" alt="Abrir PDF">
                                </a>
                                    <div class="modal fade text-left" id="Comentarios{{$solicitud->id_requisicion}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                    aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Seguimiento de comentarios</h5>
                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">X</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">No existen comentarios de seguimiento en este momento</div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">cancelar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                <a href="#" data-toggle="modal" data-target="#Comentarios{{$solicitud->id_requisicion}}">
                                    <img src="{{ asset('img/comentarios.png') }}" alt="Abrir PDF">
                                </a>
                                    <div class="modal fade" id="Comentarios{{$solicitud->id_requisicion}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                                @if($solicitud->estado === "Solicitado" || $solicitud->estado === "Rechazado" || $solicitud->estado === "Incompleta")
                                <a href="{{route('editReqDir',$solicitud->id_requisicion)}}" class="btn btn-success">Editar requisición</a>
                                <a class="btn btn-danger" href="#" data-toggle="modal" data-target="#eliminarReq{{$solicitud->id_requisicion}}">
                                    Eliminar
                                </a>
                                <!-- Logout Modal-->
                                <div class="modal fade" id="eliminarReq{{$solicitud->id_requisicion}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">¿Ha tomado una decisión?</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">X</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">Selecciona confirmar para eliminar esta requisición</div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">cancelar</button>
                                                <form action="{{route('deleteSolicitud',$solicitud->id_requisicion)}}" method="POST">
                                                    @csrf
                                                    {!!method_field('DELETE')!!}
                                                    <button type="submit" class="btn btn-primary">confirmar</button>
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
