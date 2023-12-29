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
            <form action="{{route('insertRefaccion')}}" method="post">
                @csrf
                <div class="form-group">
                    <label for="exampleFormControlInput1">Ubicación:</label>
                    <input name="ubicacion" type="text" class="form-control" placeholder="Escribe la ubicacion de la refaccción" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Descripcion</label>
                    <input name="descripcion" type="text" min="1981" class="form-control" placeholder="Escribe una descripcion de la refaccción" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Medida:</label>
                    <input name="medida" type="text" class="form-control" placeholder="Escribe la medida de la refaccción" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Marca:</label>
                    <input name="marca" type="text" class="form-control" placeholder="Escribe la marca la refacción" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Cantidad:</label>
                    <input name="cantidad" type="text" class="form-control" placeholder="Ingresa que cantidad a registrar" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Crear refacción</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection