@extends('plantillaGerGen')

@section('contenido')

@if(session()->has('regis'))
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

@if(session()->has('eliminado'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Unidad Eliminada',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

@if(session()->has('activado'))
    <script type="text/javascript">          
        Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Unidad Activada',
        showConfirmButton: false,
        timer: 1000
        })
    </script> 
@endif

@if(session()->has('baja'))
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
            <div class="col-xl-4 col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    
                        <h6 class="m-0 font-weight-bold text-primary">Unidad {{$unidad->id_unidad}}</h6>
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
                            </div> <!-- Fin de la clase dropdown-menu dropdown-menu-right shadow animated--fade-in -->
                        </div> <!-- Fin de la clase dropdown no-arrow -->
                        
                    </div> <!-- Fin de la clase card-header py-3 -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn bg-gradient-info text-white">Ver mas información</button>
                            </div> <!-- Fin de la clase col-md-6 -->
                            <div class="col-md-6">
                                <button class="btn bg-gradient-success text-white">Reportar fallo</button>
                            </div>  <!-- Fin de la clase col-md-6 -->
                        </div> <!-- Fin de la clase row --> 
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <?php
                                        $tiempo = 1+rand(0,2);

                                        if ($tiempo == 1) {
                                            echo '<img src="\img\EstatusUnidad\Estatus_van\Unidad1.jpg" style="width: 100px;">';
                                        } elseif ($tiempo == 2) {
                                            echo '<img src="\img\EstatusUnidad\Estatus_van\Unidad2.jpg" style="width: 100px;">';
                                        } elseif ($tiempo == 3) {
                                            echo '<img src="\img\EstatusUnidad\Estatus_van\Unidad3.jpg" style="width: 100px;">';
                                        } else {
                                            echo 'Imagen no encontrada';
                                        }
                                        ?>
                                    </div> <!-- Fin de la clase d-flex -->
                                </td> <!-- Fin de la clase td -->
                            </div> <!-- Fin de la clase col-md-12 -->
                        </div> <!-- Fin de la clase row mt-4 -->
                    </div>  <!-- Fin de la clase card-body -->
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
                    <thead>
                        <tr>
                            <th>Placas unidad:</th>
                            <th>Tipo:</th>
                            <th>Estado:</th>
                            <th>Año Unidad:</th>
                            <th>Marca:</th>
                            <th>Modelo:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($unidades as $unidad)
                        <tr>
                            <th>{{$unidad->id_unidad}}</th>
                            <th>{{$unidad->tipo}}</th>
                            <th>{{$unidad->estado}}</th>
                            <th>{{$unidad->anio_unidad}}</th>
                            <th>{{$unidad->marca}}</th>
                            <th>{{$unidad->modelo}}</th>    
                            <!-- Ruta de la imagen: C:\laragon\www\PW_182\VamsSystem\public\img\EstatusUnidad\ -->
                            <td><img src="\img\EstatusUnidad\imagen_unidad.jpg" style="width: 100px;" >
                            <!-- alt="Imagen de la unidad"> -->
                        </td>
                        </tr> 
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
@endsection