@extends('plantillaAdm')

@section('Contenido')

<!-- Mensaje de error para mostrar que no se ha cargado el PDF correctamente -->
@if(session()->has('error'))
    <script type="text/javascript">
        Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'No se cargó pdf',
        showConfirmButton: false,
        timer: 1000
        })
    </script>
@endif

<!-- Mensaje de error para mostrar que se ha eliminado el archivo de cotización correctamente -->
@if(session()->has('eliminado'))
    <script type="text/javascript">
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Se eliminó la cotizacion',
        showConfirmButton: false,
        timer: 1000
        })
    </script>
@endif

<!-- Mensaje de éxito para mostrar que se ha registrado la cotización correctamente -->
@if(session()->has('cotizacion'))
    <script type="text/javascript">
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Se registro la cotización',
        showConfirmButton: false,
        timer: 1000
        })
    </script>
@endif

<!-- Mensaje de éxito para mostrar que se ha actualizado la cotización correctamente -->
@if(session()->has('actualizacion'))
    <script type="text/javascript">
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Se registro la nueva cotización',
        showConfirmButton: false,
        timer: 1000
        })
    </script>
@endif

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">COTIZACIONES</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Cotizaciones creadas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-center">
                            <th>No. cotizacion:</th>
                            <th>Requisicion:</th>
                            <th>Archivo:</th>
                            <th>Opciones:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Ciclo reiterativo para mostrar las cotizaciones -->
                        @foreach ($cotizaciones as $cotizacion)
                        <tr class="text-center">
                        <th class="text-center">{{ $loop->iteration }}</th> <!-- Muestra el número de cotización -->
                            <th>
                                <a href="{{ asset($cotizacion->reqPDF) }}" target="_blank"> <!-- Enlace para abrir el PDF de la requisición -->
                                    <img class="imagen-container"  src="{{ asset('img/req.jpg') }}" alt="Abrir PDF">
                                </a>
                            </th>
                            <th>
                                <a href="{{ asset($cotizacion->cotPDF) }}" target="_blank"><!-- Enlace para abrir el PDF de la cotización -->
                                    <img class="imagen-container"  src="{{ asset('img/cot.jpg') }}" alt="Abrir PDF">
                                </a>
                            </th>
                            <th>
                                <!-- Botón para eliminar la cotización -->
                                <form action="{{ route('deleteCotiza', ['id' => $cotizacion->id_cotizacion, 'rid' => $cotizacion->id_requisicion]) }}" method="POST">
                                    @csrf
                                    {!!method_field('DELETE')!!}
                                    <button type="submit" class="btn btn-primary">Eliminar</button>
                                </form>
                            </th>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-body">
                <h5 class="text-center">Datos de registro</h5>
                <form action="{{route('insertCotiza')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Campo oculto para enviar el ID de la requisición -->
                    <input type="hidden" name="requisicion" value="{{$id}}">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Archivo de cotización:</label>
                        <input name="archivo" type="file" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Registrar cotizacion</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
