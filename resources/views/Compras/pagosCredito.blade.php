@extends('plantillaAdm')

@section('Contenido')
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">PAGOS A CREDITO</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">                
                <h6 class="m-0 font-weight-bold text-primary">Pagos a crédito registrados</h6>                
            </div>

            <!--Tarjeta de pagos-->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Folio:</th>
                                <th>Servicio:</th>
                                <th>Fecha Pago:</th>
                                <th>Estado:</th>
                                <th>Importe:</th>
                                <th>Proveedor:</th>
                                <th>Orden Pago:</th>
                                <th>Opciones:</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--Iterar sobre los pagos y crear una fila para cada uno-->
                            @foreach ($pagos as $pago)
                                <tr>
                                    <th>{{ $pago->id_pago }}</th>
                                    <th>{{ $pago->nombre_servicio }}</th>
                                    <!-- Verificar el estado del pago y aplicar estilos según corresponda -->
                                    @if ($pago->estado === 'Pagado')
                                        <th class="font-weight-bold text-success">{{ $pago->estado }}</th>
                                    @else
                                        <th class="text-success">
                                            {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d-m-Y') }}</th>
                                    @endif
                                    <th>{{ $pago->estado }}</th>
                                    <th>${{ $pago->costo_total }}</th>
                                    <th>{{ $pago->nombre }}</th>  
                                    <th class="text-center">
                                        <a href="{{ asset($pago->pdf) }}" target="_blank">
                                            <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                        </a>
                                    </th>
                                    <th>
                                        @if ($pago->estado === 'Pagado')
                                            @if (empty($pago->comprobante_pago))
                                                Sin comprobante
                                            @else
                                                <a href="{{ asset($pago->comprobante_pago) }}" target="_blank">
                                                    Comprobante pago
                                                </a>
                                            @endif
                                        @else
                                            <a class="btn btn-success" href="#" data-toggle="modal"
                                                data-target="#EditarPago{{ $pago->id_pago }}">
                                                Editar
                                            </a>
                                            <!-- Logout Modal-->
                                            <div class="modal fade" id="EditarPago{{ $pago->id_pago }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Editar pago
                                                            </h5>
                                                            <button class="close" type="button" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">X</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('updatePagoC', $pago->id_pago) }}"
                                                                method="post">
                                                                @csrf
                                                                {!! method_field('PUT') !!}
                                                                <div class="form-group">
                                                                    <label for="exampleFormControlInput1">Servicio:</label>
                                                                    <select name="servicio" id=""
                                                                        class="form-control" required>
                                                                        <option value="{{ $pago->id_servicio }}" selected>
                                                                            {{ $pago->nombre_servicio }} -
                                                                            {{ $pago->nombre }}</option>
                                                                        <option value="" disabled>Selecciona el
                                                                            servicio que se va a pagar...</option>
                                                                        @foreach ($servicios as $servicio)
                                                                            <option value="{{ $servicio->id_servicio }}">
                                                                                {{ $servicio->nombre_servicio }} -
                                                                                {{ $servicio->nombre }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="exampleFormControlInput1">Importe a
                                                                        pagar:</label>
                                                                    <input value="{{ $pago->costo_total }}"
                                                                        name="importe" type="number"
                                                                        class="form-control" placeholder="Importe a pagar"
                                                                        required step="0.01" pattern="^\d+(\.\d{2})?$"
                                                                        title="El importe debe ser un número con dos decimales. Ejemplo: 123.45">
                                                                </div>
                                                                <div class="form-group">
                                                                    <!-- Columna izquierda -->
                                                                    <div class="col-md-6">
                                                                        <label for="exampleFormControlInput1">Condiciones
                                                                            de pago:</label>
                                                                        <!-- Campo para seleccionar la condición de pago acordada -->
                                                                        <select name="condiciones" id="condicionPago"
                                                                            class="form-control" required>
                                                                            <option value="" selected disabled>
                                                                                Selecciona la condicion de pago acordada:
                                                                            </option>
                                                                            <option value="Contado"
                                                                                {{ is_null($pago->fecha_pago) ? 'selected' : '' }}>
                                                                                Contado
                                                                            </option>

                                                                            <option value="Credito"
                                                                                {{ !is_null($pago->fecha_pago) ? 'selected' : '' }}>
                                                                                Crédito
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                    <!-- Columna derecha -->
                                                                    <div class="col-md-6" id="datosBancarios"
                                                                        style="display: none;">
                                                                        <label for="banco">Días de credito
                                                                            acordado:</label>
                                                                        <input value="{{ $pago->fecha_pago }}" type="date" class="form-control"
                                                                            id="banco" name="dia_credito"
                                                                            placeholder="Ingresa los días de crédito acordados">
                                                                    </div>
                                                                </div>
                                                        </div>
                                                        <div class="card-footer py-3">
                                                            <div class="form-group">
                                                                <label for="exampleFormControlInput1">Notas:</label>
                                                                <input value="{{ $pago->notas }}" name="Notas"
                                                                    type="text" class="form-control"
                                                                    placeholder="Agrega notas si necesario">
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">Actualizar
                                                                orden de pago</button>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                </div>
                <a class="btn btn-danger" href="#" data-toggle="modal"
                    data-target="#EliminarPago{{ $pago->id_pago }}">
                    Eliminar
                </a>
                <!-- Logout Modal-->
                <div class="modal fade" id="EliminarPago{{ $pago->id_pago }}" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">¿Ha tomado una decisión?</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">X</span>
                                </button>
                            </div>
                            <div class="modal-body">Selecciona confirmar para eliminar esta orden de pago</div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">cancelar</button>
                                <form action="{{ route('deletePagoC', $pago->id_pago) }}" method="POST">
                                    @csrf
                                    {!! method_field('DELETE') !!}
                                    <button type="submit" class="btn btn-primary">confirmar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                </th>
                </tr>
                @endforeach
                </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>

    <script>
        function validarCondicionPago() {

            // Select
            var select = document.getElementById('condicionPago');

            // Valor seleccionado
            var valor = select.value;

            // Contenedor
            var datosBancarios = document.getElementById('datosBancarios');

            // Input interno
            var inputDias = datosBancarios.querySelector('input');

            // Si es crédito
            if (valor === 'Credito') {

                datosBancarios.style.display = 'block';

                inputDias.required = true;

            } else {

                datosBancarios.style.display = 'none';

                inputDias.value = '';

                inputDias.required = false;
            }
        }

        // Detectar cambio manual
        document.getElementById('condicionPago')
            .addEventListener('change', validarCondicionPago);

        // Ejecutar al cargar la página
        document.addEventListener('DOMContentLoaded', validarCondicionPago);
    </script>
@endsection
