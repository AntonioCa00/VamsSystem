@extends('plantillaGtArea')

@section('contenido')
    @if (session()->has('regis'))
        <!-- Mensaje de la clase SweetAlert2 -->
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Unidad registrada',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    @if (session()->has('update'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Unidad Editada',
                showConfirmButton: false,
                timer: 1000
            })
        </script> <!-- Fin de la clase script type="text/javascript" -->
    @endif

    @if (session()->has('eliminado'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Unidad Eliminada',
                showConfirmButton: false,
                timer: 1000
            })
        </script> <!-- Fin de la clase script type="text/javascript" -->
    @endif

    @if (session()->has('activado'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Unidad Activada',
                showConfirmButton: false,
                timer: 1000
            })
        </script> <!-- Fin de la clase script type="text/javascript" -->
    @endif

    @if (session()->has('baja'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Unidad Inactiva',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">MANTENIMIENTO UNIDADES</h1>

        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="row">

                @foreach ($unidades as $unidad)
                    <div class="col-xl-3 col-md-4 ">
                        <div class="card shadow mb-4 ">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Unidad: {{ $unidad->id_unidad }}</h6>
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                        aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">Opciones:</div>
                                        <a class="dropdown-item" href="#">Suspender Unidad</a>
                                        <a class="dropdown-item" href="#">Actualizar información</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#">Dar de baja</a>
                                    </div>
                                    <!-- Fin de la clase dropdown-menu dropdown-menu-right shadow animated--fade-in -->
                                </div> <!-- Fin de la clase dropdown no-arrow -->
                            </div> <!-- Fin de la clase card-header py-3 -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <a class="btn bg-gradient-info text-white" style="width: 100%; font-size: 12px;"
                                            href="#" data-toggle="modal"
                                            data-target="#detalles{{ $unidad->id_unidad }}">
                                            Información
                                        </a>
                                        <!-- Modal detalles-->
                                        <div class="modal fade" id="detalles{{ $unidad->id_unidad }}" tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Información
                                                            mantenimiento de la unidad</h5>
                                                        <button class="close" type="button" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">X</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body modal-body-scrollable">
                                                        <div class="col-xl-7 col-md-4 ">
                                                            <div
                                                                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                                                <h6 class="m-0 font-weight-bold text-primary">Unidad
                                                                    {{ $unidad->id_unidad }}</h6>
                                                                <div class="dropdown no-arrow">
                                                                </div> <!-- Fin de la clase dropdown no-arrow -->
                                                            </div> <!-- Fin de la clase card-header py-3 -->
                                                            <div class="card-body">
                                                                <div class="row mt-5">
                                                                    <div class="col-md-12">
                                                                        <td><!-- Inicio de la clase td -->

                                                                            <div class="d-flex justify-content-left">

                                                                                <?php                                                                                
                                                                                $porcentaje = 90;
                                                                                $color = '';
                                                                                if ($porcentaje < 25) {
                                                                                    $color = 'red';
                                                                                } elseif ($porcentaje < 50) {
                                                                                    $color = 'orange';
                                                                                } elseif ($porcentaje < 75) {
                                                                                    $color = 'yellow';
                                                                                } else {
                                                                                    $color = 'green';
                                                                                }
                                                                                ?>
                                                                                <ul>
                                                                                    <li>
                                                                                        <div
                                                                                            style="width: 900%; background-color: lightgrey; border: 1px solid black; border-radius: 3px; padding: 1px;">
                                                                                            <div
                                                                                                style="width: <?php echo $porcentaje; ?>%; background-color: <?php echo $color; ?>; height: 19px; border-radius: 1px;">
                                                                                            </div>
                                                                                        </div>
                                                                                        <!-- Fin de la clase d-flex -->
                                                                                        <span
                                                                                            style="margin-left: 12px; color: black"><?php echo 'Aceite  '; ?>
                                                                                        </span>
                                                                                    </li>
                                                                                    <li>
                                                                                        <div
                                                                                            style="width: 900%; background-color: lightgrey; border: 1px solid black; border-radius: 3px; padding: 1px;">
                                                                                            <div
                                                                                                style="width: <?php echo $porcentaje; ?>%; background-color: <?php echo $color; ?>; height: 19px; border-radius: 1px;">
                                                                                            </div>
                                                                                        </div>
                                                                                        <!-- Fin de la clase d-flex -->
                                                                                        <span
                                                                                            style="margin-left: 12px; color: black"><?php echo 'Filtros  '; ?>
                                                                                        </span>
                                                                                    </li>
                                                                                </ul>                                                                        
                                                                            </div> <!-- Fin de la clase col-md-12 -->

                                                                            <div class="d-flex justify-content-end">
                                                                                <?php //  Inicio de la clase php
                                                                                $Unidad = $unidad->tipo;
                                                                                $tiempo = 1 + rand(0, 5);
                                                                                
                                                                                switch ($Unidad) {
                                                                                    case 'AUTOMOVIL':
                                                                                        switch ($tiempo) {
                                                                                            case 1:
                                                                                                echo '<img src="\img\Unidad\Estatus_automovil\EstatusAmarillo.jpg" style="width: 100px;">';
                                                                                                break;
                                                                                            case 2:
                                                                                                echo '<img src="\img\Unidad\Estatus_automovil\EstatusAzul.jpg" style="width: 100px;">';
                                                                                                break;
                                                                                            case 3:
                                                                                                echo '<img src="\img\Unidad\Estatus_automovil\EstatusGris.jpg" style="width: 100px;">';
                                                                                                break;
                                                                                            case 4:
                                                                                                echo '<img src="\img\Unidad\Estatus_automovil\EstatusNaranja.jpg" style="width: 100px;">';
                                                                                                break;
                                                                                            case 5:
                                                                                                echo '<img src="\img\Unidad\Estatus_automovil\EstatusRojo.jpg" style="width: 100px;">';
                                                                                                break;
                                                                                            case 6:
                                                                                                echo '<img src="\img\Unidad\Estatus_automovil\EstatusVerde.jpg" style="width: 100px;">';
                                                                                                break;
                                                                                                echo 'Imagen no encontrada';
                                                                                        }
                                                                                        break;
                                                                                    case 'CAMIÓN':
                                                                                        switch ($tiempo) {
                                                                                            case 1:
                                                                                                echo '<img src="\img\Unidad\Estatus_camion\EstatusAmarillo.jpg" style="width: 100px;">';
                                                                                                break;
                                                                                            case 2:
                                                                                                echo '<img src="\img\Unidad\Estatus_camion\EstatusAzul.jpg" style="width: 100px;">';
                                                                                                break;
                                                                                            case 3:
                                                                                                echo '<img src="\img\Unidad\Estatus_camion\EstatusGris.jpg" style="width: 100px;">';
                                                                                                break;
                                                                                            case 4:
                                                                                                echo '<img src="\img\Unidad\Estatus_camion\EstatusNaranja.jpg" style="width: 100px;">';
                                                                                                break;
                                                                                            case 5:
                                                                                                echo '<img src="\img\Unidad\Estatus_camion\EstatusRojo.jpg" style="width: 100px;">';
                                                                                                break;
                                                                                            case 6:
                                                                                                echo '<img src="\img\Unidad\Estatus_camion\EstatusVerde.jpg" style="width: 100px;">';
                                                                                                break;
                                                                                                echo 'Imagen no encontrada';
                                                                                        }
                                                                                        break;
                                                                                    case 'CAMIONETA':
                                                                                        switch ($tiempo) {
                                                                                            case 1:
                                                                                                echo '<img src="\img\Unidad\Estatus_van\EstatusAmarillo.jpg" style="width: 100px;">';
                                                                                                break;
                                                                                            case 2:
                                                                                                echo '<img src="\img\Unidad\Estatus_van\EstatusAzul.jpg" style="width: 100px;">';
                                                                                                break;
                                                                                            case 3:
                                                                                                echo '<img src="\img\Unidad\Estatus_van\EstatusGris.jpg" style="width: 100px;">';
                                                                                                break;
                                                                                            case 4:
                                                                                                echo '<img src="\img\Unidad\Estatus_van\EstatusNaranja.jpg" style="width: 100px;">';
                                                                                                break;
                                                                                            case 5:
                                                                                                echo '<img src="\img\Unidad\Estatus_van\EstatusRojo.jpg" style="width: 100px;">';
                                                                                                break;
                                                                                            case 6:
                                                                                                echo '<img src="\img\Unidad\Estatus_van\EstatusVerde.jpg" style="width: 100px;">';
                                                                                                break;
                                                                                                echo 'Imagen no encontrada';
                                                                                        }
                                                                                        break;
                                                                                }
                                                                                
                                                                                ?>
                                                                                <!-- Fin de la clase php -->
                                                                                <button
                                                                                    class="btn bg-gradient-info text-white"
                                                                                    style="width: 20%; font-size: 12px;">Programar
                                                                                    mantenimiento</button>
                                                                            </div>
                                                                    </div> <!-- Fin de la clase d-flex -->
                                                                </div> <!-- Fin de la clase d-flex -->
                                                                </td> <!-- Fin de la clase td -->
                                                            </div> <!-- Fin de la clase col-md-12 -->
                                                        </div> <!-- Fin de la clase card-body -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- Fin de la clase col-md-6 -->
                                    </div> <!-- Fin de la clase col-md-6 -->
                                    <div class="col-md-6">
                                        <button class="btn bg-gradient-success text-white"
                                            style="width: 100%; font-size: 12px;">Actualizar</button>
                                    </div> <!-- Fin de la clase col-md-6 -->
                                </div> <!-- Fin de la clase row -->
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <?php
                                                $Unidad = $unidad->tipo;
                                                
                                                $tiempo = 1 + rand(0, 5);
                                                
                                                switch ($Unidad) {
                                                    case 'AUTOMOVIL':
                                                        switch ($tiempo) {
                                                            case 1:
                                                                echo '<img src="\img\Unidad\Estatus_automovil\EstatusAmarillo.jpg" style="width: 100px;">';
                                                                break;
                                                            case 2:
                                                                echo '<img src="\img\Unidad\Estatus_automovil\EstatusAzul.jpg" style="width: 100px;">';
                                                                break;
                                                            case 3:
                                                                echo '<img src="\img\Unidad\Estatus_automovil\EstatusGris.jpg" style="width: 100px;">';
                                                                break;
                                                            case 4:
                                                                echo '<img src="\img\Unidad\Estatus_automovil\EstatusNaranja.jpg" style="width: 100px;">';
                                                                break;
                                                            case 5:
                                                                echo '<img src="\img\Unidad\Estatus_automovil\EstatusRojo.jpg" style="width: 100px;">';
                                                                break;
                                                            case 6:
                                                                echo '<img src="\img\Unidad\Estatus_automovil\EstatusVerde.jpg" style="width: 100px;">';
                                                                break;
                                                                echo 'Imagen no encontrada';
                                                        }
                                                        break;
                                                    case 'CAMIÓN':
                                                        switch ($tiempo) {
                                                            case 1:
                                                                echo '<img src="\img\Unidad\Estatus_camion\EstatusAmarillo.jpg" style="width: 100px;">';
                                                                break;
                                                            case 2:
                                                                echo '<img src="\img\Unidad\Estatus_camion\EstatusAzul.jpg" style="width: 100px;">';
                                                                break;
                                                            case 3:
                                                                echo '<img src="\img\Unidad\Estatus_camion\EstatusGris.jpg" style="width: 100px;">';
                                                                break;
                                                            case 4:
                                                                echo '<img src="\img\Unidad\Estatus_camion\EstatusNaranja.jpg" style="width: 100px;">';
                                                                break;
                                                            case 5:
                                                                echo '<img src="\img\Unidad\Estatus_camion\EstatusRojo.jpg" style="width: 100px;">';
                                                                break;
                                                            case 6:
                                                                echo '<img src="\img\Unidad\Estatus_camion\EstatusVerde.jpg" style="width: 100px;">';
                                                                break;
                                                                echo 'Imagen no encontrada';
                                                        }
                                                        break;
                                                    case 'CAMIONETA':
                                                        switch ($tiempo) {
                                                            case 1:
                                                                echo '<img src="\img\Unidad\Estatus_van\EstatusAmarillo.jpg" style="width: 100px;">';
                                                                break;
                                                            case 2:
                                                                echo '<img src="\img\Unidad\Estatus_van\EstatusAzul.jpg" style="width: 100px;">';
                                                                break;
                                                            case 3:
                                                                echo '<img src="\img\Unidad\Estatus_van\EstatusGris.jpg" style="width: 100px;">';
                                                                break;
                                                            case 4:
                                                                echo '<img src="\img\Unidad\Estatus_van\EstatusNaranja.jpg" style="width: 100px;">';
                                                                break;
                                                            case 5:
                                                                echo '<img src="\img\Unidad\Estatus_van\EstatusRojo.jpg" style="width: 100px;">';
                                                                break;
                                                            case 6:
                                                                echo '<img src="\img\Unidad\Estatus_van\EstatusVerde.jpg" style="width: 100px;">';
                                                                break;
                                                                echo 'Imagen no encontrada';
                                                        }
                                                        break;
                                                }
                                                ?>
                                            </div> <!-- Fin de la clase d-flex -->
                                        </td> <!-- Fin de la clase td -->
                                    </div> <!-- Fin de la clase col-md-12 -->
                                </div> <!-- Fin de la clase row mt-4 -->
                            </div> <!-- Fin de la clase card-body -->
                        </div> <!-- Fin de la clase card shadow mb-4 -->
                    </div> <!-- Fin de la clase col-xl-4 col-md-6 -->
                @endforeach
            </div> <!-- Fin de la clase row -->


            <h1 class="h3 mb-2 text-gray-800">LISTA UNIDADES</h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Unidades existentes</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead> <!-- Inicio de la clase thead -->
                                <tr> <!-- Inicio de la clase tr -->
                                    <th>Placas unidad:</th>
                                    <th>Tipo:</th>
                                    <th>Estado:</th>
                                    <th>Año Unidad:</th>
                                    <th>Marca:</th>
                                    <th>Modelo:</th>
                                    <th>Imagen:</th>
                                </tr> <!-- Fin de la clase tr -->
                            </thead> <!-- Fin de la clase thead -->
                            <tbody> <!-- Inicio de la clase tbody -->
                                @foreach ($unidades as $unidad)
                                    <tr> <!-- Inicio de la clase tr -->
                                        <th>{{ $unidad->id_unidad }}</th>
                                        <th>{{ $unidad->tipo }}</th>
                                        <th>{{ $unidad->estado }}</th>
                                        <th>{{ $unidad->anio_unidad }}</th>
                                        <th>{{ $unidad->marca }}</th>
                                        <th>{{ $unidad->modelo }}</th>
                                        <!-- Ruta de la imagen: C:\laragon\www\PW_182\VamsSystem\public\img\Unidad\ -->
                                        <td>
                                            @if ($unidad->tipo == 'AUTOMOVIL')
                                                <img src="\img\Unidad\imagen_automovil.jpg" style="width: 100px;">
                                            @elseif ($unidad->tipo == 'CAMIÓN')
                                                <img src="\img\Unidad\imagen_camion.jpg" style="width: 100px;">
                                            @elseif ($unidad->tipo == 'CAMIONETA')
                                                <img src="\img\Unidad\imagen_van.jpg" style="width: 100px;">
                                            @else
                                                <img src="\img\Unidad\imagen_default.jpg" style="width: 100px;">
                                            @endif
                                        </td> <!-- Fin de la clase td -->
                                    </tr> <!-- Fin de la clase tr -->
                                @endforeach
                            </tbody> <!-- Fin de la clase tbody -->
                        </table> <!-- Fin de la clase table table-bordered -->
                    </div> <!-- Fin de la clase table-responsive -->
                </div> <!-- Fin de la clase card-body -->
            </div> <!-- Fin de la clase card shadow mb-4 -->
        </div> <!-- Fin de la clase container-fluid -->
    </div> <!-- Fin de la clase container-fluid -->
@endsection
