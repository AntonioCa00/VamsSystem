@extends('plantillaAlm')

@section('contenido')

@if(session()->has('vacio'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Por favor ingresa articulos para la salida',
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
        title: 'No hay suficiente stock para permitir la salida',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">ENTRADAS</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Registrar entradas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Requisicion inicial:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr> 
                            <th class="text-center">
                                <a href="{{ asset($refacciones[0]->pdf) }}" target="_blank">
                                    <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                </a>    
                            </th>                                              
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Id refaccion:</th>
                            <th>Nombre:</th>
                            <th>Cantidad:</th>
                            <th>Opciones:</th>  
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salida as $index => $salida)
                        <tr>
                            <th>{{ $salida['id'] }}</th>
                            <th>{{ $salida['nombre'] }}</th>
                            <th>{{ $salida['cantidad'] }}</th>
                            <th>
                                <form action="{{ route('deleteArraySal', ['index' => $index]) }}" method="post">
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

            <div class="card-body">
                <h5 class="text-center">Datos para generar la salida de articulos</h5>
                <form action="{{route('ArraySalida')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Refaccion a salir</label>
                        <select name="refaccion" id="refaccion" class="form-control" required>
                            <option value="" selected disabled>Selecciona la refacción a salir</option>
                            @foreach ($refacciones as $refaccion)                            
                                <option value="{{$refaccion->id_refaccion}}">{{$refaccion->nombre}}: {{$refaccion->marca}} {{$refaccion->modelo}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" id="opcionSeleccionada" name="nombre" value="">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Cantidad:</label>
                        <input name="cantidad" type="number" class="form-control" placeholder="Cantidad de refacciones que salen" required>
                    </div>                    
                    <button type="submit" class="btn btn-primary">Guardar articulo</button>
                </form>
            </div>
            <div class="card-footer py-3 text-center">
                <a href="{{route('createSalida',$refacciones[0]->id_requisicion)}}" class="btn btn-primary">Generar salida</a>
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