@extends('plantillaAdm')

@section('Contenido')

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Compras</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Registrar nueva compra</h6>
        </div>
        <div class="card-body">
            <h3 class="text-center">Datos de registro</h3>
            <form action="{{route('insertCompra')}}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="exampleFormControlSelect1">Solicitud:</label>
                    <select id="solicitud-id" name="solicitudId" class="form-control">
                        <option selected disabled value="">Selecciona la solicitud que requiere la compra</option>
                        <!-- Iterar sobre las solicitudes y crear una opción para cada una -->
                        @foreach ($solicitudes as $solicitud)
                            <option value="{{ $solicitud->id_solicitud }}">{{ $solicitud->descripcion }}</option>
                        @endforeach
                    
                    <input type="text" class="form-control" id="unidad-id" disabled/>
                    <input type="text" class="form-control" id="descripcion" disabled/>
                    <input type="text" class="form-control" id="refaccion" disabled />        
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Costo:</label>
                    <input name="costo" type="number" class="form-control" placeholder="Costo de la compra de la unidad" required>
                </div>     
                <div class="form-group">
                    <label for="exampleFormControlInput1">Factura:</label>
                    <input name="factura" type="text" class="form-control" placeholder="Factura de la compra" required>
                </div>

                <button type="submit" class="btn btn-primary">Registrar compra</button>
            </form>
        </div>
    </div>
</div>

<!-- jQuery para manejar el cambio de la solicitud seleccionada -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Esperar a que el documento esté listo
    $(document).ready(function() {
        // Manejar el cambio en el select de solicitudes
        $('#solicitud-id').change(function() {
            // Obtener el ID de la solicitud seleccionada
            var solicitudId = $(this).val();

            // Buscar la solicitud seleccionada en el arreglo de solicitudes
            var solicitud = {!! json_encode($solicitudes) !!}.find(function(solicitud) {
                return solicitud.id_solicitud == solicitudId;
            });

            // Si se encuentra la solicitud, actualizar los campos correspondientes
            if (solicitud) {
                $('#unidad-id').val(solicitud.unidad_id);
                $('#descripcion').val(solicitud.descripcion);
                $('#refaccion').val(solicitud.refaccion);                
            } else {
                // Si no se encuentra la solicitud, limpiar los campos
                $('#unidad-id').val('');
                $('#descripcion').val('');
                $('#refaccion').val('');
            }
        });
    });
</script>

@endsection