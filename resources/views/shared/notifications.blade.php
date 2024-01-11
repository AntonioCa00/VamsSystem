@php
    $notifications = DB::table('logs')
        ->orderBy('logs.created_at','desc')
        ->join('requisiciones','logs.requisicion_id','=','requisiciones.id_requisicion')
        ->where('requisiciones.usuario_id',session('loginId'))
        ->limit(6)
    ->get();
    $count= DB::table('logs')->select(DB::raw('COUNT(*) as total'))
        ->join('requisiciones','logs.requisicion_id','=','requisiciones.id_requisicion')
        ->where('requisiciones.usuario_id',session('loginId'))
    ->get();
@endphp

<!-- Nav Item - Alerts -->
<li class="nav-item dropdown no-arrow mx-1">
    <a class="nav-link dropdown-toggle" id="alertsDropdown" role="button"
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-bell fa-fw"></i>
        <!-- Counter - Alerts -->
        <span class="badge badge-danger badge-counter">{{$count[0]->total}}+</span>
    </a>
    <!-- Dropdown - Alerts -->
    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
        aria-labelledby="alertsDropdown">
        <h6 class="dropdown-header">
            Nuevos movimientos
        </h6>
        @foreach($notifications as $notification)
            <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="mr-3">
                    <div class="icon-circle bg-primary">
                        <i class="fas fa-file-alt text-white"></i>
                    </div>
                </div>
                <div>
                    <div class="small text-gray-500">{{$notification->created_at}}</div>
                    <span class="font-weight-bold">{{$notification->action}}</span>
                </div>
            </a>
        @endforeach
        <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
    </div>
</li>