@extends('plantillaAlm')

@section('contenido')

@if(session()->has('salida'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Las refacciones salieron correctamente',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">SALIDAS</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Salidas pendientes</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID requisicion</th>            
                            <th>Solicitante:</th>
                            <th>Fecha solicitud:</th>
                            <th>Estado:</th>
                            <th>Unidad:</th>
                            <th>Requisicion:</th>
                            <th>Opciones:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salidas as $salida)
                        <tr>
                            <th>{{$salida->id_requisicion}}</th>
                            <th>{{$salida->nombres}}</th>
                            <th>{{$salida->fecha_creacion}}</th>
                            <th>{{$salida->estado}}</th>
                            <th>{{$salida->unidad_id}}</th>
                            <th>
                                <a href="{{ asset($salida->pdf) }}" target="_blank">
                                    <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                </a>
                            </th>
                            <th>
                                <a class="btn btn-primary" href="{{route('crearSalida',$salida->id_requisicion)}}">
                                    Dar salida
                                </a>                                
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