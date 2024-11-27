@extends('plantillaSol')

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
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">SOLICITUDES CREADAS</h6>
            <div class="form-group d-flex align-items-center">
                <a href="{{ route('validaciones') }}" class="btn btn-warning">
                    <img src="{{asset('img/validar.png')}}">Validar requisiciones
                </a>
            </div>
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
                        @foreach ($solicitudes as $solicitudes)
                            <tr>
                                <th>{{ $solicitudes->id_requisicion }}</th>
                                <th>{{ $solicitudes->nombres }}</th>
                                <th>{{ $solicitudes->departamento }}</th>
                                <th>{{ $solicitudes->fecha_creacion }}</th>
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
                                    @if ($solicitudes->estado === "Cotizado")
                                        <a class="btn btn-info" href="{{ route('cotizacionesSolic', $solicitudes->id_requisicion) }}">
                                            Validar
                                        </a>
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
