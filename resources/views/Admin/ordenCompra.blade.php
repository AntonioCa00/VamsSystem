@extends('plantillaAdm')

@section('Contenido')

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">ORDEN DE COMPRA</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">ORDEN DE COMPRA</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Requisicion:</th>
                            <th>Cotizacion Validada:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr> 
                            <th class="text-center">
                                <a href="{{ asset($cotizacion->reqPDF) }}" target="_blank">
                                    <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                </a>    
                            </th>                            
                            <th class="text-center">
                                <a href="{{ asset($cotizacion->cotPDF) }}" target="_blank">
                                    <img src="{{ asset('img/pdf.png') }}" alt="Abrir PDF">
                                </a>
                            </th>               
                        </tr>
                    </tbody>
                </table>
            </div>

            <h6 class="m-0 font-weight-bold text-primary">Articulos de la requisicion</h6>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Cantidad:</th>
                            <th>Unidad de medida:</th>
                            <th>Descripcion:</th>
                            <th>Precio unitario:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <form action="{{route('createOrdenCompra',['cid' => $cotizacion->id_cotizacion, 'rid' => $id])}}" method="post">
                            @csrf
                        @foreach($articulos as $index => $articulo)
                        <tr>
                            <th><input type="hidden" name="articulos[{{ $articulo->id }}][id]" value="{{ $articulo->id }}">
                                <input class="form-control" type="text" name="articulos[{{ $articulo->id }}][cantidad]" value="{{ $articulo->cantidad }}" required></th>
                            <th><input class="form-control" type="text" name="articulos[{{ $articulo->id }}][unidad]" value="{{ $articulo->unidad }}" required></th>
                            <th><input class="form-control" type="text" name="articulos[{{ $articulo->id }}][descripcion]" value="{{ $articulo->descripcion }}" required></th>
                            <th><input class="form-control" type="text" name="articulos[{{ $articulo->id }}][precio_unitario]" value="{{ $articulo->precio }}" required></th>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer py-3 text-center">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Notas:</label>
                        <input name="Notas" type="text" class="form-control" placeholder="Agrega notas si necesario">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">PROVEEDOR PARA ORDEN DE COMPRA</label>
                        <select name="Proveedor" class="form-control" required>
                            <option value="" selected disabled>Selecciona el proveedor de este articulo:</option>
                            @foreach ($proveedores as $proveedor)                            
                                <option value="{{$proveedor->id_proveedor}}">{{$proveedor->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary"><h6 >Crear formato de requisición</h6></button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection