@extends('plantillaSol')

@section('contenido')
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">CORTE SEMANAL</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Requisiciones creadas</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <form action="{{ route('crearValidacion') }}" method="post">
                        @csrf
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center">
                                    <th class="col-1"><input checked type="checkbox" id="checkTodos" /> Seleccionar:</th>
                                    <th class="col-1">N° requisicion:</th>
                                    <th class="col-2">Solicitante:</th>
                                    <th class="col-1">Departamento:</th>
                                    <th>Archivo:</th>
                                    <th>Cotizacion:</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($validaciones as $req)
                                    <tr class="text-center">
                                        <td>
                                            <input type="checkbox"
                                                name="requisiciones[{{ $req->id_requisicion }}][seleccionado]"
                                                value="1" checked>
                                            <input type="hidden" name="requisiciones[{{ $req->id_requisicion }}][id]"
                                                value="{{ $req->id_requisicion }}">
                                            <input type="hidden"
                                                name="requisiciones[{{ $req->id_requisicion }}][id_cotizacion]"
                                                value="{{ $req->id_cotizacion }}">

                                        </td>
                                        <td>{{ $req->id_requisicion }}</td>
                                        <td>{{ $req->nombres }} {{ $req->apellidoP }}</td>
                                        <td>{{ $req->departamento }}</td>
                                        <td class="text-center">
                                            <a href="{{ asset($req->pdf) }}" target="_blank">
                                                <img class="imagen-container" src="{{ asset('img/req.jpg') }}"
                                                    alt="Abrir PDF">
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ asset($req->coti) }}" target="_blank">
                                                <img class="imagen-container" src="{{ asset('img/cot.jpg') }}"
                                                    alt="Abrir PDF">
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary">Validar Requisiciones</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Obtener el checkbox universal
            var checkTodos = document.getElementById('checkTodos');
            // Obtener todos los checkboxes individuales
            var checkboxes = document.querySelectorAll('input[type="checkbox"][name^="requisiciones"]');

            // Función para actualizar el estado del checkbox principal según el estado de los checkboxes individuales
            function updateCheckTodos() {
                var allChecked = true;
                checkboxes.forEach(function(checkbox) {
                    if (!checkbox.checked) {
                        allChecked = false;
                    }
                });
                checkTodos.checked = allChecked;
            }

            // Agregar evento a cada checkbox individual para actualizar el checkbox principal al cambiar su estado
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', updateCheckTodos);
            });

            // Evento para el checkbox principal para marcar o desmarcar todos los checkboxes individuales
            checkTodos.addEventListener('change', function() {
                var isChecked = checkTodos.checked;
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = isChecked;
                });
            });
        });
    </script>
@endsection
