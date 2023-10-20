@extends('plantillaAdm')

@section('Contenido')

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
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Requisicion:</th>
                            <th>Archivo:</th>
                            <th>Opciones:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cotizaciones as $cotizacion)
                        <tr>
                            <th>
                                <a href="{{ asset($cotizacion->reqPDF) }}" target="_blank">
                                    <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                </a>    
                            </th>
                            <th class="text-center">
                                <a href="{{ asset($cotizacion->cotPDF) }}" target="_blank">
                                    <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                </a>
                            </th>
                            <th>
                                <form action="{{route('deleteCotiza',$cotizacion->id_cotizacion)}}" method="POST">
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
                    <input type="hidden" name="requisicion" value="{{$id}}">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Archivo de cotización:</label>
                        <input name="archivo" type="file" class="form-control" required>
                    </div>
    
                    <button type="submit" class="btn btn-primary">Registrar compra</button>
                </form>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

@endsection