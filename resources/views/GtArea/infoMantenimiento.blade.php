@extends('plantillaGtArea')

@section('contenido')
    <div class="container-fluid">
        <div class="card shadow mb-5 ">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Unidad {{ $unidad->id_unidad }}</h6>
            </div> <!-- Fin de la clase card-header py-3 -->
            <div class="card-body justify-content-between">
                <?php
                if (!function_exists('porcentaje')) {
                    function porcentaje($porcentaje)
                    {
                        $color = '';
                        if ($porcentaje < 10) {
                            $color = 'rgb(236, 0, 0)'; // Rojo
                        } elseif ($porcentaje < 20) {
                            $color = 'rgb(236, 39, 0)'; // Naranja
                        } elseif ($porcentaje < 30) {
                            $color = 'rgb(236, 118, 0)';
                        } elseif ($porcentaje < 40) {
                            $color = 'rgb(236, 158, 0)';
                        } elseif ($porcentaje < 50) {
                            $color = 'rgb(236, 197, 0)';
                        } elseif ($porcentaje < 60) {
                            $color = 'rgb(236, 236, 0)'; // Amarillo
                        } elseif ($porcentaje < 70) {
                            $color = 'rgb(197, 236, 0)';
                        } elseif ($porcentaje < 89) {
                            $color = 'rgb(158, 236, 0)';
                        } elseif ($porcentaje < 90) {
                            $color = 'rgb(118, 236, 0)';
                        } else {
                            $color = 'rgb(79, 236, 0)'; // Verde
                        }
                        return $color; // Se añade el retorno del color calculado
                    }
                }
                ?>
                <div class="row">
                    <div class="col-md-6 container-fluid">
                        <div class="card">
                            <div class="card-body">
                                <div class="justify-content-left">
                                    <ul>
                                        <li>
                                            <div style="background-color: lightgrey; border: 1px solid black; border-radius: 3px; padding: 1px; display: flex; width: 100%;">
                                                <div
                                                    style="width: {{$datos[0]['filtro_aireG']}}%; background-color: <?php echo porcentaje($datos[0]['filtro_aireG']); ?>; height: 19px; border-radius: 1px;">
                                                </div>
                                            </div> <!-- Fin de la clase d-flex -->
                                            <span style="margin-left: 12px; color: black"><?php echo 'Filtro de Aire Grande  '; ?> </span>
                                        </li>
                                        <li>
                                            <div style="background-color: lightgrey; border: 1px solid black; border-radius: 3px; padding: 1px; display: flex; width: 100%;">
                                                <div
                                                    style=" width: {{$datos[0]['filtro_aireC']}}%; background-color: <?php echo porcentaje($datos[0]['filtro_aireC']); ?>; height: 19px; border-radius: 1px;">
                                                </div>
                                            </div> <!-- Fin de la clase d-flex -->
                                            <span style="margin-left: 12px; color: black"><?php echo 'Filtro de Aire Chico '; ?> </span>
                                        </li>
                                        <li>
                                            <div style="background-color: lightgrey; border: 1px solid black; border-radius: 3px; padding: 1px; display: flex; width: 100%;">
                                                <div
                                                    style="width: {{$datos[0]['filtro_diesel']}}%; background-color: <?php echo porcentaje($datos[0]['filtro_diesel']); ?>; height: 19px; border-radius: 1px;">
                                                </div>
                                            </div> <!-- Fin de la clase d-flex -->
                                            <span style="margin-left: 12px; color: black"><?php echo 'Filtro de Diesel '; ?> </span>
                                        </li>
                                        <li>
                                            <div style="background-color: lightgrey; border: 1px solid black; border-radius: 3px; padding: 1px; display: flex; width: 100%;">
                                                <div
                                                    style="width: {{$datos[0]['filtro_aceite']}}%; background-color: <?php echo porcentaje($datos[0]['filtro_aceite']); ?>; height: 19px; border-radius: 1px;">
                                                </div>
                                            </div> <!-- Fin de la clase d-flex -->
                                            <span style="margin-left: 12px; color: black"><?php echo 'Filtro de Aceite '; ?> </span>
                                        </li>
                                        <li>
                                            <div style="background-color: lightgrey; border: 1px solid black; border-radius: 3px; padding: 1px; display: flex; width: 100%;">
                                                <div
                                                    style="width: {{$datos[0]['wk1060_trampa']}}%; background-color: <?php echo porcentaje($datos[0]['wk1060_trampa']); ?>; height: 19px; border-radius: 1px;">
                                                </div>
                                            </div> <!-- Fin de la clase d-flex -->
                                            <span style="margin-left: 12px; color: black"><?php echo 'WK 1060 Trampa'; ?> </span>
                                        </li>

                                        <li>
                                            <div style="background-color: lightgrey; border: 1px solid black; border-radius: 3px; padding: 1px; display: flex; width: 100%;">
                                                <div
                                                    style="width: {{$datos[0]['aceite_motor']}}%; background-color: <?php echo porcentaje($datos[0]['aceite_motor']); ?>; height: 19px; border-radius: 1px;">
                                                </div>
                                            </div> <!-- Fin de la clase d-flex -->
                                            <span style="margin-left: 12px; color: black"><?php echo 'Aceite de motor'; ?> </span>
                                        </li>
                                    </ul>
                                </div> <!-- Fin de la clase col-md-12 -->
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 container-fluid">
                        <div class="card">
                            <div class="card-body">
                                <div class="justify-content-left">
                                    <ul>
                                        <li>
                                            <div style="background-color: lightgrey; border: 1px solid black; border-radius: 3px; padding: 1px; display: flex; width: 100%;">
                                                <div
                                                    style="width: {{$datos[0]['filtro_urea']}}%; background-color: <?php echo porcentaje($datos[0]['filtro_urea']); ?>; height: 19px; border-radius: 1px;">
                                                </div>
                                            </div> <!-- Fin de la clase d-flex -->  
                                            <span style="margin-left: 12px; color: black"><?php echo 'Fitro Urea'; ?> </span>
                                        </li>
                                        <li>
                                            <div style="background-color: lightgrey; border: 1px solid black; border-radius: 3px; padding: 1px; display: flex; width: 100%;">
                                                <div
                                                    style="width: {{$datos[0]['anticongelante']}}%; background-color: <?php echo porcentaje($datos[0]['anticongelante']); ?>; height: 19px; border-radius: 1px;">
                                                </div>
                                            </div> <!-- Fin de la clase d-flex -->
                                            <span style="margin-left: 12px; color: black"><?php echo 'Anticongelante'; ?> </span>
                                        </li>
                                        <li>
                                            <div style="background-color: lightgrey; border: 1px solid black; border-radius: 3px; padding: 1px; display: flex; width: 100%;">
                                                <div
                                                    style="width: {{$datos[0]['aceite_direccion']}}%; background-color: <?php echo porcentaje($datos[0]['aceite_direccion']); ?>; height: 19px; border-radius: 1px;">
                                                </div>
                                            </div> <!-- Fin de la clase d-flex -->
                                            <span style="margin-left: 12px; color: black"><?php echo 'Aceite direccion'; ?> </span>
                                        </li>
                                        <li>
                                            <div style="background-color: lightgrey; border: 1px solid black; border-radius: 3px; padding: 1px; display: flex; width: 100%;">
                                                <div
                                                    style="width: {{$datos[0]['banda_poles']}}%; background-color: <?php echo porcentaje($datos[0]['banda_poles']); ?>; height: 19px; border-radius: 1px;">
                                                </div>
                                            </div> <!-- Fin de la clase d-flex -->
                                            <span style="margin-left: 12px; color: black"><?php echo 'Banda de poles'; ?> </span>
                                        </li>
                                        <li>
                                            <div style="background-color: lightgrey; border: 1px solid black; border-radius: 3px; padding: 1px; display: flex; width: 100%;">
                                                <div
                                                    style="width: {{$datos[0]['ajuste_frenos']}}%; background-color: <?php echo porcentaje($datos[0]['ajuste_frenos']); ?>; height: 19px; border-radius: 1px;">
                                                </div>
                                            </div> <!-- Fin de la clase d-flex -->
                                            <span style="margin-left: 12px; color: black"><?php echo 'Ajuste frenos'; ?> </span>
                                        </li>
                                        <li>
                                            <div style="background-color: lightgrey; border: 1px solid black; border-radius: 3px; padding: 1px; display: flex; width: 100%;">
                                                <div
                                                    style="width: {{$datos[0]['engrasado_chasis']}}%; background-color: <?php echo porcentaje($datos[0]['engrasado_chasis']); ?>; height: 19px; border-radius: 1px;">
                                                </div>
                                            </div> <!-- Fin de la clase d-flex -->
                                            <span style="margin-left: 12px; color: black"><?php echo 'Engrasado de chasis'; ?> </span>
                                        </li>
                                    </ul>
                                </div>
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
                                ?> <!-- Fin de la clase php -->
                                <button class="btn bg-gradient-info text-white"
                                    style="width: 200px; font-size: 12px;">Programar
                                    mantenimiento</button>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
@endsection
