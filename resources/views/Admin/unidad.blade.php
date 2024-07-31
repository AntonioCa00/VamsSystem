@extends('plantillaAdm')

@section('Contenido')

@if(session()->has('regis'))
    <script type="text/javascript">
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Unidad registrada',
        showConfirmButton: false,
        timer: 1000
        })
    </script>
@endif

@if(session()->has('update'))
    <script type="text/javascript">
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Unidad Editada',
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
        title: 'Unidad Eliminada',
        showConfirmButton: false,
        timer: 1000
        })
    </script>
@endif

@if(session()->has('activado'))
    <script type="text/javascript">
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Unidad Activada',
        showConfirmButton: false,
        timer: 1000
        })
    </script>
@endif

@if(session()->has('baja'))
    <script type="text/javascript">
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Unidad Inactiva',
        showConfirmButton: false,
        timer: 1000
        })
    </script>
@endif

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">UNIDADES</h1>
        <a href="{{ route('reporteUnidadesCom') }}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Exportar en PDF lista de unidades</a>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a class="btn btn-primary" href="{{route('CreateUnidad')}}">Registrar nueva unidad</a>
            <a class="btn btn-primary" href="{{route('actUnui')}}" style="margin-left: 70%;">Reactivar unidad</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID_unidad:</th>
                            <th>Tipo:</th>
                            <th>Estado:</th>
                            <th>Año Unidad:</th>
                            <th>Marca:</th>
                            <th>Modelo:</th>
                            <th>Caracteristicas:</th>
                            <th>N° serie</th>
                            <th>N° permiso</th>
                            <th>Opciones:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($unidades as $unidad)
                        <tr>
                            <th>{{$unidad->id_unidad}}</th>
                            <th>{{$unidad->tipo}}</th>
                            <th>{{$unidad->estado}}</th>
                            <th>{{$unidad->anio_unidad}}</th>
                            <th>{{$unidad->marca}}</th>
                            <th>{{$unidad->modelo}}</th>
                            <th>{{$unidad->caracteristicas  }}</th>
                            <th>{{$unidad->n_de_serie}}</th>
                            <th>{{$unidad->n_de_permiso}}</th>
                            <th>
                                <a href="{{route('editUnidad',$unidad->id_unidad)}}" class="btn btn-primary">Editar</a>
                                <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#eliminarUnid{{$unidad->id_unidad}}">
                                    Quitar
                                </a>
                                <!-- deleteUnidad Modal-->
                                <div class="modal fade" id="eliminarUnid{{$unidad->id_unidad}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">¿Ha tomado una decisión?</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">X</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">Selecciona que hacer con esta unidad</div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">cancelar</button>
                                                <form action="{{route('deleteUnidad',$unidad->id_unidad)}}" method="POST">
                                                    @csrf
                                                    {!!method_field('PUT')!!}
                                                    <button type="submit" class="btn btn-primary">Eliminar</button>
                                                </form>
                                                <form action="{{route('bajaUnidad',$unidad->id_unidad)}}" method="POST">
                                                    @csrf
                                                    {!!method_field('PUT')!!}
                                                    <button type="submit" class="btn btn-primary">Inactivar</button>
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
