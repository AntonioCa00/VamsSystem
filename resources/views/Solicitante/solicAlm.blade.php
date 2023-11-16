@extends('plantillaSol')

@section('contenido')

@if(session()->has('vacio'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'No se puede crear una requisicion vacia.',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

@if(session()->has('insuficiente'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Existencia insuficiente, solicita una compra de la refaccion o una cantidad menor.',
        showConfirmButton: true,
        })
    </script> 
@endif

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">SOLICITUDES</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Datos de registro <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#Almacen" style="margin-left: 75%;">Consultar almacen</a></h6>            
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Cantidad:</th>
                            <th>Refaccion:</th>
                            <th>Opciones:</th>  
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($datosAlm as $index => $datos)
                        <tr>
                            <th>{{ $datos['cantidad'] }}</th>
                            <th>{{ $datos['nombre'] }}</th>
                            <th>
                                <form action="{{ route('eliminarElementoSolic', ['index' => $index]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-primary">Eliminar</button>
                                </form>                                
                            </th>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <form action="{{route('arraySoliAlm')}}" method="post">
                @csrf
                <div class="form-group">
                    <label for="exampleFormControlInput1">Cantidad:</label>
                    <input name="cantidad" type="number" class="form-control" placeholder="Cantidad de refacciones necesarias" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Refaccion solicitada</label>
                    <select name="refaccion" class="form-control" id="refaccion" required>
                        <option value="" selected disabled>Selecciona la refaccion que requiere:</option>
                        @foreach ($refacciones as $refaccion)                            
                            <option value="{{$refaccion->id_refaccion}}">{{$refaccion->nombre}} {{$refaccion->marca}} {{$refaccion->modelo}}</option>
                        @endforeach
                    </select>
                    <input type="hidden" id="opcionSeleccionada" name="nombre" value="">
                </div>
                <button type="submit" class="btn btn-primary">Agregar articulo</button>
            </form>
        </div>
        <div class="card-footer py-3 text-center">
            <form action="{{route('requisicionAlm')}}" method="post">
                @csrf
                <div class="form-group">
                    <label for="exampleFormControlInput1">Notas:</label>
                    <input name="Notas" type="text" class="form-control" placeholder="Agrega notas si necesario">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">UNIDAD PARA REQUISICION</label>
                    <select name="unidad" class="form-control" required>
                        <option value="" selected disabled>Selecciona la unidad que requiere la refaccion:</option>
                        @foreach ($unidades as $unidad)                            
                            <option value="{{$unidad->id_unidad}}">{{$unidad->id_unidad}}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary"><h6 >Crear formato de requisición</h6></button>
            </form>
        </div>

        {{-- Modal Consultar Almacen --}}
        <div class="modal fade" id="Almacen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Disponibilidad de almacen</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">X</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Nombre:</th>
                                        <th>Marca:</th>
                                        <th>Modelo:</th>  
                                        <th>Stock:</th>  
                                    </tr>
                                </thead>    
                                <tbody>
                                    @foreach($refacciones as $refaccion)
                                    <tr>
                                        <th>{{ $refaccion->nombre}}</th>
                                        <th>{{ $refaccion->marca}}</th>
                                        <th>{{ $refaccion->modelo}}</th>
                                        <th>{{ $refaccion->stock}}</th>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">cancelar</button>                                             
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>

<script>
    // Obtén el elemento select y el campo oculto
    var selectElement = document.getElementById('refaccion');
    var hiddenInput = document.getElementById('opcionSeleccionada');

    // Agrega un evento de cambio al elemento select
    selectElement.addEventListener('change', function() {
    // Obtiene el texto del option seleccionado
    var selectedText = selectElement.options[selectElement.selectedIndex].text;

    // Actualiza el valor del campo oculto con el texto seleccionado
    hiddenInput.value = selectedText;
});

</script>

@endsection