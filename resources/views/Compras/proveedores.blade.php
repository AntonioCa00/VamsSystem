@extends('plantillaAdm')

@section('Contenido')

@if(session()->has('insert'))
    <script type="text/javascript">
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Proveedor registrado!',
        showConfirmButton: false,
        timer: 1000
        })
    </script>
@endif

@if(session()->has('update'))
    <script type="text/javascript">
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Proveedor actualizado!',
        showConfirmButton: false,
        timer: 1000
        })
    </script>
@endif

@if(session()->has('delete'))
    <script type="text/javascript">
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Proveedor eliminado!',
        showConfirmButton: false,
        timer: 1000
        })
    </script>
@endif

<style>
    .modal-body-scrollable {
        max-height: 450px;
        overflow-y: auto; /* Se habilita el desplazamiento vertical si el contenido excede la altura máxima */
    }
</style>

<div class="container-fluid">

    <!-- Page Heading -->
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">PROVEEDORES</h1>        
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
            <a class="btn btn-primary" href="{{route('createProveedor')}}">Registrar nuevo proveedor</a>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm" data-toggle="modal" data-target="#RegistrarProv">
                <img src="{{ asset('img/excel.png') }}" alt=""> IMPORTAR PROVEEDORES DESDE EXCEL
            </a>

            <div class="modal fade" id="RegistrarProv" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">ACTUALIZACION MASIVA DE PROVEEDORES</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">X</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('actualizarProveedores') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    {!!method_field('PUT')!!}   
                                    <div class="form-group">
                                        <label>Favor de cargar excel con datos a actualizar:</label>
                                        <input name="file" type="file" class="form-control">
                                    </div>
                                <button type="submit" class="btn btn-primary">Actualizar informacion</button>
                            </form>
                        </div>                                                
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nombre:</th>
                            <th>Caratula:</th>
                            <th>Detalles:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($proveedores as $proveedor)
                        <tr>
                            <th>{{$proveedor->nombre}}</th>
                            <th class="text-center">
                                @if (!empty($proveedor->caratula))
                                    <a href="{{ asset($proveedor->caratula) }}">
                                        <img class="imagen-container" src="{{ asset('img/caratula.png') }}" alt="Abrir PDF">
                                    </a>    
                                @endif
                            </th>
                            <th class="text-center">
                                <a class="btn btn-info btnDetalleProveedor"
                                    href="#"
                                    data-toggle="modal"
                                    data-target="#modalDetalleProveedor"

                                    data-editar="{{ route('editProveedor', $proveedor->id_proveedor) }}"
                                    data-eliminar="{{ route('deleteProveedor', $proveedor->id_proveedor) }}"

                                    data-nombre="{{ $proveedor->nombre }}"
                                    data-regimen="{{ $proveedor->regimen_fiscal }}"
                                    data-sobrenombre="{{ $proveedor->sobrenombre }}"
                                    data-telefono="{{ $proveedor->telefono }}"
                                    data-telefono2="{{ $proveedor->telefono2 }}"
                                    data-contacto="{{ $proveedor->contacto }}"
                                    data-direccion="{{ $proveedor->direccion }}"
                                    data-domicilio="{{ $proveedor->domicilio }}"
                                    data-rfc="{{ $proveedor->rfc }}"
                                    data-correo="{{ $proveedor->correo }}"

                                    data-cif="{{ $proveedor->CIF ? asset($proveedor->CIF) : '' }}"

                                    data-banco="{{ $proveedor->banco }}"
                                    data-cuenta="{{ $proveedor->n_cuenta }}"
                                    data-clabe="{{ $proveedor->n_cuenta_clabe }}"

                                    data-estadocuenta="{{ $proveedor->estado_cuenta ? asset($proveedor->estado_cuenta) : '' }}">

                                    Ver detalles
                                </a>
                            </th>
                                <!-- Modal detalles-->
                                
                                <!-- Logout Modal-->
                                <div class="modal fade" id="eliminarProv{{$proveedor->id_proveedor}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">¿Ha tomado una decisión?</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">X</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">Selecciona confirmar para eliminar este proveedor</div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">cancelar</button>
                                                <form action="{{route('deleteProveedor',$proveedor->id_proveedor)}}" method="post">
                                                    @csrf
                                                    {!!method_field('PUT')!!}
                                                    <button type="submit" class="btn btn-primary">confirmar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </th>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('Compras.modals.modalProveedor')

<script src="{{ asset('js/compras.js') }}"></script>
@endsection
