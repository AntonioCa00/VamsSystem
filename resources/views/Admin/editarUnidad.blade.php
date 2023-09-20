@extends('plantillaAdm')

@section('Contenido')

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">UNIDADES</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Editar unidad unidad</h6>
        </div>
        <div class="card-body">
            <h3 class="text-center">Datos de registro</h3>
            <form action="" method="post">
                @csrf
                {!!method_field('PUT')!!}
                <div class="form-group">
                    <label for="exampleFormControlInput1">Codigo de la unidad:</label>
                    <input name="id_unidad" type="text" value="{{$unidad->id_unidad}}" class="form-control"placeholder="Placas de la unidad">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Tipo de vehiculo</label>
                    <select name="tipo" class="form-control">
                        <option selected >{{$unidad->tipo}}</option>
                        <option>Camión</option>
                        <option>Autobús</option>
                        <option>Camioneta</option>
                        <option>Trailer</option>
                        <option>Automovil</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Estado:</label>
                    <select name="estado"class="form-control">
                        <option selected>{{$unidad->estado}}</option>
                        <option>Activo</option>
                        <option>Inactivo</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Año:</label>
                    <input name="anio_unidad" value="{{$unidad->ano_unidad}}" type="number" class="form-control" placeholder="Año de la unidad">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Marca:</label>
                    <input name="marca" value="{{$unidad->marca}}" type="text" class="form-control" placeholder="Marca de la unidad">
                </div>
                <button type="submit" class="btn btn-primary">Editar unidad</button>
            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

@endsection