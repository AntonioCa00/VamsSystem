@extends('plantillaGerGen')

@section('contenido')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Reportes generales</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- CARD REPORTE REQUISICIONES-->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Reporte general</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <div>
                                    <label for="exampleFormControlSelect1">REQUISICIONES:</label>
                                    <a class="btn btn-primary" href="#" data-toggle="modal"
                                        data-target="#ReportesReq">
                                        Reporte de requisiciones
                                    </a>
                                    <!-- Logout Modal-->
                                    <div class="modal fade" id="ReportesReq" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Consulta de
                                                        requisiciones</h5>
                                                    <button class="close" type="button" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">X</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('reportesReqGer') }}" method="POST">
                                                        @csrf
                                                        <div class="card-body py-1">
                                                            <div class="row">
                                                                <div class="col-sm-6 mb-1">
                                                                    <label for="reqInicio" class="col-form-label">Fecha
                                                                        inicio:</label>
                                                                    <input type="date" class="form-control"
                                                                        name="inicio" id="reqInicio" required>
                                                                </div>
                                                                <div class="col-sm-6 mb-1">
                                                                    <label for="reqFin" class="col-form-label">Fecha
                                                                        final:</label>
                                                                    <input type="date" class="form-control"
                                                                        name="fin" id="reqFin" required>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="card-header py-1">
                                                            <a href="#" class="form-control btn btn-primary"
                                                                onclick="setDates('reqInicio', 'reqFin', 'month')">Mes
                                                                actual</a>
                                                            <a href="#" class="form-control btn btn-primary"
                                                                onclick="setDates('reqInicio', 'reqFin', 'week')">Semana
                                                                actual</a>
                                                            <a href="#" class="form-control btn btn-primary"
                                                                onclick="setDates('reqInicio', 'reqFin', 'day')">Hoy</a>
                                                        </div>
                                                        <div class="card-header py-1">
                                                            <a href="#" class="form-control btn btn-primary"
                                                                onclick="setDates('reqInicio', 'reqFin', 'year')">Año
                                                                actual</a>
                                                        </div>
                                                        <div class="card-body py-3">
                                                            <label for="">Filtrar por departamento</label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="departamentos[]" value="Mantenimiento"
                                                                    id="flexCheckMantenimiento" checked>
                                                                <label class="form-check-label"
                                                                    for="flexCheckMantenimiento">
                                                                    Mantenimiento
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="departamentos[]" value="Finanzas"
                                                                    id="flexCheckFinanzas" checked>
                                                                <label class="form-check-label" for="flexCheckFinanzas">
                                                                    Finanzas
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="departamentos[]" value="RH"
                                                                    id="flexCheckRH" checked>
                                                                <label class="form-check-label" for="flexCheckRH">
                                                                    RH
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="departamentos[]" value="Logistica"
                                                                    id="flexCheckLogistica" checked>
                                                                <label class="form-check-label"
                                                                    for="flexCheckLogistica">
                                                                    Logistica
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Crear
                                                                reporte</button>
                                                            <button class="btn btn-secondary" type="button"
                                                                data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CARD REPORTE ORDENES DE COMPRA-->
        <div class="col-xl-5 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Reporte general
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <div>
                                    <label for="exampleFormControlSelect1">REQUISICIONES - ORDENES DE COMPRA:</label>
                                    <a class="btn btn-primary" href="#" data-toggle="modal"
                                        data-target="#ReportesCompras">
                                        Reporte de ordenes de compra
                                    </a>
                                    <!-- Logout Modal-->
                                    <div class="modal fade" id="ReportesCompras" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Consulta de ordenes
                                                        de compra</h5>
                                                    <button class="close" type="button" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">X</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('reportesOrdGer') }}" method="POST">
                                                        @csrf
                                                        <div class="card-body py-1">
                                                            <div class="row">
                                                                <div class="col-sm-6 mb-1">
                                                                    <label for="reqInicio"
                                                                        class="col-form-label">Fecha inicio:</label>
                                                                    <input type="date" class="form-control"
                                                                        name="inicio" id="compraInicio" required>
                                                                </div>
                                                                <div class="col-sm-6 mb-1">
                                                                    <label for="reqFin" class="col-form-label">Fecha
                                                                        final:</label>
                                                                    <input type="date" class="form-control"
                                                                        name="fin" id="compraFin" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-header py-1">
                                                            <a href="#" class="form-control btn btn-primary"
                                                                onclick="setDates('compraInicio', 'compraFin', 'month')">Mes
                                                                actual</a>
                                                            <a href="#" class="form-control btn btn-primary"
                                                                onclick="setDates('compraInicio', 'compraFin', 'week')">Semana
                                                                actual</a>
                                                            <a href="#" class="form-control btn btn-primary"
                                                                onclick="setDates('compraInicio', 'compraFin', 'day')">Hoy</a>
                                                        </div>
                                                        <div class="card-header py-1">
                                                            <a href="#" class="form-control btn btn-primary"
                                                                onclick="setDates('compraInicio', 'compraFin', 'year')">Año
                                                                actual</a>
                                                        </div>
                                                        <div class="card-body py-3">
                                                            <label for="">Filtrar por departamento</label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="departamentos[]" value="Mantenimiento"
                                                                    id="flexCheckMantenimiento" checked>
                                                                <label class="form-check-label"
                                                                    for="flexCheckMantenimiento">
                                                                    Mantenimiento
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="departamentos[]" value="Finanzas"
                                                                    id="flexCheckFinanzas" checked>
                                                                <label class="form-check-label"
                                                                    for="flexCheckFinanzas">
                                                                    Finanzas
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="departamentos[]" value="RH"
                                                                    id="flexCheckRH" checked>
                                                                <label class="form-check-label" for="flexCheckRH">
                                                                    RH
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="departamentos[]" value="Logistica"
                                                                    id="flexCheckLogistica" checked>
                                                                <label class="form-check-label"
                                                                    for="flexCheckLogistica">
                                                                    Logistica
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Crear
                                                                reporte</button>
                                                            <button class="btn btn-secondary" type="button"
                                                                data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

<script>
    const today = new Date();

    function setDates(startId, endId, period) {
        const startElement = document.getElementById(startId);
        const endElement = document.getElementById(endId);

        let startDate;
        switch (period) {
            case 'year':
                startDate = new Date(today.getFullYear(), 0, 1);
                break;
            case 'month':
                startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                break;
            case 'week':
                startDate = new Date(today);
                startDate.setDate(today.getDate() - today.getDay() + 1);
                break;
            case 'day':
                startDate = new Date(today);
                break;
            default:
                startDate = new Date();
        }

        startElement.value = formatDate(startDate);
        endElement.value = formatDate(today);
    }

    function formatDate(date) {
        const day = ("0" + date.getDate()).slice(-2);
        const month = ("0" + (date.getMonth() + 1)).slice(-2);
        const year = date.getFullYear();
        return `${year}-${month}-${day}`;
    }
</script>
@endsection
