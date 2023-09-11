@extends('plantillaGen')

@section('contenido')

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">SOLICITUDES</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Registrar una nueva solicitud</h6>
        </div>
        <div class="card-body">
            <h3 class="text-center">Datos de registro</h3>
            <form action="{{route('insertSolicitud')}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Unidad:</label>
                    <select name="Unidad" class="form-control">
                        <option selected disabled value="">Selecciona la unidad</option>
                        @foreach($unidades as $unidad)
                            <option value="{{$unidad->IdUnidad}}">{{$unidad->IdUnidad}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Descripcion:</label>
                    <input name="Descripcion" type="text" class="form-control" placeholder="Describe la solicitud">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Refacción</label>
                    <select name="Refaccion" class="form-control">
                        <option selected disabled value="">Selecciona la refaccion que necesitas</option>
                        @foreach($refacciones as $refaccion)
                            <option value="{{$refaccion->id_refaccion}}">{{$refaccion->nombre}}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Guardar solicitud</button>
            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

@endsection