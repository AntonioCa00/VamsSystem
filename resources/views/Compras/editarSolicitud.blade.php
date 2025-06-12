@extends('plantillaAdm')

@section('Contenido')

<!-- Mensaje de error al intentar crear una requisicion vacia -->
@if(session()->has('vacio'))
    <script type="text/javascript">
        Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'No se puede crear una requisicion vacia.',
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
            <h6 class="m-0 font-weight-bold text-primary">Datos de registro</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Cantidad:</th>
                            <th>Unidad:</th>
                            <th>Descripcion:</th>
                            <th>Editar:</th>
                            <th>Rechazar:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Iterar sobre los articulos de la requisicion -->
                        @foreach($articulos as $articulo)
                        <tr>
                            <th>{{ $articulo->cantidad }}</th>
                            <th>{{ $articulo->unidad}}</th>
                            <th>{{ $articulo->descripcion}}</th>
                            <th>
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#editarModal{{ $articulo->id }}">
                                    Editar
                                </button>
                            </th>
                            <th>
                                <form action="{{ route('rechazaArtCompras', ['id' => $articulo->id, 'rid' => $articulo->requisicion_id]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-primary">Rechazar</button>
                                </form>
                            </th>
                        </tr>

                        <!-- Modal -->
                        <div class="modal fade" id="editarModal{{ $articulo->id }}" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel{{ $articulo->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editarModalLabel{{ $articulo->id }}">Editar Elemento</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('editarArtCompras', $articulo->id) }}" method="post">
                                            {!!method_field('PUT')!!}
                                            @csrf
                                            <div class="form-group">
                                                <label>Cantidad:</label>
                                                <input type="text" class="form-control" name="editCantidad" value="{{ $articulo->cantidad}}">
                                            </div>
                                            <div class="form-group">
                                                <label>Unidad de medida:</label>
                                                <input type="text" class="form-control" name="editUnidad" value="{{ $articulo->unidad}}">
                                            </div>
                                            <div class="form-group">
                                                <label>Descripcion:</label>
                                                <input type="text" class="form-control" name="editDescripcion" value="{{ $articulo->descripcion}}">
                                            </div>

                                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer py-3 text-center">
            <!-- Botón para aprobar la requisición -->
            <form action="{{route('aprobarCompras',$articulos[0]->requisicion_id)}}" method="post">
                @csrf
                {!!method_field('PUT')!!}
                <div class="form-group">
                    <label for="exampleFormControlInput1">Comentarios de aprobacion:</label>
                    <input name="Comentarios" type="text" class="form-control" placeholder="Añade comentarios si es necesario...">
                </div>
                <button type="submit" class="btn btn-primary"><h6 >Guardar Cambios</h6></button>
            </form>
        </div>
    </div>
</div>
@endsection
