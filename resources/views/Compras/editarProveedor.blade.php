@extends('plantillaAdm')

@section('Contenido')

@if ($errors->has('archivo_estadoCuenta'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Si actualiza datos bancarios, favor de cargar estado de cuenta actualizado',
        showConfirmButton: false,
        timer: 2000
        })
    </script>
@endif

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">PROVEEDORES</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Editar proveedor</h6>
        </div>
        <div class="card-body">
            <h3 class="text-center">Datos de registro</h3>
            <form action="{{route('updateProveedor',$proveedor->id_proveedor)}}" method="POST" enctype="multipart/form-data">
                {!!method_field('PUT')!!}    
                @csrf
                <div class="form-group">
                    <label for="exampleFormControlInput1">Nombre y/o razón social de la empresa:<span style="color: red;">*</span></label>
                    <input name="nombre" type="text" class="form-control" placeholder="Nombre y/o razón social de la empresa proveedor"value="{{$proveedor->nombre}}" >
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Razón social de la empresa:<span
                            style="color: red;">*</span></label>
                    <input name="regimen" type="text" class="form-control"
                        placeholder="Regimen Fiscal del proveedor"value="{{ $proveedor->regimen_fiscal }}"
                        required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Sobrenombre:<span
                            style="color: red;">*</span></label>
                    <input name="sobrenombre" type="text" class="form-control"
                        placeholder="Sobrenombre de la empresa proveedor"value="{{ $proveedor->sobrenombre }}"
                        required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Telefono:<span style="color: red;">*</span></label>
                    <input name="telefono" type="text" class="form-control" placeholder="No° telefonico del proveedor" value="{{$proveedor->telefono}}">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Telefono secundario:</label>
                    <input maxlength="10" name="telefono2" type="text" class="form-control"
                        placeholder="No° telefonico secundarop del proveedor" value="{{$proveedor->telefono2}}">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Nombre del contacto:<span style="color: red;">*</span></label>
                    <input name="contacto" type="text" class="form-control" placeholder="Nombre de la persona contacto de la empresa" value="{{$proveedor->contacto}}">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Dirección:<span style="color: red;">*</span></label>
                    <input name="direccion" type="text" class="form-control" placeholder="Dirección de la empresa" value="{{$proveedor->direccion}}">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Domicilio fiscal:<span style="color: red;">*</span></label>
                    <input name="domicilio" type="text" class="form-control" placeholder="Domicilio fiscal de la empresa" value="{{$proveedor->domicilio}}">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">RFC:<span style="color: red;">*</span></label>
                    <input name="rfc" type="text" class="form-control" placeholder="RFC de la empresa" value="{{$proveedor->rfc}}">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Correo:<span style="color: red;">*</span></label>
                    <input name="correo" type="text" class="form-control" placeholder="Correo electronico de la empresa"  value="{{$proveedor->correo}}">
                </div>                
                <div class="form-group">
                    <label for="exampleFormControlInput1">CIF en formato PDF:<a href="{{ asset($proveedor->CIF) }}" target="_blank">
                        <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                    </a></span></label>
                    <input name="archivo_CIF" type="file" class="form-control">
                </div>
                <div class="card-body">
                    <h3 class="text-center">Datos bancarios</h3>
                    <h5 class="text-center text-primary">En caso de llenar los datos bancarios, favor de adjuntar el archivo pdf solicitado.</h5>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Banco:</label>
                        <input name="banco" type="text" class="form-control" value="{{$proveedor->banco}}" placeholder="Banco por el cual se le efectua el pago a la empresa">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Numero de cuenta:</label>
                        <input name="n_cuenta" type="text" class="form-control" value="{{$proveedor->n_cuenta}}" placeholder="Numero de cuenta al que se le efectua el pago a la empresa">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Numero de cuenta clabe:</label>
                        <input name="n_cuenta_clabe" type="text" class="form-control" value="{{$proveedor->n_cuenta_clabe}}" placeholder="Numero de cuenta clabe al que se le efectua el pago a la empresa">
                    </div>
                    <div class="form-group">
                        @if (!empty($proveedor->estado_cuenta))
                            <label for="exampleFormControlInput1">Caratula del estado de cuenta del banco:<a href="{{ asset($proveedor->estado_cuenta) }}" target="_blank">
                                <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                            </a></label>
                        @else
                            <label for="exampleFormControlInput1">Caratula del estado de cuenta del banco:</label>
                        @endif                        
                        <input name="archivo_estadoCuenta" type="file" class="form-control">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Editar proveedor</button>
            </form>
        </div>
    </div>
</div>
@endsection