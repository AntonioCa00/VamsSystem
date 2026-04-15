@extends('plantillaSol')

@section('contenido')
    @if (session()->has('pago'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Se ha registrado su orden de pago!',
                timer: 1000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session()->has('editado'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Orden de pago Editada!',
                timer: 1000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session()->has('eliminado'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Orden de pago Eliminada!',
                timer: 1000,
                showConfirmButton: false
            });
        </script>
    @endif

    <style>
        .modal-body-scrollable {
            max-height: 450px;
            overflow-y: auto;
        }
    </style>

    <div class="container-fluid">

        <h1 class="h3 mb-2 text-gray-800">PAGOS FIJOS</h1>

        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">

                <a class="btn btn-primary" href="{{ route('crearPagos') }}">Crear solicitud de pago</a>

                <button class="btn btn-warning" data-toggle="modal" data-target="#modalServicios">
                    Consultar servicios
                </button>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered" id="dataTable" width="100%">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Servicio</th>
                                <th>Estado</th>
                                <th>Importe</th>
                                <th>Proveedor</th>
                                <th>Orden Pago</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($pagos as $pago)
                                <tr>
                                    <td>{{ $pago->id_pago }}</td>
                                    <td>{{ $pago->nombre_servicio }}</td>

                                    <td class="{{ $pago->estado == 'Pagado' ? 'text-success font-weight-bold' : '' }}">
                                        {{ $pago->estado }}
                                    </td>

                                    <td>${{ $pago->costo_total }}</td>
                                    <td>{{ $pago->nombre }}</td>

                                    <td class="text-center">
                                        <a href="{{ asset($pago->pdf) }}" target="_blank">
                                            <img src="{{ asset('img/pago.jpg') }}" style="width:40px">
                                        </a>
                                    </td>

                                    <td>
                                        @if ($pago->estado !== 'Pagado')
                                            <button class="btn btn-success btn-editar-pago" data-toggle="modal"
                                                data-target="#modalEditarPago" data-id="{{ $pago->id_pago }}"
                                                data-servicio="{{ $pago->id_servicio }}"
                                                data-importe="{{ $pago->costo_total }}" data-notas="{{ $pago->notas }}">
                                                Editar
                                            </button>

                                            <button class="btn btn-danger btn-eliminar-pago" data-toggle="modal"
                                                data-target="#modalEliminarPago" data-id="{{ $pago->id_pago }}">
                                                Eliminar
                                            </button>
                                        @else
                                            @if (empty($pago->comprobante_pago))
                                                Sin comprobante
                                            @else
                                                <a href="{{ asset($pago->comprobante_pago) }}" target="_blank">
                                                    Comprobante pago
                                                </a>
                                            @endif
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                </div>
            </div>
        </div>
    </div>

    <!-- ================= MODAL SERVICIOS ================= -->

    <div class="modal fade" id="modalServicios">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5>Servicios Registrados</h5>
                    <button class="close" data-dismiss="modal">X</button>
                </div>

                <div class="modal-body modal-body-scrollable">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Servicio</th>
                                <th>Proveedor</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($servicios as $servicio)
                                <tr>
                                    <td>{{ $servicio->nombre_servicio }}</td>
                                    <td>{{ $servicio->nombre }}</td>

                                    <td>

                                        <button class="btn btn-success btn-editar-servicio" data-toggle="modal"
                                            data-target="#modalEditarServicio" data-id="{{ $servicio->id_servicio }}"
                                            data-nombre="{{ $servicio->nombre_servicio }}"
                                            data-proveedor="{{ $servicio->id_proveedor }}">
                                            Editar
                                        </button>

                                        <button class="btn btn-danger btn-eliminar-servicio" data-toggle="modal"
                                            data-target="#modalEliminarServicio" data-id="{{ $servicio->id_servicio }}">
                                            Eliminar
                                        </button>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                </div>
            </div>
        </div>
    </div>

    <!-- ================= MODALES ================= -->

    <!-- EDITAR SERVICIO -->
    <div class="modal fade" id="modalEditarServicio">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <form id="formEditarServicio" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5>Editar servicio</h5>
                        <button class="close" data-dismiss="modal">X</button>
                    </div>

                    <div class="modal-body">
                        <input id="editNombre" name="nombre" class="form-control mb-2" required>

                        <select id="editProveedor" name="proveedor" class="form-control" required>
                            @foreach ($proveedores as $proveedor)
                                <option value="{{ $proveedor->id_proveedor }}">{{ $proveedor->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">Guardar</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <!-- ELIMINAR SERVICIO -->
    <div class="modal fade" id="modalEliminarServicio">
        <div class="modal-dialog">
            <div class="modal-content">

                <form id="formEliminarServicio" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="modal-body">¿Eliminar servicio?</div>

                    <div class="modal-footer">
                        <button class="btn btn-danger">Eliminar</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <!-- EDITAR PAGO -->
    <div class="modal fade" id="modalEditarPago">
        <div class="modal-dialog">
            <div class="modal-content">

                <form id="formEditarPago" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-body">

                        <select id="editServicioPago" name="servicio" class="form-control mb-2">
                            @foreach ($serviciosG as $servicio)
                                <option value="{{ $servicio->id_servicio }}">
                                    {{ $servicio->nombre_servicio }} - {{ $servicio->nombre }}
                                </option>
                            @endforeach
                        </select>

                        <input id="editImporte" name="importe" type="number" step="0.01" class="form-control mb-2">
                        <input id="editNotas" name="Notas" class="form-control">

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">Actualizar</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <!-- ELIMINAR PAGO -->
    <div class="modal fade" id="modalEliminarPago">
        <div class="modal-dialog">
            <div class="modal-content">

                <form id="formEliminarPago" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="modal-body">¿Eliminar pago?</div>

                    <div class="modal-footer">
                        <button class="btn btn-danger">Eliminar</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <!-- ================= JS ================= -->

    <script>
        const urlEditServicio = "{{ route('editServicio', ':id') }}";
        const urlDeleteServicio = "{{ route('deleteServicio', ':id') }}";
        const urlEditPago = "{{ route('updatePago', ':id') }}";
        const urlDeletePago = "{{ route('deletePago', ':id') }}";

        document.querySelectorAll('.btn-editar-servicio').forEach(btn => {
            btn.onclick = function() {
                let url = urlEditServicio.replace(':id', this.dataset.id);
                formEditarServicio.action = url;

                editNombre.value = this.dataset.nombre;
                editProveedor.value = this.dataset.proveedor;
            };
        });

        document.querySelectorAll('.btn-eliminar-servicio').forEach(btn => {
            btn.onclick = function() {
                let url = urlDeleteServicio.replace(':id', this.dataset.id);
                formEliminarServicio.action = url;
            };
        });

        document.querySelectorAll('.btn-editar-pago').forEach(btn => {
            btn.onclick = function() {
                let url = urlEditPago.replace(':id', this.dataset.id);
                formEditarPago.action = url;

                editServicioPago.value = this.dataset.servicio;
                editImporte.value = this.dataset.importe;
                editNotas.value = this.dataset.notas;
            };
        });

        document.querySelectorAll('.btn-eliminar-pago').forEach(btn => {
            btn.onclick = function() {
                let url = urlDeletePago.replace(':id', this.dataset.id);
                formEliminarPago.action = url;
            };
        });
    </script>
@endsection
