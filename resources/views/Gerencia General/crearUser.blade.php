@extends('plantillaGerGen')

@section('contenido')

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">ENCARGADOS</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Registrar nuevo usuario</h6>
        </div>
        <div class="card-body">
            <h3 class="text-center">Datos de registro</h3>
            <form action="{{route('insertUser')}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="exampleFormControlInput1">Nombre completo:</label>
                    <input name="nombre" type="text" class="form-control" placeholder="Nombre completo del usuario" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Telefono:</label>
                    <input name="telefono" type="text" class="form-control" placeholder="No° telefonico del usuario" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Correo:</label>
                    <input name="correo" type="text" class="form-control" placeholder="Correo electronico del usuario" required>
                </div>

                <div class="form-group">
                    <label for="exampleFormControlSelect1">Rol del usuario:</label>
                    <select name="rol" class="form-control" id="rol" required>
                        <option selected disabled value="">Selecciona el rol que tendrá el usuario</option>
                        <option>Gerencia General</option>
                        <option>Gerente Area</option>
                        <option>Compras</option>
                        <option>Otro</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">¿A que departamento pertence?</label>
                    <select name="departamento" class="form-control" required>
                        <option selected disabled value="">Selecciona el departamento al que pertenece el usuario</option>
                        <option>Direccion</option>
                        <option>Compras</option>
                        <option>Finanzas</option>
                        <option>Logística</option>
                        <option>Mantenimiento</option>
                        <option>RH</option>            
                        <option>Ventas</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Registrar usuario nuevo</button>
            </form>
        </div>
    </div>
</div>
@endsection