@extends('plantillaAdm')

@section('Contenido')

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">PROVEEDORES</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Registrar nuevo proveedor</h6>
        </div>
        <div class="card-body">
            <h3 class="text-center">Datos de registro</h3>
            <form action="{{route('insertProveedor')}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="exampleFormControlInput1">Nombre de empresa:</label>
                    <input name="nombre" type="text" class="form-control" placeholder="Nombre de la empresa proveedor" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Telefono:</label>
                    <input name="telefono" type="text" class="form-control" placeholder="No° telefonico del proveedor" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Correo:</label>
                    <input name="correo" type="text" class="form-control" placeholder="Correo electronico del proveedor" required>
                </div>
                <button type="submit" class="btn btn-primary">Registrar proveedor</button>
            </form>
        </div>
    </div>
</div>
@endsection