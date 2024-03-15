@extends('plantillaSol')

@section('contenido')

@if(session()->has('pago'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Se ha registrado su orden de pago!',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

@if(session()->has('editado'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Orden de pago Editada!',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

@if(session()->has('servEditado'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Se ha actualizado el servicio!',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

@if(session()->has('servDelete'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Se ha eliminado el servicio!',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

@if(session()->has('eliminado'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Orden de pago Eliminada!',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">PAGOS FIJOS</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <a class="m-0 btn btn-primary" href="{{route('crearPagos')}}">Crear solicitud de pago</a>            
            <div class="form-group d-flex align-items-center">
                <a class="btn btn-warning" data-toggle="modal" data-target="#Servicios">
                    Consultar servicios 
                </a>
                <div class="modal fade" id="Servicios" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Servicios Registrados</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">X</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group align-items-center">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Servicio:</th>
                                                <th>Proveedor:</th>
                                                <th>Opciones:</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($servicios as $servicio)
                                            <tr>                                       
                                                <th>{{$servicio->nombre_servicio}}</th>
                                                <th>{{$servicio->nombre}}</th>
                                                <th>
                                                    <!-- Asumiendo que cada botón de editar dentro de tu tabla tiene una clase `btn-editar` -->
                                                    <a class="btn btn-success btn-editar" href="#" data-toggle="modal" data-target="#editarServ{{$servicio->id_servicio}}">
                                                        Editar
                                                    </a>
                                                    <!-- Logout Modal-->
                                                    <div class="modal fade" id="editarServ{{$servicio->id_servicio}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                    aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Editar servicio</h5>
                                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">X</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form action="{{route('editServicio',$servicio->id_servicio)}}" method="POST">
                                                                        @csrf
                                                                        {!!method_field('PUT')!!}   
                                                                        <div class="form-group">
                                                                            <label for="exampleFormControlInput1">Nombre del servicio:</label>
                                                                            <input name="nombre" value="{{$servicio->nombre_servicio}}" type="text" class="form-control"
                                                                                placeholder="Nombre del servicio a registrar" required>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="exampleFormControlInput1">Proveedor:</label>
                                                                            <select name="proveedor" class="form-control" id="" required>
                                                                                <option value="{{$servicio->id_proveedor}}">{{$servicio->nombre}}</option>
                                                                                <option value="" disabled>Selecciona un proveedor...</option>
                                                                                @foreach ($proveedores as $proveedor)
                                                                                    <option value="{{ $proveedor->id_proveedor }}">{{ $proveedor->nombre }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <button type="submit" class="btn btn-primary">Editar servicio</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <a class="btn btn-danger" href="#" data-toggle="modal" data-target="#eliminarServ{{$servicio->id_servicio}}">
                                                        Eliminar
                                                    </a>
                                                    <!-- Logout Modal-->
                                                    <div class="modal fade" id="eliminarServ{{$servicio->id_servicio}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                    aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">¿Ha tomado una decisión?</h5>
                                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">X</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">Selecciona confirmar para eliminar este servicio</div>
                                                                <div class="modal-footer">
                                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">cancelar</button>
                                                                    <form action="{{route('deleteServicio',$servicio->id_servicio)}}" method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
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
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Folio:</th>
                            <th>Servicio:</th>   
                            <th>Estado:</th>                                                 
                            <th>Importe:</th>
                            <th>Proveedor:</th>
                            <th>Orden Pago:</th>
                            <th>Opciones:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pagos as $pago)                            
                            <tr>
                                <th>{{$pago->id_pago}}</th>
                                <th>{{$pago->nombre_servicio}}</th>
                                @if ($pago->estado === "Pagado")
                                    <th class="font-weight-bold text-success">{{$pago->estado}}</th>
                                @else
                                    <th>{{$pago->estado}}</th>
                                @endif
                                
                                <th>${{$pago->costo_total}}</th>
                                <th>{{$pago->nombre}}</th>
                                <th class="text-center">
                                    <a href="{{ asset($pago->pdf) }}" target="_blank">
                                        <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                    </a>
                                </th>
                                <th>
                                    @if ($pago->estado === "Pagado")
                                        @if(empty($pago->comprobante_pago))
                                            Sin comprobante
                                        @else 
                                            <a href="{{ asset($pago->comprobante_pago)}}" target="_blank">
                                                Comprobante pago
                                            </a>
                                        @endif                      
                                    @else
                                        <a class="btn btn-success" href="#" data-toggle="modal" data-target="#EditarPago{{$pago->id_pago}}">
                                            Editar
                                        </a>
                                        <!-- Logout Modal-->
                                        <div class="modal fade" id="EditarPago{{$pago->id_pago}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                        aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Editar pago</h5>
                                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">X</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('updatePago',$pago->id_pago)}}" method="post">
                                                            @csrf
                                                            {!!method_field('PUT')!!}   
                                                            <div class="form-group">
                                                                <label for="exampleFormControlInput1">Servicio:</label>                                                            
                                                                <select name="servicio" id="" class="form-control" required>                                                                
                                                                    <option value="{{$pago->id_servicio}}" selected>{{$pago->nombre_servicio}} - {{$pago->nombre}}</option>
                                                                    <option value="" disabled >Selecciona el servicio que se va a pagar...</option>                                                                
                                                                    @foreach ($servicios as $servicio)
                                                                        <option value="{{ $servicio->id_servicio }}">{{ $servicio->nombre_servicio }} -
                                                                            {{ $servicio->nombre }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="exampleFormControlInput1">Importe a pagar:</label>
                                                                <input value="{{$pago->costo_total}}" name="importe" type="number" class="form-control" placeholder="Importe a pagar" required
                                                                    step="0.01" pattern="^\d+(\.\d{2})?$"
                                                                    title="El importe debe ser un número con dos decimales. Ejemplo: 123.45">
                                                            </div>
                                                    </div>
                                                    <div class="card-footer py-3">
                                                        <div class="form-group">
                                                            <label for="exampleFormControlInput1">Notas:</label>
                                                            <input value="{{$pago->notas}}" name="Notas" type="text" class="form-control" placeholder="Agrega notas si necesario">
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Actualizar orden de pago</button>
                                                    </div>
                                                    </form>
                                                    </div>                                                
                                                </div>
                                            </div>
                                        </div>
                                        <a class="btn btn-danger" href="#" data-toggle="modal" data-target="#EliminarPago{{$pago->id_pago}}">
                                            Eliminar
                                        </a>
                                        <!-- Logout Modal-->
                                        <div class="modal fade" id="EliminarPago{{$pago->id_pago}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                        aria-hidden="true">
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
                                                        <form action="{{route('deletePago',$pago->id_pago)}}" method="POST">
                                                            @csrf
                                                            {!!method_field('DELETE')!!}    
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
@endsection