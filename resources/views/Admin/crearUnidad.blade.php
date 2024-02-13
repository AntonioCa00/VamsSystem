@extends('plantillaAdm')

@section('Contenido')
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">UNIDADES</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Registrar nueva unidad</h6>
            </div>
            <div class="card-body">
                <h3 class="text-center">Datos de registro</h3>
                <form action="{{ route('insertUnidad') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Codigo de la unidad:</label>
                        <input name="id_unidad" type="text" class="form-control" placeholder="Placas de la unidad"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Tipo de vehiculo</label>
                        <select name="tipo" class="form-control" required>
                            <option selected disabled value="">Selecciona el tipo de vehiculo</option>
                            <option>CAMIÓN</option>
                            <option>CAMIONETA</option>
                            <option>AUTOMOVIL</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Estado:</label>
                        <select name="estado" class="form-control" required>
                            <option selected disabled value="">Selecciona el estado en el que se encuentra el vehiculo
                            </option>
                            <option>Activo</option>
                            <option>Inactivo</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Año:</label>
                        <input name="anio_unidad" type="number" class="form-control" placeholder="Año de la unidad"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Marca:</label>
                        <input name="marca" type="text" class="form-control" placeholder="Marca de la unidad" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Modelo:</label>
                        <input name="modelo" type="text" class="form-control"
                            placeholder="Modelo especifico de la unidad" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Caracteristicas:</label>
                        <input name="caracteristicas" type="text" class="form-control"
                            placeholder="Color particular del vehiculo"required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Numero de serie:</label>
                        <input name="serie" type="text" class="form-control"
                            placeholder="Numero de serie del vehiculo"required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Numero de permiso:</label>
                        <input name="permiso" type="text" class="form-control"
                            placeholder="Numero de permiso del vehiculo"required>
                    </div>
                    <button type="submit" class="btn btn-primary">Registrar unidad</button>
                </form>
            </div>
        </div>
    </div>
@endsection
