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
            <form action="">
                <div class="form-group">
                    <label for="exampleFormControlInput1">Nombre de la refacción:</label>
                    <input name="nombre" type="text" class="form-control" placeholder="Escribe el nombre de la refaccción" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Marca:</label>
                    <input name="marca" type="text" class="form-control" placeholder="Escribe la marca de la refaccción" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Año:</label>
                    <input name="anio" type="number" min="1981" class="form-control" placeholder="Escribe año de la refaccción" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Modelo:</label>
                    <input name="modelo" type="text" class="form-control" placeholder="Escribe el modelo de la refaccción" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Descipción:</label>
                    <input name="descripcion" type="text" class="form-control" placeholder="Describe un poco más la refacción" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Stock:</label>
                    <input name="cantidad" type="text" class="form-control" placeholder="Ingresa que cantidad a registrar" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Crear refacción</button>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

@endsection