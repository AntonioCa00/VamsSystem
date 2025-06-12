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
                                <!-- Mostrar archivo de requisición -->
                                <th class="text-center">
                                    <a href="{{ asset($cotizacion->reqPDF) }}" target="_blank">
                                        <img class="imagen-container" src="{{ asset('img/req.jpg') }}" alt="Abrir PDF">
                                    </a>
                                </th>
                                <!-- Mostrar cotización validada -->
                                <th class="text-center">
                                    <a href="{{ asset($cotizacion->cotPDF) }}" target="_blank">
                                        <img class="imagen-container" src="{{ asset('img/cot.jpg') }}" alt="Abrir PDF">
                                    </a>
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <form action="{{ route('createOrdenCompra', ['cid' => $cotizacion->id_cotizacion, 'rid' => $id]) }}" method="post">
                    @csrf
                <h6 class="m-0 font-weight-bold text-primary">Articulos de la requisicion</h6>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th><input checked type="checkbox" id="checkTodos" /> Seleccionar:</th>
                                <th>Cantidad:</th>
                                <th>Unidad de medida:</th>
                                <th>Descripcion:</th>
                                <th>Precio unitario SIN IVA:</th>
                            </tr>
                        </thead>                                                    
                                <tbody id="tablaArticulos">        
                                <!-- Iterar sobre los artículos que pertenecen a la requisición -->                        
                                @foreach ($articulos as $index => $articulo)
                                    <tr>
                                        <th>
                                            <!-- Checkbox para seleccionar el artículo -->
                                            <input checked type="checkbox" class="check-articulo" name="articulos_seleccionados[]" value="{{ $articulo->id }}" onchange="calcularTotal()">
                                        </th>
                                        <th>
                                            <input type="hidden" name="articulos[{{ $articulo->id }}][id]" value="{{ $articulo->id }}">
                                            <input class="form-control cantidad" type="number" name="articulos[{{ $articulo->id }}][cantidad]" value="{{ $articulo->cantidad }}" required oninput="calcularTotal()">
                                        </th>
                                        <th><input class="form-control" type="text" name="articulos[{{ $articulo->id }}][unidad]" value="{{ $articulo->unidad }}" required></th>
                                        <th><input class="form-control" type="text" name="articulos[{{ $articulo->id }}][descripcion]" value="{{ $articulo->descripcion }}" required></th>
                                        <th>
                                            <input class="form-control precio_unitario" type="number" name="articulos[{{ $articulo->id }}][precio_unitario]" value="{{ $articulo->precio }}" step="0.01" oninput="calcularTotal()">
                                        </th>
                                    </tr>
                                @endforeach
                            </form>
                        </tbody>
                        <tfoot>
                            <!-- Fila para ingresar el descuento -->
                            <tr>
                                <th colspan="3">
                                    <a href="#" class="btn btn-primary" id="agregarFila"> +</a>
                                </th>                                
                                <th class="text-end">Descuento:</th>
                                <th>
                                    <input name="descuento" type="number" id="descuento" class="form-control" step="0.01" value="0" oninput="calcularTotal()">
                                </th>
                            </tr>
                            <!-- Fila para el total -->
                            <tr>
                                <th colspan="3"></th>
                                <th class="text-end">Total:</th>
                                <th id="total_monto">0.00</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-footer py-3">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">PROVEEDOR PARA ORDEN DE COMPRA</label>
                        <select name="Proveedor" id="proveedor" class="form-control" required>
                            <option value="" selected disabled>Selecciona el proveedor de este articulo:</option>
                            <!-- Iterar sobre los proveedores y crear una opción para cada uno -->
                            @foreach ($proveedores as $proveedor)
                                <option value="{{ $proveedor->id_proveedor }}">{{ $proveedor->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Condiciones de pago:</label>
                        <!-- Campo para seleccionar la condición de pago acordada -->
                        <select name="condiciones" id="condicionPago" class="form-control" required>
                            <option value="" selected disabled>Selecciona la condicion de pago acordada:</option>
                            <option value="Contado">Contado</option>
                            <option value="Credito">Crédito</option>
                        </select>
                    </div>
                    <!-- Campo para mostrar u ocultar los días de crédito acordados -->
                    <div id="datosBancarios" style="display: none;">
                        <label for="banco">Días de credito acordado:</label>
                        <input type="text" class="form-control" style="width: 60%" id="banco" name="dias"><br>
                    </div>
                    <!-- Campo para ingresar los datos bancarios del proveedor -->
                    <div class="form-group mt-4">
                        <label for="exampleFormControlInput1">Notas:</label>
                        <input name="Notas" type="text" class="form-control" value=""
                            placeholder="Agrega notas si necesario">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <h6>Crear formato de orden de compra</h6>
                    </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Esperar a que el DOM esté completamente cargado
        document.addEventListener('DOMContentLoaded', function() {
            // Obtener el checkbox universal
            var checkTodos = document.getElementById('checkTodos');
            // Obtener todos los checkboxes individuales
            var checkboxes = document.querySelectorAll('input[type="checkbox"][name^="articulos_seleccionados"]');

            // Iterar sobre cada checkbox y establecer el atributo "required" en el campo precio_unitario correspondiente
            checkboxes.forEach(function(checkbox) {
                var precioInput = checkbox.closest('tr').querySelector('input[name*="[precio_unitario]"]');
                precioInput.required = true;

                // Agregar un event listener para cambiar el estado del atributo "required" cuando cambia el estado del checkbox individual
                checkbox.addEventListener('change', function() {
                    toggleRequired(this);
                    updateCheckTodos();
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

            // Función para actualizar el estado del checkbox principal según el estado de los checkboxes individuales
            function updateCheckTodos() {
                var allChecked = true;
                checkboxes.forEach(function(checkbox) {
                    if (!checkbox.checked) {
                        allChecked = false;
                    }
                });
                // Actualizar el estado del checkbox universal
                checkTodos.checked = allChecked;
            }

            // Agregar un event listener para cambiar el estado del atributo "required" cuando cambia el estado del checkbox universal
            checkTodos.addEventListener('change', function(e) {
                var estado = this.checked; // true o false

                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = estado;
                    toggleRequired(checkbox);
                });
            });
        });

        // Mostrar u ocultar campo de días de crédito según la condición de pago seleccionada
        document.getElementById('condicionPago').addEventListener('change', function() {
            // Obtener el valor seleccionado
            var valor = this.value;
            var datosBancarios = document.getElementById('datosBancarios');

            // Mostrar u ocultar el campo de datos bancarios según la condición de pago
            if (valor == 'Credito') {
                datosBancarios.style.display = 'block';
            } else {
                datosBancarios.style.display = 'none';
            }
        });

        // Función para calcular el total basado en los artículos seleccionados
        function calcularTotal() {
        let total = 0;
        
        // Iterar sobre cada fila de la tabla para calcular el total solo de los artículos seleccionados
        document.querySelectorAll("tbody tr").forEach(row => {
            let checkbox = row.querySelector(".check-articulo");
            // Verificar si el checkbox está marcado
            if (checkbox && checkbox.checked) { 
                // Obtener cantidad y precio unitario de los inputs correspondientes
                let cantidad = parseFloat(row.querySelector(".cantidad")?.value) || 0;
                let precio = parseFloat(row.querySelector(".precio_unitario")?.value) || 0;
                total += cantidad * precio;
            }
        });

        // Obtener descuento
        let descuento = parseFloat(document.getElementById("descuento").value) || 0;
        let totalConDescuento = total - descuento;

        // Evitar que el total sea negativo
        if (totalConDescuento < 0) {
            totalConDescuento = 0;
        }

        // Actualizar el total en la vista
        document.getElementById("total_monto").textContent = totalConDescuento.toFixed(2);
    }

    // Ejecutar el cálculo cuando la página cargue
    document.addEventListener("DOMContentLoaded", calcularTotal);


    document.getElementById('agregarFila').addEventListener('click', function(e) {
        e.preventDefault();

        const tbody = document.getElementById('tablaArticulos');
        const idUnico = 'nuevo_' + Date.now(); // Para evitar conflictos con los ids originales

        const nuevaFila = document.createElement('tr');
        nuevaFila.innerHTML = `
        <th>
            <input type="checkbox" class="check-articulo" name="articulos_seleccionados[]" value="${idUnico}" onchange="calcularTotal()" checked>
            <button type="button" class="btn btn-danger btn-sm mt-1 eliminar-fila">Eliminar</button>
        </th>
        <th>
            <input type="hidden" name="articulos[${idUnico}][id]" value="${idUnico}">
            <input class="form-control cantidad" type="number" name="articulos[${idUnico}][cantidad]" value="1" required oninput="calcularTotal()">
        </th>
        <th><input class="form-control" type="text" name="articulos[${idUnico}][unidad]" value="Servicios" required></th>
        <th>
            <input class="form-control" type="text" name="articulos[${idUnico}][descripcion]" value="" required>            
        </th>
        <th>
            <input class="form-control precio_unitario" type="number" name="articulos[${idUnico}][precio_unitario]" value="0" step="0.01" oninput="calcularTotal()">
        </th>
    `;
        tbody.appendChild(nuevaFila);
    });

        document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('eliminar-fila')) {
            const fila = e.target.closest('tr');
            if (fila) fila.remove();
            calcularTotal(); // opcional si quieres actualizar el total al eliminar
        }
    });

    </script>
@endsection
