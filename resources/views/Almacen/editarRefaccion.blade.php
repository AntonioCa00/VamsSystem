@extends('plantillaAlm')

@section('contenido')

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">ALMACEN</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DATOS DE REGISTRO</h6>
        </div>
        <div class="card-body">
            <form action="{{route('updateRefaccion',$refaccion->clave)}}" method="post">
                {!!method_field('PUT')!!}    
                @csrf
                <div class="form-group">
                    <label for="exampleFormControlInput1">Clave de la refacción:</label>
                    <input value="{{$refaccion->clave}}" name="clave" type="text" class="form-control" placeholder="Escribe la clave de la refaccción" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Ubicación:</label>
                    <input value="{{$refaccion->ubicacion}}" name="ubicacion" type="text" class="form-control" placeholder="Escribe la marca de la refaccción" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Descripcion:</label>
                    <input value="{{$refaccion->descripcion}}" name="descripcion" type="text" class="form-control" placeholder="Descripcion de la refaccción" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Medida:</label>
                    <input value="{{$refaccion->medida}}" name="medida" type="text" class="form-control" placeholder="Escribe la medida de la refaccción" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Marca:</label>
                    <input value="{{$refaccion->marca}}" name="marca" type="text" class="form-control" placeholder="Escribe la marca la refacción" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Stock:</label>
                    <input value="{{$refaccion->cantidad}}" value="{{$refaccion->cantidad}}" name="cantidad" type="text" class="form-control" placeholder="Ingresa que cantidad a registrar" disabled>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Editar refacción</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection