@extends('plantillaAdm')

@section('Contenido')

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
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Panel de Gastos</h1>
        <a href="{{route('reportesAdm')}}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> REPORTES GENERALES</a>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Gastos (Mensuales)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$ {{$TotalMes}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Gastos (Anuales)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{$TotalAnio}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Completadas
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{$completas}}</div>
                                </div>
                                <div class="col">
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Requisiciones Pendientes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$pendientes}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->

    <div class="row">

        <!-- Area Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div
                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Resumen de Gastos</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div
                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Gastos mensuales por área</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="myPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> Mantenimiento
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Almacen
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-info"></i> Logistica
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-danger"></i> RH
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-warning"></i> Gestoría
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-secondary"></i> Contabilidad
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-dark"></i> Sistemas
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle" style="color: #ff00ff;"></i> Ventas
                        </span>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script defer>
    document.addEventListener("DOMContentLoaded", function() {
    var enero = {{ $enero }};
    var febrero = {{ $febrero }};
    var marzo = {{ $marzo }};
    var abril = {{ $abril }};
    var mayo = {{ $mayo }};
    var junio = {{ $junio }};
    var julio = {{ $julio }};
    var agosto = {{ $agosto }};
    var septiembre = {{ $septiembre }};
    var octubre = {{ $octubre }};
    var noviembre = {{ $noviembre }};
    var diciembre = {{ $diciembre }};

    var mantenimiento = parseFloat({{ $mantenimiento }}) || 0; // Convierte el valor a flotante o usa 0 como predeterminado
    var almacen = parseFloat({{ $almacen }}) || 0; // Convierte el valor a flotante o usa 0 como predeterminado
    var logistica = parseFloat({{ $logistica }}) || 0; // Convierte el valor a flotante o usa 0 como predeterminado
    var rh = parseFloat({{ $rh }}) || 0; // Convierte el valor a flotante o usa 0 como predeterminado
    var gestoria = parseFloat({{ $gestoria }}) || 0; // Convierte el valor a flotante o usa 0 como predeterminado
    var contabilidad = parseFloat({{ $contabilidad }}) || 0; // Convierte el valor a flotante o usa 0 como predeterminado
    var sistemas = parseFloat({{ $sistemas }}) || 0; // Convierte el valor a flotante o usa 0 como predeterminado
    var ventas = parseFloat({{ $ventas }}) || 0; // Convierte el valor a flotante o usa 0 como predeterminado


    generarGraficaCombinada(enero, febrero, marzo, abril, mayo, junio, julio, agosto, septiembre, octubre, noviembre, diciembre);

    generarGraficarea( mantenimiento, almacen, logistica, rh, gestoria, contabilidad, sistemas, ventas);

    });    
</script>
@endsection
