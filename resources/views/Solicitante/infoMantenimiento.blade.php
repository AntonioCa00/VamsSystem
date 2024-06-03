@extends('plantillaSol')

@section('contenido')

    @if(!empty($alert))
        <script type="text/javascript">
            Swal.fire({
            title: "Has registrado un mantenimiento incompleto",
            text: "{{$alert->notas}}",
            icon: "warning"
            });
        </script>
    @endif

    @if (session()->has('programado'))
    <script type="text/javascript">
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Se ha programado tu mantenimiento!',
            showConfirmButton: false,
            timer: 1000
        })
    </script>
    @endif

    <div class="container-fluid">
        <div class="card shadow mb-5 ">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                @if($unidad->tipo != "AUTOMOVIL")
                    <h6 class="m-0 font-weight-bold text-primary">Unidad: {{ $unidad->n_de_permiso }}</h6>
                @else 
                    <h6 class="m-0 font-weight-bold text-primary">Unidad {{ $unidad->id_unidad }}</h6>
                @endif        
            </div> <!-- Fin de la clase card-header py-3 -->
            <div class="card-body justify-content-between">
                @if (!empty($programacion))
                    @if($programacion->dias <=0 )
                        <h5 class="mb-2 m-0 font-weight-bold text-danger">Se ha vencido la fecha de tu mantenimiento</h5>                    
                    @elseif($programacion->dias > 0 && $programacion->dias <= '3')
                        <h5 class="mb-2 m-0 font-weight-bold text-danger">Faltan {{$programacion->dias}} días para realizar tu mantenimiento</h5>                    
                    @elseif ($programacion->dias > '3' && $programacion->dias <= '10')
                        <h5 class="mb-2 m-0 font-weight-bold text-warning">Faltan {{$programacion->dias}} días para realizar tu mantenimiento</h5>
                    @else 
                        <h5 class="mb-2 m-0 font-weight-bold text-success">Faltan {{$programacion->dias}} días para realizar tu mantenimiento</h5>
                    @endif
                @endif
                @if (!empty($programacion->notas))
                    <h5 class="mb-2 m-0 font-weight-bold">Notas del mantenimiento: {{$programacion->notas}}</h5>
                @endif
                <div class="row">
                    <div class="col-md-6 container-fluid">
                        <div class="card">
                            <div class="card-body">
                                <div class="justify-content-left">
                                    <h5>Las refacciones que requieren cambio proximamente son:</h5>
                                    @foreach ($refacciones as $refaccion)                                        
                                        <ul>
                                            @if (($unidad->contador+1)%$refaccion->ciclo == 0) 
                                                <li>
                                                    <h6 style="color: red;"><strong>{{strtoupper($refaccion->nombre)}}</strong></h6>
                                                </li>
                                            @endif                                             
                                        </ul>                             
                                    @endforeach                                    
                                </div> <!-- Fin de la clase col-md-12 -->
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 container-fluid">
                        <div class="card">
                            <div class="card-body">
                                <div class="justify-content-left">
                                    <h5>Las refacciones que requieren limpieza proximamente son:</h5>
                                    @foreach ($refacciones as $refaccion)
                                        <ul>
                                            @if (($unidad->contador+1)%$refaccion->ciclo != 0) 
                                                <li>
                                                    <h6 style="color: orange;"><strong>{{strtoupper($refaccion->nombre)}}</strong></h6>
                                                </li>
                                            @endif
                                        </ul>                             
                                    @endforeach
                                </div>     
                                <div class="py-1 d-flex justify-content-between">
                                    @if (empty($programacion))
                                        <a class="btn bg-gradient-info text-white"href="#"
                                            data-toggle="modal" data-target="#programacion"
                                            style="width: 200px; font-size: 13px;">
                                            PROGRAMAR mantenimiento
                                        </a>

                                        <!--Modal para programar un mantenimiento-->
                                        <div class="modal fade" id="programacion" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Programar mantenimiento de la unidad</h5>
                                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">X</span>
                                                            </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('programar',$unidad->id_unidad)}}" method="POST">
                                                            @csrf
                                                            <label>Seleccione la fecha en que realizará el siguiente mantenimiento:</label>
                                                            <div class="form-group">                                                                
                                                                <input name="date" type="date" class="form-control">
                                                            </div>
                                                            <label>Notas del mantenimiento:</label>
                                                            <div class="form-group">                                                                                                                                
                                                                <input name="notas" type="input" class="form-control">
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">Programar mantenimiento</button>
                                                        </form>
                                                    </div>                        
                                                </div>
                                            </div>
                                        </div>
                                    @else 
                                        <a class="btn bg-gradient-info text-white"href="#"
                                        data-toggle="modal" data-target="#reprogramacion"
                                        style="width: 200px; font-size: 13px;">
                                        RE-PROGRAMAR mantenimiento
                                        </a>

                                        <!--Modal para programar un mantenimiento-->
                                        <div class="modal fade" id="reprogramacion" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Re-programar mantenimiento de la unidad</h5>
                                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">X</span>
                                                            </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('reprogramar',['unidad'=>$unidad->id_unidad, 'progra' =>$programacion->id_programacion])}}" method="POST">
                                                            @csrf
                                                            <label>Seleccione la fecha en que realizará el siguiente mantenimiento:</label>
                                                            <div class="form-group">                                                                                                                            
                                                                <input value="{{$programacion->fecha_progra}}" name="date" type="date" class="form-control" required>
                                                            </div>
                                                            <label>Notas del mantenimiento:</label>
                                                            <div class="form-group">                                                                
                                                                <input value="{{$programacion->notas}}" name="notas" type="input" class="form-control">
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">Programar mantenimiento</button>
                                                        </form>
                                                    </div>                        
                                                </div>
                                            </div>
                                        </div>
                                        <a class="btn bg-gradient-success text-white"href="#"
                                        data-toggle="modal" data-target="#registrarM"
                                        style="width: 200px; font-size: 13px;">
                                        REGISTRAR mantenimiento
                                        </a>    
                                        
                                        <!--Modal para programar un mantenimiento-->
                                        <div class="modal fade" id="registrarM" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Registrar la realización de un mantenimiento</h5>
                                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">X</span>
                                                            </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('registrarM',$programacion->id_programacion)}}" method="POST">    
                                                            @csrf
                                                            <label>¿Se realizó completo el mantenimiento?</label>
                                                            <div class="form-group">                                                            
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="estatus" id="inlineRadio1" value="1" checked>
                                                                    <label class="form-check-label" for="inlineRadio1">Sí</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="estatus" id="inlineRadio2" value="2">
                                                                    <label class="form-check-label" for="inlineRadio2">No</label>
                                                                </div>
                                                            </div>
                                                            <label>Ultimo kilometraje:</label>
                                                            <div class="form-group">                                                            
                                                                <input name="kms" type="input" class="form-control" required>
                                                            </div>
                                                            <label>Notas del mantenimiento:</label>
                                                            <div class="form-group">                                                            
                                                                <input name="notas" type="input" class="form-control">
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">Registrar mantenimiento</button>
                                                        </form>
                                                    </div>                        
                                                </div>
                                            </div>
                                        </div>
                                    @endif                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
@endsection
