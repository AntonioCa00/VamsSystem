@extends('plantillaAdm')

@section('Contenido')
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
                    <form action="{{ route('createCorte') }}" method="post">
                        @csrf
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center">
                                    <th><input checked type="checkbox" id="checkTodos" /> Seleccionar:</th>
                                    <th class="col-1">N° requisicion:</th>
                                    <th>Solicitante:</th>
                                    <th>Departamento:</th>
                                    <th>Archivo:</th>
                                    <th>Creado:</th>
                                    <th>Opciones:</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($corte as $req)
                                    @if (!empty($req->urgencia))
                                        <tr class="text-center text-danger">
                                        @else
                                        <tr class="text-center">
                                    @endif
                                    <td>
                                        <input type="checkbox"
                                            name="requisiciones[{{ $req->id_requisicion }}][seleccionado]" value="1"
                                            checked>
                                        <input type="hidden" name="requisiciones[{{ $req->id_requisicion }}][id]"
                                            value="{{ $req->id_requisicion }}">
                                    </td>
                                    <td>{{ $req->id_requisicion }}</td>
                                    <td>{{ $req->nombres }} {{ $req->apellidoP }}</td>
                                    <td>{{ $req->departamento }}</td>
                                    <td class="text-center">
                                        <a href="{{ asset($req->pdf) }}" target="_blank">
                                            <img class="imagen-container" src="{{ asset('img/req.jpg') }}" alt="Abrir PDF">
                                        </a>
                                    </td>
                                    <td>{{ $req->created_at }}</td>
                                    <td><a href="{{ route('editarArtComp', $req->id_requisicion) }}"
                                            class="btn btn-success">Editar</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <button type="submit" value="Corte" name="action" class="btn btn-primary">Procesar
                                Requisiciones</button>
                            <div class="form-group d-flex align-items-center">
                                <button type="submit" name="action" value="Urgencia" class="btn btn-danger">
                                    <img src="{{ asset('img/urgencia.png') }}">Procesar urgencias
                                </button>
                            </div>
                        </div>
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
