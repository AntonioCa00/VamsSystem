@extends('plantillaGtArea')

@section('contenido')

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">PROVEEDORES</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Editar proveedor</h6>
        </div>
        <div class="card-body">
            <h3 class="text-center">Datos de registro</h3>
            <form action="{{route('updateProveedorDir',$proveedor->id_proveedor)}}" method="POST">
                {!!method_field('PUT')!!}    
                @csrf
                <div class="form-group">
                    <label for="exampleFormControlInput1">Nombre de empresa:</label>
                    <input value="{{$proveedor->nombre}}" name="nombre" type="text" class="form-control" placeholder="Nombre de la empresa proveedor" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Telefono:</label>
                    <input value="{{$proveedor->telefono}}" name="telefono" type="text" class="form-control" placeholder="No° telefonico del proveedor" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Correo:</label>
                    <input value="{{$proveedor->correo}}" name="correo" type="text" class="form-control" placeholder="Correo electronico del proveedor" required>
                </div>
                <button type="submit" class="btn btn-primary">Editar proveedor</button>
            </form>
        </div>
    </div>
</div>
@endsection