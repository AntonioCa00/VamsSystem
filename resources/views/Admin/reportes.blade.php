@extends('plantillaAdm')

@section('Contenido')

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Reportes generales</h1>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- CARD REPORTE GENERAL-->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Reporte general</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <a class="btn btn-primary" href="{{route('reporteGeneralAdm')}}">Crear reporte</a>                                
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CARD REPORTE POR ENCARGADOS-->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1"> Reporte por encargado</div>
                            <form action="{{route('reporteEncargadoAdm')}}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Solicitantes:</label>
                                    <select name="encargado" class="form-control" required>
                                        <option selected disabled value="">Selecciona el encargado:</option>
                                        @foreach ($encargados as $encargado)                            
                                            <option value="{{$encargado->id}}">{{$encargado->nombres}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Crear reporte</button>
                            </form>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CARD REPORTE POR UNIDAD-->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1"> Reporte por unidad</div>
                            <form action="{{route('reporteUnidadAdm')}}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Unidades:</label>
                                    <select name="unidad" class="form-control" required>
                                        <option selected disabled value="">Selecciona la unidad:</option>
                                        @foreach ($unidades as $unidad)                            
                                            <option value="{{$unidad->id_unidad}}">{{$unidad->id_unidad}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Crear reporte</button>
                            </form>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection