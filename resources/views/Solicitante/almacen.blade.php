@extends('plantillaSol')

@section('contenido')

@if(session()->has('entra'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Bienvenido a sistema VAMS.',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">ALMACEN</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a class="btn btn-primary" href="{{route('solicitudAlm')}}">Solicitar de almacen</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Clave:</th>
                            <th>Ubicación</th>
                            <th>Descripción:</th>
                            <th>Medida:</th>  
                            <th>Marca:</th>                                                      
                            <th>Stock:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($refacciones as $refaccion)
                        <tr>
                            <th>{{$refaccion->clave}}</th>
                            <th>{{$refaccion->ubicacion}}</th>
                            <th>{{$refaccion->descripcion}}</th>
                            <th>{{$refaccion->medida}}</th>
                            <th>{{$refaccion->marca}}</th>
                            <th>{{$refaccion->cantidad}}</th>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection