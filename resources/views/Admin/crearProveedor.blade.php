@extends('plantillaAdm')

@section('Contenido')
    @if ($errors->has('archivo_CIF'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Debe de cargar CIF en formato .pdf',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    @if ($errors->has('archivo_estadoCuenta'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Debe de cargar estado de cuenta en formato .pdf y datos bancarios completos',
                showConfirmButton: false,
                timer: 1500
            })
        </script>
    @endif

    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">PROVEEDORES</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Registrar nuevo proveedor</h6>
            </div>
            <div class="card-body">
                <h3 class="text-center">Datos de registro</h3>
                <form action="{{ route('insertProveedor') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Nombre y/o razón social de la empresa:<span
                                style="color: red;">*</span></label>
                        <input name="nombre" type="text" class="form-control" maxlength="26"
                            placeholder="Nombre y/o razón social de la empresa proveedor"value="{{ old('nombre') }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Telefono:<span style="color: red;">*</span></label>
                        <input maxlength="10" name="telefono" type="text" class="form-control"
                            placeholder="No° telefonico del proveedor" value="{{ old('telefono') }}"required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Telefono secundario:</label>
                        <input maxlength="10" name="telefono2" type="text" class="form-control"
                            placeholder="No° telefonico secundarop del proveedor" value="{{ old('telefono2') }}"required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Nombre del contacto:<span style="color: red;">*</span></label>
                        <input name="contacto" type="text" class="form-control"
                            placeholder="Nombre de la persona contacto de la empresa" value="{{ old('contacto') }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Dirección:<span style="color: red;">*</span></label>
                        <input name="direccion" type="text" class="form-control" placeholder="Dirección de la empresa"
                            value="{{ old('direccion') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Domicilio fiscal:<span style="color: red;">*</span></label>
                        <input name="domicilio" type="text" class="form-control"
                            placeholder="Domicilio fiscal de la empresa" value="{{ old('domicilio') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">RFC:<span style="color: red;">*</span></label>
                        <input maxlength="13" name="rfc" type="text" class="form-control" placeholder="RFC de la empresa"
                            value="{{ old('rfc') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Correo:<span style="color: red;">*</span></label>
                        <input name="correo" type="email" class="form-control"
                            placeholder="Correo electronico de la empresa" value="{{ old('correo') }}"required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">CIF en formato PDF:<span style="color: red;">*</span></label>
                        <input name="archivo_CIF" type="file" class="form-control" value="{{ old('archivo_CIF') }}"
                            required>
                    </div>
                    <div class="card-body">
                        <h3 class="text-center">Datos bancarios</h3>
                        <h5 class="text-center text-primary">En caso de llenar los datos bancarios, favor de adjuntar el
                            archivo pdf solicitado</h5>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Banco:</label>
                            <input name="banco" type="text" class="form-control" value="{{ old('banco') }}"
                                placeholder="Banco por el cual se le efectua el pago a la empresa">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Numero de cuenta:</label>
                            <input name="n_cuenta" type="text" class="form-control" value="{{ old('n_cuenta') }}"
                                placeholder="Numero de cuenta al que se le efectua el pago a la empresa">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Numero de cuenta clabe:</label>
                            <input name="n_cuenta_clabe" type="text" class="form-control"
                                value="{{ old('n_cuenta_clabe') }}"
                                placeholder="Numero de cuenta clabe al que se le efectua el pago a la empresa">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Caratula del estado de cuenta del banco:</label>
                            <input name="archivo_estadoCuenta" type="file" value="{{ old('archivo_estadoCuenta') }}"
                                class="form-control">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Registrar proveedor</button>
                </form>
            </div>
        </div>
    </div>
@endsection
