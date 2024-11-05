@extends('plantillaSol')

@section('contenido')
    @if (session()->has('pago'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Se ha registrado su orden de pago!',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    @if (session()->has('editado'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Orden de pago Editada!',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    @if (session()->has('servEditado'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Se ha actualizado el servicio!',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    @if (session()->has('servDelete'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Se ha eliminado el servicio!',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    @if (session()->has('eliminado'))
        <script type="text/javascript">
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Orden de pago Eliminada!',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">PAGOS FIJOS</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">CONSULTAR PAGOS SOLICITADOS</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Folio:</th>
                                <th>Servicio:</th>
                                <th>Estado:</th>
                                <th>Importe:</th>
                                <th>Proveedor:</th>
                                <th>Orden Pago:</th>
                                <th>Opciones:</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pagos as $pago)
                                <tr>
                                    <th>{{ $pago->id_pago }}</th>
                                    <th>{{ $pago->nombre_servicio }}</th>
                                    @if ($pago->estado === 'Pagado')
                                        <th class="font-weight-bold text-success">{{ $pago->estado }}</th>
                                    @else
                                        <th>{{ $pago->estado }}</th>
                                    @endif

                                    <th>${{ $pago->costo_total }}</th>
                                    <th>{{ $pago->nombre }}</th>
                                    <th class="text-center">
                                        <a href="{{ asset($pago->pdf) }}" target="_blank">
                                            <img class="imagen-container" src="{{ asset('img/pago.jpg') }}" alt="Abrir PDF">
                                        </a>
                                    </th>
                                    @if ($pago->estado === 'Pagado')
                                        @if (empty($pago->comprobante_pago))
                                            <th class="font-weight-bold text-info">
                                                Sin comprobante
                                            </th>
                                        @else
                                            <th>
                                                <a href="{{ asset($pago->comprobante_pago) }}" target="_blank">
                                                    Comprobante pago
                                                </a>
                                            </th>
                                        @endif
                                    @else
                                        <th>No se ha realizado el pago</th>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
