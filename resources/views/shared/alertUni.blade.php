@php
    $alert = DB::table('historial_mants')
        ->join('programaciones','historial_mants.programacion_id','=','programaciones.id_programacion')
        ->where('programaciones.unidad_id',$unidad_id)
        ->where('historial_mants.estatus','2')
        ->orderBy('historial_mants.created_at','desc')
        ->first();

    $alerts = DB::table('historial_mants')
        ->select('historial_mants.created_at','historial_mants.notas')   
        ->join('programaciones','historial_mants.programacion_id','=','programaciones.id_programacion')
        ->where('programaciones.unidad_id',$unidad_id)
        ->where('historial_mants.estatus','2')
        ->orderBy('historial_mants.created_at','desc')
        ->get();
@endphp

@if (!empty($alert))
<!-- Nav Item - Alerts -->
<a class="nav-link  id="alertsDropdown" role="button"
    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fas fa-bell fa-fw"></i>
    <!-- Counter - Alerts -->
    <span class="badge badge-danger badge-counter">*</span>
</a>
@endif
<!-- Dropdown - Alerts -->
<div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
    aria-labelledby="alertsDropdown">
    <h6 class="dropdown-header">
        Nuevos movimientos
    </h6>
    @if (!empty($alerts))
    @foreach($alerts as $alert)
        <a class="dropdown-item d-flex align-items-center">
            <div class="mr-3">
                <div class="icon-circle bg-primary">
                    <i class="fas fa-file-alt text-white"></i>
                </div>
            </div>
            <div>
                <div class="small text-gray-500">{{$alert->created_at}}</div>
                <span class="font-weight-bold">{{$alert->notas}}</span>
            </div>
        </a>
    @endforeach
    @else 
        <a class="dropdown-item d-flex align-items-center" href="#">
            <div class="mr-3">
                <div class="icon-circle bg-primary">
                    <i class="fas fa-file-alt text-white"></i>
                </div>
            </div>
            <div>
                <div class="small text-gray-500">No hay notas de mantenimientos registrados </div>
            </div>
        </a> 
    @endif
    <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
</div>