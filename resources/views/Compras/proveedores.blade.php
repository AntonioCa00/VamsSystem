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
                            <th>Detalles:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($proveedores as $proveedor)
                        <tr>
                            <th>{{$proveedor->nombre}}</th>
                            {{-- <th>
                                <a href="{{ asset($proveedor->CIF) }}" target="_blank">
                                    <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                </a>
                            </th> --}}
                            <th>
                                <a href="#" data-toggle="modal" data-target="#detalles{{$proveedor->id_proveedor}}">
                                    <img src="{{ asset('img/detalles.png') }}" alt="Abrir detalles">
                                </a>
                                <!-- Modal detalles-->
                                <div class="modal fade" id="detalles{{$proveedor->id_proveedor}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                    aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Información detallada del proveedor</h5>
                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">X</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body modal-body-scrollable">
                                                    <div class="form-group">
                                                        <label for="exampleFormControlInput1">Nombre y/o razón social de la empresa:</span></label>
                                                        <h6>{{$proveedor->nombre}}</h6>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleFormControlInput1">Regimen fiscal:</span></label>
                                                        <h6>{{$proveedor->regimen_fiscal}}</h6>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleFormControlInput1">Sobrenombre:</span></label>
                                                        <h6>{{$proveedor->sobrenombre}}</h6>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleFormControlInput1">Telefono:</label>
                                                        <h6>{{$proveedor->telefono}}</h6>
                                                    </div>
                                                    @if (!empty($proveedor->telefono2))
                                                        <div class="form-group">
                                                            <label for="exampleFormControlInput1">Telefono secundario:</label>
                                                            <h6>{{$proveedor->telefono2}}</h6>
                                                        </div>
                                                    @endif
                                                    <div class="form-group">
                                                        <label for="exampleFormControlInput1">Nombre del contacto:</label>
                                                        <h6>{{$proveedor->contacto}}</h6>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleFormControlInput1">Dirección:</label>
                                                        <h6>{{$proveedor->direccion}}</h6>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleFormControlInput1">Domicilio fiscal:</span></label>
                                                        <h6>{{$proveedor->domicilio}}</h6>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleFormControlInput1">RFC:</span></label>
                                                        <h6>{{$proveedor->rfc}}</h6>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleFormControlInput1">Correo:</span></label>
                                                        <h6>{{$proveedor->correo}}</h6>
                                                    </div>
                                                    @if(empty($proveedor->CIF))
                                                        <div class="form-group">
                                                            <label for="exampleFormControlInput1">CIF en formato PDF:</label>
                                                            <a href="#">
                                                                <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                                            </a>
                                                        </div>
                                                    @else 
                                                        <div class="form-group">
                                                            <label for="exampleFormControlInput1">CIF en formato PDF:</label>
                                                            <a href="{{ asset($proveedor->CIF) }}" target="_blank">
                                                                <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                                            </a>
                                                        </div>
                                                    @endif                                                      
                                                    <h4 class="text-center text-primary">Datos  bancarios</h4>
                                                    @if (!empty($proveedor->banco) && !empty($proveedor->n_cuenta) && !empty($proveedor->n_cuenta_clabe))
                                                        <div class="form-group">
                                                            <label for="exampleFormControlInput1">Banco:</span></label>
                                                            <h6>{{$proveedor->banco}}</h6>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="exampleFormControlInput1">Número de cuenta:</span></label>
                                                            <h6>{{$proveedor->n_cuenta}}</h6>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="exampleFormControlInput1">Número de cuenta clabe:</span></label>
                                                            <h6>{{$proveedor->n_cuenta_clabe}}</h6>
                                                        </div>

                                                        @if(empty($proveedor->estado_cuenta))
                                                            <div class="form-group">
                                                                <label for="exampleFormControlInput1">Caratula de estado de cuenta:</span></label>
                                                                <a href="#">
                                                                    <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                                                </a>
                                                            </div>
                                                        @else 
                                                            <div class="form-group">
                                                                <label for="exampleFormControlInput1">Caratula de estado de cuenta:</span></label>
                                                                <a href="{{ asset($proveedor->estado_cuenta) }}" target="_blank">
                                                                    <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                                                </a>
                                                            </div>
                                                        @endif                                                        
                                                    @else
                                                        <div class="form-group">
                                                            <label for="exampleFormControlInput1">NO TIENE DATOS BANCARIOS REGISTRADOS</span></label>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="{{route('editProveedor',$proveedor->id_proveedor)}}" class="btn btn-success">Actualizar información</a>
                                                    <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#eliminarProv{{$proveedor->id_proveedor}}">
                                                        Eliminar
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
@endsection
