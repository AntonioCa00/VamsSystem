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
                <form action="{{ route('insertUser') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Nombre(s):</label>
                        <input name="nombres" type="text" class="form-control" placeholder="Nombre(s) del usuario"
                            value="{{old('nombres')}}" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Apellido Paterno:</label>
                        <input name="apepat" type="text" class="form-control" placeholder="Apellido paterno del usuario"
                            value="{{old('apepat')}}" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Apellido Materno:</label>
                        <input name="apemat" type="text" class="form-control" placeholder="Apellido materno del usuario"
                            value="{{old('apemat')}}" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Telefono:</label>
                        <input name="telefono" type="text" class="form-control" placeholder="No° telefonico del usuario"
                            value="{{old('telefono')}}" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Correo:</label>
                        <input name="correo" type="text" class="form-control" placeholder="Correo electronico del usuario"
                            value="{{old('correo')}}" required>
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Rol del usuario:</label>
                        <select name="rol" class="form-control" id="rol" required>
                            <option selected disabled value="">Selecciona el rol que tendrá el usuario</option>
                            <option value="Gerencia General" {{ old('rol') == 'Gerencia General' ? 'selected' : '' }}>Gerencia General</option>
                            <option value="Gerente Area" {{ old('rol') == 'Gerente Area' ? 'selected' : '' }}>Gerente Area</option>
                            <option value="Compras" {{ old('rol') == 'Compras' ? 'selected' : '' }}>Compras</option>
                            <option value="General" {{ old('rol') == 'General' ? 'selected' : '' }}>Solicitante</option>
                        </select>
                    </div>
                    <label for="exampleFormControlInput1">¿A que departamento pertence?</label>
                    @if ($errors->has('departamentos'))
                        <p class="text-danger fst-italic fw-bold">{{ $errors->first('departamentos') }}</p>
                    @endif
                    <div class="form-check">
                        <input name="departamentos[]" class="form-check-input" type="checkbox" value="Finanzas" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                            Finanzas
                        </label>
                    </div>
                    <div class="form-check">
                        <input name="departamentos[]" class="form-check-input" type="checkbox" value="Logistica" id="flexCheckChecked">
                        <label class="form-check-label" for="flexCheckChecked">
                            Logistica
                        </label>
                    </div>
                    <div class="form-check">
                        <input name="departamentos[]" class="form-check-input" type="checkbox" value="Mantenimiento" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                            Mantenimiento
                        </label>
                    </div>
                    <div class="form-check">
                        <input name="departamentos[]" class="form-check-input" type="checkbox" value="RH" id="flexCheckChecked">
                        <label class="form-check-label" for="flexCheckChecked">
                            RH
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input name="departamentos[]" class="form-check-input" type="checkbox" value="Ventas" id="flexCheckChecked">
                        <label class="form-check-label" for="flexCheckChecked">
                            Ventas
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary">Registrar usuario nuevo</button>
                </form>
            </div>
        </div>
    </div>
@endsection
