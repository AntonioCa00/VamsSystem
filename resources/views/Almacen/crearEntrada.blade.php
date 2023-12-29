@extends('plantillaAlm')

@section('contenido')

@if(session()->has('error'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'No se ha encontrado ningun archivo',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

@if(session()->has('duplicado'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Esa clave ya existe, por favor ingresa una diferente',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

@if(session()->has('vacio'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Por favor ingresa articulos para la entrada',
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
                            <th>ORDEN DE COMPRA GENERADA:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr> 
                            <th class="text-center">
                                <a href="{{ asset($orden->comPDF) }}" target="_blank">
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
                            <th>Clave:</th>
                            <th>Ubicacion:</th>
                            <th>Descripcion:</th>
                            <th>Medida:</th>
                            <th>Marca:</th>
                            <th>Cantidad:</th>
                            <th>Opciones:</th>  
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entrada as $index => $entrada)
                        <tr>
                            <th>{{ $entrada['clave'] }}</th>
                            <th>{{ $entrada['ubicacion'] }}</th>
                            <th>{{ $entrada['descripcion'] }}</th>
                            <th>{{ $entrada['medida'] }}</th>
                            <th>{{ $entrada['marca'] }}</th>
                            <th>{{ $entrada['cantidad'] }}</th>
                            <th>
                                <form action="{{ route('deleteArrayRef', ['index' => $index]) }}" method="post">
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
                <h5 class="text-center">Datos para generar la entrada de articulos</h5>
                <form action="{{route('arrayEntrada')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Clave:</label>
                        <input id="nombre" name="clave" type="text" class="form-control" placeholder="Clave de la refacción" required>
                        <div id="suggestions"></div>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Ubicacion:</label>
                        <input id="marca" name="ubicacion" type="text" class="form-control" placeholder="Ubicacion fisica de la refacción" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Descripcion:</label>
                        <input id="anio" name="descripcion" type="text" class="form-control" placeholder="Descripcion detallada de la refacción" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Medida:</label>
                        <input id="modelo" name="medida" type="text" class="form-control" placeholder="Medida de la refacción" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Marca:</label>
                        <input id="descripcion" name="marca" type="text" class="form-control" placeholder="Marca de la refacción" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Cantidad:</label>
                        <input name="cantidad" type="number" class="form-control" placeholder="Cantidad de refacciones que entran" required>
                    </div>                    
                    <button type="submit" class="btn btn-primary">Guardar articulo</button>
                </form>
            </div>
            <div class="card-footer py-3 text-center">
                <form action="{{route('entradaAlm',$id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Orden de compra correspondiente a la entrada:</label>
                        <input name="archivo" type="file" class="form-control" placeholder="Agrega notas si necesario" required>
                    </div>   
                    <label for="exampleFormControlInput1">¿La entrada esta completa?</label>
                    <div class="form-check">                        
                        <input class="form-check-input" type="radio" name="entrada" id="exampleRadios1" value="Completo" required>
                        <label class="form-check-label" for="exampleRadios1">
                          Entrada completa
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="entrada" id="exampleRadios2" value="Pendiente">
                        <label class="form-check-label" for="exampleRadios2">
                          Articulos pendientes
                        </label>
                    </div>                
                    <button type="submit" class="btn btn-primary">Enviar articulos a almacen</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    const inputName = document.getElementById('nombre');
const inputMarca = document.getElementById('marca');
const inputAnio = document.getElementById('anio');
const suggestionsDiv = document.getElementById('suggestions');

const refacciones = @json($refacciones);

inputName.addEventListener('input', function() {
    const userInput = inputName.value.toLowerCase();
    const filteredSuggestions = refacciones.filter(item => item.nombre.toLowerCase().includes(userInput));
    
    suggestionsDiv.innerHTML = '';
    filteredSuggestions.forEach(suggestion => {
        const suggestionItem = document.createElement('div');
        suggestionItem.textContent = suggestion.nombre;
        suggestionItem.addEventListener('click', function() {
            inputName.value = suggestion.nombre;
            inputMarca.value = suggestion.marca; // Assuming 'marca' is the property you want for inputAge
            inputAnio.value = suggestion.anio; // Assuming 'anio' is the property you want for inputEmail
            suggestionsDiv.style.display = 'none';
        });
        suggestionsDiv.appendChild(suggestionItem);
    });

    if (filteredSuggestions.length > 0) {
        suggestionsDiv.style.display = 'block';
    } else {
        suggestionsDiv.style.display = 'none';
    }
});

// Ocultar sugerencias cuando se hace clic fuera del cuadro de entrada
document.addEventListener('click', function(event) {
    if (event.target !== inputName && event.target !== suggestionsDiv) {
        suggestionsDiv.style.display = 'none';
    }
});

</script>

@endsection