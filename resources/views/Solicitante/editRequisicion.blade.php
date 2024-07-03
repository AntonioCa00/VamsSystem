@extends('plantillaSol')

@section('contenido')

@if(session()->has('error'))
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
                            <th>Eliminar:</th>
                        </tr>
                    </thead>
                    <tbody>
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
                                <form action="{{ route('deleteArt', ['id' => $articulo->id, 'rid' => $articulo->requisicion_id]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
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
                                        <form action="{{ route('updateArt', $articulo->id) }}" method="post">
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
            <form action="{{route('createArt',$id)}}" method="post">
                @csrf
                <div class="form-group">
                    <label for="exampleFormControlInput1">Descripcion:</label>
                    <input name="Descripcion" type="text" maxlength="47" class="form-control" placeholder="Describe el articulo" required>
                </div>                
                <div class="form-group">
                    <label for="exampleFormControlInput1">Unidad de medidad:</label>
                    <select class="form-control" name="Unidad" id="Unidad" required>
                        <option value="" disabled selected>Selecciona la unidad de medida:</option>
                        <option>Piezas</option>
                        <option>Cajas</option>
                        <option>Litros</option>
                        <option>Kilos</option>
                        <option>Cubetas</option>
                        <option>Garrafas</option>                     
                        <option>Tambo</option>   
                        <option>Metros</option>
                        <option>Tramo</option>                        
                        <option>Juegos</option>
                        <option>Kits</option>
                        <option>Paquetes</option>
                        <option>Servicios</option>
                        <option>Licencias</option>
                        <option value="Otro">Otro...</option>
                    </select>
                </div>
                <div class="form-group" id="otro" style="display: none;">
                    <label for="exampleFormControlInput1">Otra unidad de medida:</label>
                    <input name="otro" id="otraUnidad" type="text" class="form-control" placeholder="Escribe la unidad de medida">
                </div>  
                <div class="form-group">
                    <label for="exampleFormControlInput1">Cantidad:</label>
                    <input name="Cantidad" type="number" class="form-control" placeholder="Cantidad necesaria del articulo" required>
                </div>              
                <button type="submit" class="btn btn-primary">Agregar articulo</button>
            </form>
        </div>
        <div class="card-footer py-3 text-center">
            <form action="{{route('updateSolicitud',$id)}}" method="post">
                @csrf
                {!!method_field('PUT')!!}
                <div class="form-group">
                    <label for="exampleFormControlInput1">Notas:</label>
                    <input name="Notas" type="text" class="form-control" value="{{$unidad->notas}}" placeholder="Agrega notas si necesario">
                </div>
                @if(session('departamento')=== "Mantenimiento")
                    <div class="form-group">
                        <label for="exampleFormControlInput1">UNIDAD PARA REQUISICION</label>
                        <select name="unidad" class="form-control" required>
                            <option value="{{$unidad->id_unidad}}">{{$unidad->id_unidad}} {{$unidad->marca}} {{$unidad->modelo}}</option>
                            <option disabled>Selecciona una unidad en caso de cambiar de unidad la requisicion</option>
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

<script>
    document.getElementById('Unidad').addEventListener('change', function() {
        var valor = this.value;
        var otro = document.getElementById('otro'); // Selecciona el div correctamente ahora
        var input = document.getElementById('otraUnidad'); // Selecciona el input por su nuevo id

        if (valor == 'Otro') {
            otro.style.display = 'block';
            input.required = true;
        } else {
            otro.style.display = 'none';
            input.required = false;
        }
    });
</script>
@endsection