@extends('plantillaAlm')

@section('contenido')

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">SOLICITUDES</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">REQUISICIONES REGISTRADAS</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Codigo:</th>
                            <th>Unidad:</th>                                                    
                            <th>Descripcion:</th>
                            <th>Estado:</th>
                            <th>Fecha solicitud:</th>
                            <th>Requisición:</th>
                            <th>Opciones:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($solicitudes as $solicitud)
                        <tr>
                            <th>{{$solicitud->id_requisicion}}</th>
                            <th>{{$solicitud->unidad_id}}</th>
                            <th>{{$solicitud->descripcion}}</th>
                            <th>{{$solicitud->estado}}</th>
                            <th>{{$solicitud->created_at}}</th>
                            <th>{{$solicitud->pdf}}</th>
                            {{-- <th>                                
                                @if($solicitud->estado === "Solicitado")
                                    <a href="" class="btn btn-primary">Eliminar</a>
                                @else 
                                    <a href="#" class="btn btn-primary" onclick="return false;" style="pointer-events: none; background-color: gray; cursor: not-allowed;">Eliminar</a>                                
                                @endif
                            </th> --}}
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

@endsection