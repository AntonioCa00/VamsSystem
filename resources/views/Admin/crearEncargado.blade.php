@extends('plantillaAdm')

@section('Contenido')

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">ENCARGADOS</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Registrar nuevo encargado</h6>
        </div>
        <div class="card-body">
            <h3 class="text-center">Datos de registro</h3>
            <form action="" method="POST">
                @csrf
                <div class="form-group">
                    <label for="exampleFormControlInput1">Nombre completo:</label>
                    <input name="nombre" type="text" class="form-control" placeholder="Placas de la unidad" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Telefono:</label>
                    <input name="telefono" type="text" class="form-control" placeholder="Placas de la unidad" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Correo:</label>
                    <input name="correo" type="text" class="form-control" placeholder="Correo electronico del encargado" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Puesto:</label>
                    <select name="puesto" class="form-control" >
                        <option selected disabled value="">Selecciona el puesto del encargado</option>
                        <option>Gerente</option>
                        <option>Supervisor</option>
                        <option>Coordinador</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">:</label>
                    <input name="correo" type="text" class="form-control" placeholder="Correo electronico del encargado" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Año:</label>
                    <input name="anio_unidad" type="number" class="form-control" placeholder="Año de la unidad">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Marca:</label>
                    <input name="marca" type="text" class="form-control"     placeholder="Marca de la unidad">
                </div>
                <button type="submit" class="btn btn-primary">Registrar unidad</button>
            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

@endsection