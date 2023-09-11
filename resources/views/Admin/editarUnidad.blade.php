<head>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

@extends('plantilla')

@section('Contenido')

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">UNIDADES</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Editar unidad unidad</h6>
        </div>
        <div class="card-body">
            <h3 class="text-center">Datos de registro</h3>
            <form action="" method="post">
                @csrf
                {!!method_field('PUT')!!}
                <div class="form-group">
                    <label for="exampleFormControlInput1">Codigo de la unidad:</label>
                    <input name="id_unidad" type="text" value="{{$unidad->id_unidad}}" class="form-control"placeholder="Placas de la unidad">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Tipo de vehiculo</label>
                    <select name="tipo" class="form-control">
                        <option selected >{{$unidad->tipo}}</option>
                        <option>Camión</option>
                        <option>Autobús</option>
                        <option>Camioneta</option>
                        <option>Trailer</option>
                        <option>Automovil</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Estado:</label>
                    <select name="estado"class="form-control">
                        <option selected>{{$unidad->estado}}</option>
                        <option>Activo</option>
                        <option>Inactivo</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Año:</label>
                    <input name="anio_unidad" value="{{$unidad->ano_unidad}}" type="number" class="form-control" placeholder="Año de la unidad">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Marca:</label>
                    <input name="marca" value="{{$unidad->marca}}" type="text" class="form-control" placeholder="Marca de la unidad">
                </div>
                <button type="submit" class="btn btn-primary">Editar unidad</button>
            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

@endsection