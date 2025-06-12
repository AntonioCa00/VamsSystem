@extends('plantillaAdm')

<!-- Mensaje de éxito al agregar un servicio -->
@section('Contenido')
    @if (session()->has('servicio'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Se ha agregado el servicio!',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">PAGOS</h1>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Datos de registro</h6>
                <div class="form-group d-flex align-items-center">
                    <a href="" class="btn btn-secondary" href="#" data-toggle="modal" data-target="#Servicio">
                        Registrar Servicio nuevo
                    </a>
                    <div class="modal fade" id="Servicio" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Crear servicio nuevo</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">X</span>
                                    </button>
                                </div>                        
                                <div class="modal-body">
                                    <form action="{{ route('createServicioC') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Nombre del servicio:</label>
                                            <input name="nombre" type="text" class="form-control"
                                                placeholder="Nombre del servicio a registrar" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Proveedor:</label>
                                            <select name="proveedor" class="form-control" id="" required>
                                                <option value="" disabled selected>Selecciona un proveedor...</option>
                                                <!-- Iterar sobre los proveedores y crear una opción para cada uno -->
                                                @foreach ($proveedores as $proveedor)
                                                    <option value="{{ $proveedor->id_proveedor }}">{{ $proveedor->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Registrar servicio</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{route('createPagoC')}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Servicio:</label>
                        <select name="servicio" id="" class="form-control" required>
                            <option value="" disabled selected>Selecciona el servicio que se va a pagar...</option>
                            @foreach ($servicios as $servicio)
                                <option value="{{ $servicio->id_servicio }}">{{ $servicio->nombre_servicio }} -
                                    {{ $servicio->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Importe a pagar:</label>
                        <!-- Campo para ingresar el importe a pagar con validación de dos decimales -->
                        <input name="importe" type="number" class="form-control" placeholder="Importe a pagar" required
                            step="0.01" pattern="^\d+(\.\d{2})?$"
                            title="El importe debe ser un número con dos decimales. Ejemplo: 123.45">
                    </div>
            </div>
            <div class="card-footer py-3">
                <div class="form-group">
                    <label for="exampleFormControlInput1">Notas:</label>
                    <input name="Notas" type="text" class="form-control" placeholder="Agrega notas si necesario">
                </div>
                <button type="submit" class="btn btn-primary">Crear orden de pago</button>
            </div>
            </form>
        </div>
    </div>
@endsection
