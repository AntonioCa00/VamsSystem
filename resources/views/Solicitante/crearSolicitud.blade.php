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
                            <th>Descripcion:</th>
                            <th>Opciones:</th>  
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($datos as $index => $dato)
                        <tr>
                            <th>{{ $dato['cantidad'] }}</th>
                            <th>{{ $dato['descripcion'] }}</th>
                            <th>
                                <form action="{{ route('eliminarElemento', ['index' => $index]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-primary">Eliminar</button>
                                </form>                                
                            </th>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <form action="{{route('arraySoli')}}" method="post">
                @csrf
                <div class="form-group">
                    <label for="exampleFormControlInput1">Cantidad:</label>
                    <input name="Cantidad" type="number" class="form-control" placeholder="Cantidad de refacciones necesarias" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Descripcion:</label>
                    <input name="Descripcion" type="text" class="form-control" placeholder="Describe la solicitud" required>
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
                <div class="form-group">
                    <label for="exampleFormControlInput1">UNIDAD PARA REQUISICION</label>
                    <select name="unidad" class="form-control" required>
                        <option value="" selected disabled>Selecciona la unidad que requiere la refaccion:</option>
                        @foreach ($unidades as $unidad)                            
                            <option value="{{$unidad->id_unidad}}">{{$unidad->id_unidad}}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary"><h6 >Crear formato de requisición</h6></button>
            </form>
        </div>
    </div>

</div>

@endsection