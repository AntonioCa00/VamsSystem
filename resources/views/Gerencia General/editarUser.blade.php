@extends('plantillaGerGen')

@section('contenido')

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">USUARIOS</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Editar usuario</h6>
        </div>
        <div class="card-body">
            <h3 class="text-center">Datos de registro</h3>
            <form action="{{route('updateUser',$encargado->id)}}" method="POST">
                {!!method_field('PUT')!!}
                @csrf
                <div class="form-group">
                    <label for="exampleFormControlInput1">Nombre(s):</label>
                    <input name="nombres" type="text" class="form-control" value="{{$encargado->nombres}}" placeholder="Nombre(s) del usuario">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Apellido Paterno:</label>
                    <input name="apepat" type="text" class="form-control" value="{{$encargado->apellidoP}}" placeholder="Apellido paterno del usuario" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Apellido Materno:</label>
                    <input name="apemat" type="text" class="form-control" value="{{$encargado->apellidoM}}" placeholder="Apellido materno del usuario" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Telefono:</label>
                    <input name="telefono" type="text" class="form-control" value="{{$encargado->telefono}}" placeholder="No° telefonico del usuario">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Correo:</label>
                    <input name="correo" type="text" class="form-control" value="{{$encargado->correo}}" placeholder="Correo electronico del usuario">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Contraseña:</label>
                    <input name="password" type="text" class="form-control" value="{{$encargado->password}}" placeholder="Contraseña del usuario">
                </div>

                <div class="form-group">
                    <label for="exampleFormControlSelect1">Rol del usuario:</label>
                    <select name="rol" class="form-control">
                        <option value="{{$encargado->rol}}"selected>{{$encargado->rol}}</option>
                        <option disabled value="">Selecciona el rol que tendrá el usuario</option>
                        <option value="Gerencia General">Gerencia General</option>
                        <option value="Gerente Area">Gerente Area</option>
                        <option value="Compras">Compras</option>
                        <option value="General">Solicitante</option>
                    </select>
                </div>

                <label for="exampleFormControlInput1">¿A que departamento pertence?</label>
                    @if ($errors->has('departamentos'))
                        <p class="text-danger fst-italic fw-bold">{{ $errors->first('departamentos') }}</p>
                    @endif
                    @php
                        // Separar los departamentos en un array
                        $departamentosSeleccionados = explode(' / ', $encargado->departamento);
                    @endphp

                    <div class="form-check">
                        <input name="departamentos[]" class="form-check-input" type="checkbox" value="Finanzas" id="flexCheckDefault"
                            {{ in_array('Finanzas', $departamentosSeleccionados) ? 'checked' : '' }}>
                        <label class="form-check-label" for="flexCheckDefault">Finanzas</label>
                    </div>
                    <div class="form-check">
                        <input name="departamentos[]" class="form-check-input" type="checkbox" value="Logistica" id="flexCheckChecked"
                            {{ in_array('Logistica', $departamentosSeleccionados) ? 'checked' : '' }}>
                        <label class="form-check-label" for="flexCheckChecked">Logistica</label>
                    </div>
                    <div class="form-check">
                        <input name="departamentos[]" class="form-check-input" type="checkbox" value="Mantenimiento" id="flexCheckDefault"
                            {{ in_array('Mantenimiento', $departamentosSeleccionados) ? 'checked' : '' }}>
                        <label class="form-check-label" for="flexCheckDefault">Mantenimiento</label>
                    </div>
                    <div class="form-check">
                        <input name="departamentos[]" class="form-check-input" type="checkbox" value="RH" id="flexCheckChecked"
                            {{ in_array('RH', $departamentosSeleccionados) ? 'checked' : '' }}>
                        <label class="form-check-label" for="flexCheckChecked">RH</label>
                    </div>
                    <div class="form-check mb-3">
                        <input name="departamentos[]" class="form-check-input" type="checkbox" value="Ventas" id="flexCheckChecked"
                            {{ in_array('Ventas', $departamentosSeleccionados) ? 'checked' : '' }}>
                        <label class="form-check-label" for="flexCheckChecked">Ventas</label>
                    </div>
                <button type="submit" class="btn btn-primary">Editar usuario</button>
            </form>
        </div>
    </div>
</div>
@endsection
