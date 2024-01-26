@extends('plantillaGerGen')

@section('contenido')

@if(session()->has('eliminado'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Solicitud eliminada',
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
                            <th>Cotizaciones:</th>
                            <th>Orden Compra:</th>
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
                            @if (empty($solicitudes->unidad_id))
                                <th>Sin unidad</th>
                            @elseif ($solicitudes->unidad_id == "1")
                                <th>No asignada</th>
                            @else 
                                <th>{{$solicitudes->unidad_id}}</th>
                            @endif                         
                            <th>
                                <a href="{{ asset($solicitudes->pdf) }}" target="_blank">
                                    <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                </a>
                            </th>
                            @if ($solicitudes->estado === "Cotizado" || $solicitudes->estado === "Pre Validado"|| $solicitudes->estado === "Validado" || $solicitudes->estado === "Pre Validado")
                                <th>
                                    <a href="{{route('verCotizaciones',$solicitudes->id_requisicion)}}" class="btn btn-primary">Consultar</a>
                                </th>
                            @else 
                                <th>Sin cotizar</th>
                            @endif
                            @if (empty($solicitudes->ordenCompra))
                                <th>No generada aún</th>
                            @else
                                <a href="{{ asset($solicitudes->ordenCompra) }}" target="_blank">
                                    <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                </a>
                            @endif
                            <th>
                                <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#eliminarReq{{$solicitudes->id_requisicion}}">
                                    Eliminar
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
                                            <div class="modal-body">Selecciona confirmar para eliminar esta requisición</div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">cancelar</button>
                                                <form action="{{route('deleteSolicitudGG',$solicitudes->id_requisicion)}}" method="POST">
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