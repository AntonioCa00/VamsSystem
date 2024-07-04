@extends('plantillaAdm')

@section('Contenido')

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">ORDEN DE COMPRA</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">ORDEN DE COMPRA</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Requisicion:</th>
                            <th>Cotizacion Validada:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th class="text-center">
                                <a href="{{ asset($cotizacion->reqPDF) }}" target="_blank">
                                    <img class="imagen-container" src="{{ asset('img/req.jpg') }}" alt="Abrir PDF">
                                </a>
                            </th>
                            <th class="text-center">
                                <a href="{{ asset($cotizacion->cotPDF) }}" target="_blank">
                                    <img class="imagen-container" src="{{ asset('img/cot.jpg') }}" alt="Abrir PDF">
                                </a>
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h6 class="m-0 font-weight-bold text-primary">Articulos de la requisicion</h6>
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><input checked type="checkbox" id="checkTodos"/> Seleccionar:</th>
                            <th>Cantidad:</th>
                            <th>Unidad de medida:</th>
                            <th>Descripcion:</th>
                            <th>Precio unitario SIN IVA:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <form action="{{route('createOrdenCompra',['cid' => $cotizacion->id_cotizacion, 'rid' => $id])}}" method="post">
                            @csrf
                        @foreach($articulos as $index => $articulo)
                        <tr>
                            <th>
                                <input checked type="checkbox" name="articulos_seleccionados[]" value="{{$articulo->id}}" onchange="toggleRequired(this)">
                            </th>
                            <th><input type="hidden" name="articulos[{{ $articulo->id }}][id]" value="{{ $articulo->id }}">
                                <input class="form-control" type="text" name="articulos[{{ $articulo->id }}][cantidad]" value="{{ $articulo->cantidad }}" required></th>
                            <th><input class="form-control" type="text" name="articulos[{{ $articulo->id }}][unidad]" value="{{ $articulo->unidad }}" required></th>
                            <th><input class="form-control" type="text" name="articulos[{{ $articulo->id }}][descripcion]" value="{{ $articulo->descripcion }}" required></th>

                            <th><input class="form-control" type="text" name="articulos[{{ $articulo->id }}][precio_unitario]" value="{{ $articulo->precio }}"></th>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer py-3">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">PROVEEDOR PARA ORDEN DE COMPRA</label>
                        <select name="Proveedor" id="proveedor" class="form-control" required>
                            <option value="" selected disabled>Selecciona el proveedor de este articulo:</option>
                            @foreach ($proveedores as $proveedor)
                                <option value="{{$proveedor->id_proveedor}}">{{$proveedor->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Condiciones de pago:</label>
                        <select name="condiciones" id="condicionPago" class="form-control" required>
                            <option value="" selected disabled>Selecciona la condicion de pago acordada:</option>
                            <option value="Contado">Contado</option>
                            <option value="Credito">Crédito</option>
                        </select>
                    </div>
                    <div id="datosBancarios" style="display: none;">
                        <label for="banco">Días de credito acordado:</label>
                        <input type="text" class="form-control" style="width: 60%" id="banco" name="dias"><br>
                    </div>
                    <div class="form-group mt-4">
                        <label for="exampleFormControlInput1">Notas:</label>
                        <input name="Notas" type="text" class="form-control" value="" placeholder="Agrega notas si necesario">
                    </div>
                    <button type="submit" class="btn btn-primary"><h6 >Crear formato de orden de compra</h6></button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Obtener el checkbox universal
    var checkTodos = document.getElementById('checkTodos');
    // Obtener todos los checkboxes individuales
    var checkboxes = document.querySelectorAll('input[type="checkbox"][name^="articulos_seleccionados"]');

    // Iterar sobre cada checkbox y establecer el atributo "required" en el campo precio_unitario correspondiente
    checkboxes.forEach(function (checkbox) {
        var precioInput = checkbox.closest('tr').querySelector('input[name*="[precio_unitario]"]');
        precioInput.required = true;

        // Agregar un event listener para cambiar el estado del atributo "required" cuando cambia el estado del checkbox individual
        checkbox.addEventListener('change', function () {
            toggleRequired(this);
        });
    });

    // Función para cambiar el estado del atributo "required" según el estado del checkbox individual
    function toggleRequired(checkbox) {
        var precioInput = checkbox.closest('tr').querySelector('input[name*="[precio_unitario]"]');
        if (checkbox.checked) {
            precioInput.required = true;
        } else {
            precioInput.required = false;
        }
    }

    // Agregar un event listener para cambiar el estado del atributo "required" cuando cambia el estado del checkbox universal
    checkTodos.addEventListener('change', function (e) {
        var estado = this.checked; // true o false
        var checkboxes = document.querySelectorAll('input[type="checkbox"][name^="articulos_seleccionados"]');

        checkboxes.forEach(function (checkbox) {
            checkbox.checked = estado;
            toggleRequired(checkbox);
        });
    });
});

    document.getElementById('condicionPago').addEventListener('change', function() {
    var valor = this.value;
    var datosBancarios = document.getElementById('datosBancarios');

    if (valor == 'Credito') {
        datosBancarios.style.display = 'block';
    } else {
        datosBancarios.style.display = 'none';
    }
});

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('checkTodos').addEventListener('change', function (e) {
        var estado = this.checked; // true o false
        var checkboxes = document.querySelectorAll('input[type="checkbox"][name^="articulos_seleccionados"]');

        checkboxes.forEach(function (checkbox) {
            checkbox.checked = estado;
        });
    });
});
</script>
@endsection
