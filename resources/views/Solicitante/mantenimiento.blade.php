@extends('plantillaSol')

@section('contenido')
    @if (session()->has('registrado'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Se ha registrado su mantenimiento!',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif
    @if (session()->has('kilometraje'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Se ha actualizado el kilometraje actual',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    @if(session()->has('update'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Unidad Editada',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

    @if (session()->has('error'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'warning',
                title: 'No puedes registrar un kilometraje menor o igual al anterior',
                showConfirmButton: false,
                timer: 1400
            })
        </script>
    @endif

    @if (session()->has('importado'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Se han actualizado los kilometrajes',
                showConfirmButton: false,
                timer: 1400
            })
        </script>
    @endif

    <div class="container-fluid">
        <div class="py-1 d-flex justify-content-between align-items-center">
            <!-- Page Heading -->
            <h1 class="h3 mb-2 text-gray-800">MANTENIMIENTO UNIDADES</h1>
            <a class="btn btn-primary text-white"href="#"
                data-toggle="modal" data-target="#ActualizarKms">
                Actualizar kilometrajes
            </a>
            <!-- Actualizar todos los kilometrajes Modal-->
            <div class="modal fade" id="ActualizarKms" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Actualizar
                                kilometraje de todas la unidades</h5>
                            <h4 class="font-weight-bold"></4>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">X</span>
                                </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('kilometrajes')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label>Favor de cargar su excel de registro de kms</label>
                                    <input name="file" type="file" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary">Actualizar kilometrajes</button>
                            </form>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="row">
                @php
                    // Ordenar la colección de unidades por el porcentaje de porcentaje (porcentaje)
                    $unidadesOrdenadas = $unidades->sortBy('porcentaje')->values()->all();
                @endphp

                @foreach ($unidadesOrdenadas as $unidad)
                    <div class="col-xl-3 col-md-4 ">
                        <div class="card shadow mb-4 ">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Unidad: {{ $unidad->id_unidad }}</h6>
                                @include('shared.alertUni',['unidad_id'=>$unidad->id_unidad])
                            </div> <!-- Fin de la clase card-header py-3 -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <a class="btn bg-gradient-info text-white" style="width: 100%; font-size: 12px;"
                                            href="{{ route('infoMantenimiento', $unidad->id_unidad) }}">
                                            Información
                                        </a>
                                    </div> <!-- Fin de la clase col-md-6 -->
                                    <div class="col-md-6">
                                        <a class="btn bg-gradient-success text-white" style="width: 100%; font-size: 12px;"
                                            href="#" data-toggle="modal"
                                            data-target="#Actualizar{{ $unidad->id_unidad }}">
                                            Actualizar km
                                        </a>
                                        <!-- Rechazar Modal-->
                                        <div class="modal fade" id="Actualizar{{ $unidad->id_unidad }}" tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Actualizar
                                                            kilometraje de la unidad:</h5>
                                                        <h4 class="font-weight-bold"> {{ $unidad->id_unidad }}</4>
                                                            <button class="close" type="button" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">X</span>
                                                            </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        El ultimo kilometraje registrado de la unidad es: <label
                                                            class="font-weight-bold text-primary">
                                                            {{ number_format($unidad->kilometraje, 0, '.', ',') }}</label>
                                                        <form action="{{ route('updateKilom', $unidad->id_unidad) }}"
                                                            method="POST" class="mt-4">
                                                            @csrf
                                                            {!! method_field('PUT') !!}
                                                            <div class="form-group">
                                                                <label for="exampleFormControlInput1">¿Cuál es el
                                                                    kilometraje actual?</label>
                                                                <input id="numero" name="kilometraje" type="text"
                                                                    class="form-control"
                                                                    placeholder="Ingresa el kilometraje actual" required>
                                                            </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Confirmar</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- Fin de la clase col-md-6 -->
                                </div> <!-- Fin de la clase row -->
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <?php
                                                $Unidad = $unidad->tipo;
                                                
                                                echo $unidad->porcentaje;
                                                switch ($Unidad) {
                                                    case 'AUTOMOVIL':
                                                        if ($unidad->porcentaje < 25) {
                                                            echo '<img src="\img\Unidad\Estatus_automovil\EstatusRojo.jpg" style="width: 100px;">';
                                                        } elseif ($unidad->porcentaje >= 25 && $unidad->porcentaje < 50) {
                                                            echo '<img src="\img\Unidad\Estatus_automovil\EstatusNaranja.jpg" style="width: 100px;">';
                                                        } elseif ($unidad->porcentaje >= 50 && $unidad->porcentaje < 75) {
                                                            echo '<img src="\img\Unidad\Estatus_automovil\EstatusAmarillo.jpg" style="width: 100px;">';
                                                        } elseif ($unidad->porcentaje >= 75 && $unidad->porcentaje <= 100) {
                                                            echo '<img src="\img\Unidad\Estatus_automovil\EstatusVerde.jpg" style="width: 100px;">';
                                                        }
                                                        break;
                                                    case 'CAMIÓN':
                                                        if ($unidad->porcentaje < 25) {
                                                            echo '<img src="\img\Unidad\Estatus_camion\EstatusRojo.jpg" style="width: 100px;">';
                                                        } elseif ($unidad->porcentaje >= 25 && $unidad->porcentaje < 50) {
                                                            echo '<img src="\img\Unidad\Estatus_camion\EstatusNaranja.jpg" style="width: 100px;">';
                                                        } elseif ($unidad->porcentaje >= 50 && $unidad->porcentaje < 75) {
                                                            echo '<img src="\img\Unidad\Estatus_camion\EstatusAmarillo.jpg" style="width: 100px;">';
                                                        } elseif ($unidad->porcentaje >= 75 && $unidad->porcentaje <= 100) {
                                                            echo '<img src="\img\Unidad\Estatus_camion\EstatusVerde.jpg" style="width: 100px;">';
                                                        }
                                                        break;
                                                    case 'CAMIONETA':
                                                        if ($unidad->porcentaje < 25) {
                                                            echo '<img src="\img\Unidad\Estatus_van\EstatusRojo.jpg" style="width: 100px;">';
                                                        } elseif ($unidad->porcentaje >= 25 && $unidad->porcentaje < 50) {
                                                            echo '<img src="\img\Unidad\Estatus_van\EstatusNaranja.jpg" style="width: 100px;">';
                                                        } elseif ($unidad->porcentaje >= 50 && $unidad->porcentaje < 75) {
                                                            echo '<img src="\img\Unidad\Estatus_van\EstatusAmarillo.jpg" style="width: 100px;">';
                                                        } elseif ($unidad->porcentaje >= 75 && $unidad->porcentaje <= 100) {
                                                            echo '<img src="\img\Unidad\Estatus_van\EstatusVerde.jpg" style="width: 100px;">';
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
        </div> <!-- Fin de la clase container-fluid -->
    </div> <!-- Fin de la clase container-fluid -->
@endsection
