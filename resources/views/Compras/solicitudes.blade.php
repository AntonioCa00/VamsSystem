@extends('plantillaAdm')

@section('Contenido')

@if(session()->has('cotizacion'))
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

@if(session()->has('finalizada'))
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

    <!-- Encabezado de la tabla -->
    <h1 class="h3 mb-2 text-gray-800">SOLICITUDES</h1>

    <!-- Tabla de los datos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">HISTORIAL DE SOLICITUDES</h6>
            <div class="form-group d-flex align-items-center">
                <a href="" class="btn btn-secondary" href="#" data-toggle="modal" data-target="#Filtro">
                    <img src="{{asset('img/filtrar.png')}}">Filtrar requisiciones
                </a>
                <div class="modal fade" id="Filtro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Filtrado de requisiciones</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">X</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group align-items-center">
                                    <form action="{{route('filtrarSolic','departamento')}}" method="POST" class="w-100 d-flex align-items-center mb-4">
                                        @csrf
                                        <select name="filtro" class="form-control flex-grow-1 mr-2" required>
                                            <option value="" disabled selected>Filtrar por departamento...</option>
                                            @foreach ($departamentos as $dpto)
                                                <option value="{{$dpto->departamento}}">{{$dpto->departamento}}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-primary">Filtrar</button>
                                    </form>
                                    <form action="{{route('filtrarSolic','estado')}}" method="POST" class="w-100 d-flex align-items-center mb-2">
                                        @csrf
                                        <select name="filtro" class="form-control flex-grow-1 mr-2" required>
                                            <option value="" disabled selected>Filtrar por estatus...</option>
                                            @foreach ($estatus as $estat)
                                                <option value="{{$estat->estado}}">{{$estat->estado}}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-primary">Filtrar</button>
                                    </form>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="{{route('solicitudes')}}" class="btn btn-success">Ver Todas</a>
                            </div>
                        </div>
                    </div>
                </div>
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
                            {{-- <th style="width: 150px; /* o el ancho específico que desees */
                            max-width: 150px; /* coincide con el ancho para asegurar el límite */
                            overflow: hidden;
                            white-space: nowrap;text-overflow: ellipsis;">Servicio:</th> --}}
                            <th>Requisicion:</th>
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
                            <th class="text-center">
                                @if (empty($solicitudes->detalles))
                                <a href="#" data-toggle="modal" data-target="#Comentarios{{$solicitudes->id_requisicion}}">
                                    <img src="{{ asset('img/comente.png') }}" alt="Abrir PDF">
                                </a>
                                    <div class="modal fade text-left" id="Comentarios{{$solicitudes->id_requisicion}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                                <a href="#" data-toggle="modal" data-target="#Comentarios{{$solicitudes->id_requisicion}}">
                                    <img src="{{ asset('img/comentarios.png') }}" alt="Abrir PDF">
                                </a>
                                    <div class="modal fade" id="Comentarios{{$solicitudes->id_requisicion}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                    aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Seguimiento de comentarios</h5>
                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">X</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">Comentario emitido por: <strong>{{$solicitudes->rol}}</strong></div>
                                                <div class="modal-body">Comentario: <strong>{{$solicitudes->detalles}}</strong></div>
                                                <div class="modal-body">Fecha del comentario: <strong>{{$solicitudes->fechaCom}}</strong></div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">cancelar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </th>
                            <th class="text-center">
                                <a href="{{ asset($solicitudes->pdf) }}" target="_blank">
                                    <img class="imagen-container"  src="{{ asset('img/req.jpg') }}" alt="Abrir PDF">
                                </a>
                            </th>
                            <th>
                                @if ($solicitudes->estado === "Aprobado" || $solicitudes->estado === "Cotizado")
                                    <a class="btn btn-primary" href="{{route('createCotiza', $solicitudes->id_requisicion)}}">
                                        Subir cotizaciones
                                    </a>
                                @elseif ($solicitudes->estado === "Validado" ||$solicitudes->estado ==="Comprado")
                                    <a class="btn btn-info" href="{{route('ordenCompra',$solicitudes->id_requisicion)}}">
                                        Orden de compra
                                    </a>
                                    <a class="btn btn-success" href="#" data-toggle="modal" data-target="#Finalizar{{$solicitudes->id_requisicion}}">
                                        Finalizar
                                    </a>
                                    <!-- Logout Modal-->
                                    <div class="modal fade" id="Finalizar{{$solicitudes->id_requisicion}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">¿Se ha completado la requisicion?</h5>
                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">X</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">Selecciona confirmar para finalizar proceso</div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">cancelar</button>
                                                    <form action="{{route('FinalizarReq',$solicitudes->id_requisicion)}}" method="POST">
                                                        @csrf
                                                        {!!method_field('PUT')!!}
                                                        <button type="submit" class="btn btn-primary">Confirmar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <a class="btn btn-primary" href="" onclick="return false;" style="pointer-events: none; background-color: gray; cursor: not-allowed;">
                                        Eliminar
                                    </a>
                                    <a class="btn btn-success" href="#" data-toggle="modal" data-target="#Finalizar{{$solicitudes->id_requisicion}}">
                                        Finalizar
                                    </a>
                                    <!-- Logout Modal-->
                                    <div class="modal fade" id="Finalizar{{$solicitudes->id_requisicion}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">¿Se ha completado la requisicion?</h5>
                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">X</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">Selecciona confirmar para finalizar proceso</div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">cancelar</button>
                                                    <form action="{{route('FinalizarReq',$solicitudes->id_requisicion)}}" method="POST">
                                                        @csrf
                                                        {!!method_field('PUT')!!}
                                                        <button type="submit" class="btn btn-primary">Confirmar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
