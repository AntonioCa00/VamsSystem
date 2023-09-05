@extends('plantilla')

@section('Contenido')

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">ENCARGADOS</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Encargados registrados</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID_encargado</th>
                            <th>Nombre</th>
                            <th>Telefono</th>
                            <th>Correo</th>
                            <th>Puesto</th>
                            <th>Contraseña</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($encargados as $encargado)
                        <tr>
                            <th>{{$encargado->id_encargado}}</th>
                            <th>{{$encargado->nombre}}</th>
                            <th>{{$encargado->telefono}}</th>
                            <th>{{$encargado->correo}}</th>
                            <th>{{$encargado->puesto}}</th>
                            <th>{{$encargado->contrasena}}</th>
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