@extends('plantillaAdm')

@section('Contenido')
    @if (session()->has('cotizacion'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Cotizacion realizada',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    @if (session()->has('finalizada'))
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

    @if (session()->has('eliminada'))
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

    @if (session()->has('corte'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Solicitudes procesadas',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    <div class="container-fluid">

        <!-- Encabezado de la tabla -->
        <h1 class="h3 mb-2 text-gray-800">SOLICITUDES</h1>

        <!-- Tabla de los datos -->
        <div class="card shadow mb-1">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">HISTORIAL DE SOLICITUDES</h6>
                <div class="form-group d-flex align-items-center">
                    <a href="{{ route('corte') }}" class="btn btn-warning">
                        <img src="{{ asset('img/corte.png') }}">Corte Semanal
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Folio:</th>
                                <th>Encargado:</th>
                                <th>Fecha solicitud:</th>
                                <th>Estado:</th>
                                <th>Comentarios:</th>
                                <th>Requisicion:</th>
                                <th>Opciones:</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($solicitudes as $solicitud)
                                @if (!empty($solicitud->urgencia))
                                    <tr class="text-danger">
                                    @else
                                    <tr>
                                @endif
                                <th>{{ $solicitud->id_requisicion }}</th>
                                <th>{{ $solicitud->nombres }}</th>
                                <th>{{ $solicitud->fecha_creacion }}</th>
                                <th>{{ $solicitud->estado }}</th>
                                <th class="text-center">
                                    @if (empty($solicitud->detalles))
                                        <a href="#" data-toggle="modal"
                                            data-target="#Comentarios{{ $solicitud->id_requisicion }}">
                                            <img src="{{ asset('img/comente.png') }}" alt="Abrir PDF">
                                        </a>
                                        <div class="modal fade text-left" id="Comentarios{{ $solicitud->id_requisicion }}"
                                            tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                            aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Seguimiento de
                                                            comentarios</h5>
                                                        <button class="close" type="button" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">X</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">No existen comentarios de seguimiento en este
                                                        momento</div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" type="button"
                                                            data-dismiss="modal">cancelar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <a href="#" data-toggle="modal"
                                            data-target="#Comentarios{{ $solicitud->id_requisicion }}">
                                            <img src="{{ asset('img/comentarios.png') }}" alt="Abrir PDF">
                                        </a>
                                        <div class="modal fade" id="Comentarios{{ $solicitud->id_requisicion }}"
                                            tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                            aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Seguimiento de
                                                            comentarios</h5>
                                                        <button class="close" type="button" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">X</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">Comentario emitido por:
                                                        <strong>{{ $solicitud->rol }}</strong>
                                                    </div>
                                                    <div class="modal-body">Comentario:
                                                        <strong>{{ $solicitud->detalles }}</strong>
                                                    </div>
                                                    <div class="modal-body">Fecha del comentario:
                                                        <strong>{{ $solicitud->fechaCom }}</strong>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" type="button"
                                                            data-dismiss="modal">cancelar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </th>
                                <th class="text-center">
                                    <a href="{{ asset($solicitud->pdf) }}" target="_blank">
                                        <img class="imagen-container" src="{{ asset('img/req.jpg') }}" alt="Abrir PDF">
                                    </a>
                                </th>
                                <th>
                                    @if ($solicitud->estado === 'Aprobado' || $solicitud->estado === 'Cotizado')
                                        <a class="btn btn-primary"
                                            href="{{ route('createCotiza', $solicitud->id_requisicion) }}">
                                            Subir cotizaciones
                                        </a>                                        
                                    @elseif ($solicitud->estado === 'Validado' || $solicitud->estado === 'Comprado')
                                        <a class="btn btn-info"
                                            href="{{ route('ordenCompra', $solicitud->id_requisicion) }}">
                                            Orden de compra
                                        </a>                                        
                                    @else    
                                        <a class="btn btn-primary" href="" onclick="return false;"
                                            style="pointer-events: none; background-color: gray; cursor: not-allowed;">
                                            Eliminar
                                        </a>
                                        <a class="btn btn-sucess" href="" onclick="return false;"
                                            style="pointer-events: none; background-color: gray; cursor: not-allowed;">
                                            Finalizar
                                        </a>
                                    @endif
                                    <a class="btn btn-success" href="#" data-toggle="modal"
                                        data-target="#Finalizar{{ $solicitud->id_requisicion }}">
                                        Finalizar
                                    </a>
                                        <!-- Logout Modal-->
                                        <div class="modal fade" id="Finalizar{{ $solicitud->id_requisicion }}"
                                            tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                            aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">¿Se ha completado
                                                            la
                                                            requisicion?</h5>
                                                        <button class="close" type="button" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">X</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">Selecciona confirmar para finalizar proceso
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" type="button"
                                                            data-dismiss="modal">cancelar</button>
                                                        <form
                                                            action="{{ route('FinalizarReq', $solicitud->id_requisicion) }}"
                                                            method="POST">
                                                            @csrf
                                                            {!! method_field('PUT') !!}
                                                            <button type="submit"
                                                                class="btn btn-primary">Confirmar</button>
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
