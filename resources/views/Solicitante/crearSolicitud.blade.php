@extends('plantillaSol')

@section('contenido')

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
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Cantidad:</th>
                            <th>Unidad:</th>
                            <th>Descripcion:</th>
                            <th>Editar:</th>
                            <th>Eliminar:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($datos as $index => $dato)
                        <tr>
                            <th>{{ $dato['cantidad'] }}</th>
                            <th>{{ $dato['unidad'] }}</th>
                            <th>{{ $dato['descripcion'] }}</th>                            
                            <th>
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#editarModal{{ $index }}">
                                    Editar
                                </button>                                
                            </th>
                            <th>
                                <form action="{{ route('eliminarElemento', ['index' => $index]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-primary">Eliminar</button>
                                </form>
                            </th>
                        </tr>

                        <!-- Modal -->
                        <div class="modal fade" id="editarModal{{ $index }}" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel{{ $index }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editarModalLabel{{ $index }}">Editar Elemento</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('editArray', ['index' => $index]) }}" method="post">
                                            @csrf
                                            <div class="form-group">
                                                <label for="editCantidad{{ $index }}">Cantidad:</label>
                                                <input type="text" class="form-control" name="editCantidad" id="editCantidad{{ $index }}" value="{{ $dato['cantidad'] }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="editCantidad{{ $index }}">Unidad de medida:</label>
                                                <input type="text" class="form-control" name="editUnidad" id="editCantidad{{ $index }}" value="{{ $dato['unidad'] }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="editCantidad{{ $index }}">Descripcion:</label>
                                                <input type="text" class="form-control" name="editDescripcion" id="editCantidad{{ $index }}" value="{{ $dato['descripcion'] }}">
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
            <form action="{{route('arraySoli')}}" method="post">
                @csrf
                <div class="form-group">
                    <label for="exampleFormControlInput1">Cantidad:</label>
                    <input name="Cantidad" type="number" class="form-control" placeholder="Cantidad necesaria del articulo" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Unidad de medidad:</label>
                    <input name="Unidad" type="text" class="form-control" placeholder="Unidad de medida de articulo" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Descripcion:</label>
                    <input name="Descripcion" type="text" class="form-control" placeholder="Describe el articulo" required>
                </div>
                <button type="submit" class="btn btn-primary">Agregar articulo</button>
            </form>
        </div>
        <div class="card-footer py-3 text-center">
            <form action="{{route('requisicion')}}" method="post">
                @csrf
                <div class="form-group">
                    <label for="exampleFormControlInput1">Notas:</label>
                    <input name="Notas" type="text" class="form-control" placeholder="Agrega notas si necesario">
                </div>
                @if(session('departamento')=== "Mantenimiento")
                    <div class="form-group">
                        <label for="exampleFormControlInput1">UNIDAD PARA REQUISICION</label>
                        <select name="unidad" class="form-control" required>
                            <option value="" selected disabled>Selecciona la unidad que requiere la refaccion:</option>
                            @foreach ($unidades as $unidad)                            
                                <option value="{{$unidad->id_unidad}}">{{$unidad->id_unidad}} {{$unidad->marca}} {{$unidad->modelo}}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <button type="submit" class="btn btn-primary"><h6 >Crear formato de requisición</h6></button>
            </form>
        </div>
    </div>
</div>
@endsection