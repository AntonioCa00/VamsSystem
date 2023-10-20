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

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Cantidad:</th>
                            <th>Descripcion:</th>
                            <th>Precio unitario:</th>
                            <th>Opciones:</th>  
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ordenCom as $index => $ordenCom)
                        <tr>
                            <th>{{ $ordenCom['cantidad'] }}</th>
                            <th>{{ $ordenCom['descripcion'] }}</th>
                            <th>{{ $ordenCom['precio'] }}</th>
                            <th>
                                <form action="{{ route('eliminarElemOrden', ['index' => $index]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-primary">Eliminar</button>
                                </form>                                
                            </th>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-body">
                <h5 class="text-center">Datos para orden de compra</h5>
                <form action="{{route('arrayOrdenCom')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Cantidad:</label>
                        <input name="Cantidad" type="number" class="form-control" placeholder="Cantidad de refacciones a comprar" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Descripcion:</label>
                        <input name="Descripcion" type="text" class="form-control" placeholder="Describe la refaccion o articulo a comprar" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Precio unitario:</label>
                        <input name="PrecioUni" type="number" step="0.01" pattern="\d+(\.\d{1,2})?" class="form-control" placeholder="Precio por pieza" required>                        
                    </div>                    
                    <button type="submit" class="btn btn-primary">Agregar articulo</button>
                </form>
            </div>

            <div class="card-footer py-3 text-center">
                <form action="{{route('createOrdenCompra')}}" method="post">
                    @csrf
                    <input type="hidden" name="cotizacion" value="{{$coti}}">
                    <input type="hidden" name="requisicion" value="{{$id}}">
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