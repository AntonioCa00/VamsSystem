@extends('plantillaGen')

@section('contenido')

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Reportes</h1>
    <p class="mb-4"></p>

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-8 col-lg-7">

            <!-- Area Chart -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Total de costos por mes
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="graficoCombinado"></canvas>
                    </div>
                    <hr>
                    -
                </div>
            </div>

            <!-- Bar Chart -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Bar Chart</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="myBarChart"></canvas>
                    </div>
                    <hr>
                    Styling for the bar chart can be found in the
                    <code>/js/demo/chart-bar-demo.js</code> file.
                </div>
            </div>

        </div>

        <!-- Donut Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Donut Chart</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-pie pt-4">
                        <canvas id="myPieChart"></canvas>
                    </div>
                    <hr>
                    Styling for the donut chart can be found in the
                    <code>/js/demo/chart-pie-demo.js</code> file.
                </div>
            </div>
        </div>
    </div>
</div>

</div>
</div>

<script>
    // Obtener los datos de los meses desde el controlador
    var octubre = {{ $octubre[0]->octubre }};
    var septiembre = {{ $septiembre[0]->septiembre }};
    var agosto = {{ $agosto[0]->agosto }};
    var julio = {{ $julio[0]->julio }};

    // Configurar los datos para la gráfica combinada
    var ctx = document.getElementById('graficoCombinado').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio', 'Agosto', 'Septiembre', 'Octubre','Noviembre','Diciembre',],
            datasets: [{
                label: 'Total costo por mes ($)',
                data: [500,30,40,100,0,204,julio, agosto, septiembre, octubre,4000,2006],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection