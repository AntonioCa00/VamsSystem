@extends('plantillaGtArea')

@section('contenido')

@if(session()->has('rechazaC'))
    <script type="text/javascript">
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'cotizaciones rechazadas!',
        showConfirmButton: false,
        timer: 1000
        })
    </script>
@endif

@if(session()->has('aprobado'))
    <script type="text/javascript">
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Requisicion aprobada!',
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
                            <th>Requisicion N°:</th>
                            <th>Encargado:</th>
                            <th>Departamento:</th>
                            <th>Fecha solicitud:</th>
                            <th>Estado:</th>
                            <th>Requisicion:</th>
                            <th>Comentarios:</th>
                            <th>Opciones:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Convierte la cadena de departamentos de la sesión en un array
                            $departamentosSesion = explode(' / ', session('departamento'));
                        @endphp

                        @if (in_array("Finanzas", $departamentosSesion) && session('rol') === "Gerente Area")
                            @foreach ($solicitudes as $solicitudes)
                                <tr>
                                    <th>{{ $solicitudes->id_requisicion }}</th>
                                    <th>{{ $solicitudes->nombres }}</th>
                                    <th>{{ $solicitudes->departamento }}</th>
                                    <th>{{ $solicitudes->created_at }}</th>
                                    <th>{{ $solicitudes->estado }}</th>
                                    <th class="text-center">
                                        <a href="{{ asset($solicitudes->pdf) }}" target="_blank">
                                            <img class="imagen-container" src="{{ asset('img/req.jpg') }}" alt="Abrir PDF">
                                        </a>
                                    </th>
                                    <th class="text-center">
                                        @if (empty($solicitudes->detalles))
                                            <a href="#" data-toggle="modal" data-target="#Comentarios{{ $solicitudes->id_requisicion }}">
                                                <img src="{{ asset('img/comente.png') }}" alt="Abrir PDF">
                                            </a>
                                            <div class="modal fade text-left" id="Comentarios{{ $solicitudes->id_requisicion }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <a href="#" data-toggle="modal" data-target="#Comentarios{{ $solicitudes->id_requisicion }}">
                                                <img src="{{ asset('img/comentarios.png') }}" alt="Abrir PDF">
                                            </a>
                                            <div class="modal fade" id="Comentarios{{ $solicitudes->id_requisicion }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Seguimiento de comentarios</h5>
                                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">X</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">Comentario emitido por: <strong>{{ $solicitudes->rol }}</strong></div>
                                                        <div class="modal-body">Comentario: <strong>{{ $solicitudes->detalles }}</strong></div>
                                                        <div class="modal-body">Fecha del comentario: <strong>{{ $solicitudes->fechaCom }}</strong></div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </th>
                                    <th>
                                        <!-- Ver Cotizaciones -->
                                        @if ($solicitudes->estado === "Cotizado" && in_array($solicitudes->departamento, $departamentosSesion))
                                        <a href="#" class="btn btn-info" onclick="return false;" style="pointer-events: none; background-color: gray; cursor: not-allowed;">Revisar Cotizaciones</a>
                                            <!-- Acción específica si es necesario -->
                                        @elseif ($solicitudes->estado === "Pre Validado" && session('rol') === "Gerente Area" && in_array("Finanzas", $departamentosSesion))
                                            <a class="btn btn-info" href="{{ route('aprobCotiza', $solicitudes->id_requisicion) }}">
                                                Confirmar validación
                                            </a>
                                        <!-- Validar o Rechazar -->
                                        @elseif ($solicitudes->estado === "Solicitado" && in_array($solicitudes->departamento, $departamentosSesion))
                                            <a class="btn btn-success" href="{{ route('aprobarArt', $solicitudes->id_requisicion) }}">
                                                Aprobar
                                            </a>
                                            <a class="btn btn-warning" href="#" data-toggle="modal" data-target="#eliminarReq{{ $solicitudes->id_requisicion }}">
                                                Rechazar
                                            </a>
                                            <!-- Rechazar Modal-->
                                            <div class="modal fade" id="eliminarReq{{ $solicitudes->id_requisicion }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">¿Ha tomado una decisión?</h5>
                                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">X</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">Selecciona confirmar para rechazar esta solicitud
                                                            <form action="{{ route('deleteReq', $solicitudes->id_requisicion) }}" method="POST">
                                                                @csrf
                                                                {!! method_field('PUT') !!}
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
                                            <a href="#" class="btn btn-primary" onclick="return false;" style="pointer-events: none; background-color: gray; cursor: not-allowed;">Eliminar</a>
                                        @endif
                                    </th>
                                </tr>
                            @endforeach
                        @else
                            @foreach ($solicitudes as $solicitudes)
                                @if (in_array($solicitudes->departamento, $departamentosSesion))
                                    <tr>
                                        <th>{{ $solicitudes->id_requisicion }}</th>
                                        <th>{{ $solicitudes->nombres }}</th>
                                        <th>{{ $solicitudes->departamento }}</th>
                                        <th>{{ $solicitudes->created_at }}</th>
                                        <th>{{ $solicitudes->estado }}</th>
                                        <th class="text-center">
                                            <a href="{{ asset($solicitudes->pdf) }}" target="_blank">
                                                <img class="imagen-container" src="{{ asset('img/req.jpg') }}" alt="Abrir PDF">
                                            </a>
                                        </th>
                                        <th>
                                            <a href="#" data-toggle="modal" data-target="#Comentarios{{ $solicitudes->id_requisicion }}">
                                                <img src="{{ asset('img/comentarios.png') }}" alt="Abrir PDF">
                                            </a>

                                            @if (empty($solicitudes->detalles))
                                                <div class="modal fade" id="Comentarios{{ $solicitudes->id_requisicion }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="modal fade" id="Comentarios{{ $solicitudes->id_requisicion }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Seguimiento de comentarios</h5>
                                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">X</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">Comentario emitido por: <strong>{{ $solicitudes->rol }}</strong></div>
                                                            <div class="modal-body">Comentario: <strong>{{ $solicitudes->detalles }}</strong></div>
                                                            <div class="modal-body">Fecha del comentario: <strong>{{ $solicitudes->fechaCom }}</strong></div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </th>
                                        <th>
                                            <!-- Ver Cotizaciones -->
                                            @if ($solicitudes->estado === "Cotizado" && in_array($solicitudes->departamento, $departamentosSesion))
                                            <a class="btn btn-info" href="{{route('verCotiza', $solicitudes->id_requisicion)}}">
                                                Revisar Cotizaciones
                                            </a>
                                                <!-- Acción específica si es necesario -->
                                            @elseif ($solicitudes->estado === "Pre Validado" && session('rol') === "Gerente Area" && in_array("Finanzas", $departamentosSesion))
                                                <a class="btn btn-info" href="{{ route('aprobCotiza', $solicitudes->id_requisicion) }}">
                                                    Confirmar validación
                                                </a>
                                            <!-- Validar o Rechazar -->
                                            @elseif ($solicitudes->estado === "Solicitado" && in_array($solicitudes->departamento, $departamentosSesion))
                                                <a class="btn btn-success" href="{{ route('aprobarArt', $solicitudes->id_requisicion) }}">
                                                    Aprobar
                                                </a>
                                                <a class="btn btn-warning" href="#" data-toggle="modal" data-target="#eliminarReq{{ $solicitudes->id_requisicion }}">
                                                    Rechazar
                                                </a>
                                                <!-- Rechazar Modal-->
                                                <div class="modal fade" id="eliminarReq{{ $solicitudes->id_requisicion }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">¿Ha tomado una decisión?</h5>
                                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">X</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">Selecciona confirmar para rechazar esta solicitud
                                                                <form action="{{ route('deleteReq', $solicitudes->id_requisicion) }}" method="POST">
                                                                    @csrf
                                                                    {!! method_field('PUT') !!}
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
                                                <a href="#" class="btn btn-primary" onclick="return false;" style="pointer-events: none; background-color: gray; cursor: not-allowed;">Eliminar</a>
                                            @endif
                                        </th>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
