@extends('plantillaSol')

@section('contenido')

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

@if (session()->has('error'))
    <script type="text/javascript">
        Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Ya has programado un mantenimiento para esta unidad!',
            showConfirmButton: false,
            timer: 1000
        })
    </script>
@endif

    <div class="container-fluid">
        <div class="py-1 d-flex justify-content-between align-items-center">
            <!-- Page Heading -->
            <h1 class="h3 mb-2 text-gray-800">MANTENIMIENTO UNIDADES</h1>
        </div>

        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="card-body">
                <div id='calendar'></div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Programar mantenimiento de la unidad</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">X</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('programarC')}}" method="POST">
                                @csrf
                                <label>Seleccione la fecha en que realizará el siguiente mantenimiento:</label>
                                <div class="form-group">
                                    <input name="date" type="date" class="form-control">
                                </div>
                                <label>¿Qué unidad se le realizará el manteniento?</label>
                                <div class="form-group">
                                    <select name="unidad" class="form-control" required>
                                        <option value="" selected disabled>Selecciona la unidad que se programará el mantenimiento:</option>
                                        @foreach ($unidades as $unidad)
                                            @if ($unidad->tipo != "AUTOMOVIL")
                                                <option value="{{$unidad->id_unidad}}">{{$unidad->n_de_permiso}} - {{$unidad->marca}} {{$unidad->modelo}}</option>
                                            @else
                                                <option value="{{$unidad->id_unidad}}">{{$unidad->id_unidad}} - {{$unidad->marca}} {{$unidad->modelo}}</option>
                                            @endif
                                        @endforeach
                                    </select>
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
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var calendarEl = document.getElementById('calendar');

                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        plugins: [ 'dayGrid', 'interaction' ],
                        header: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,dayGridWeek,dayGridDay'
                        },
                        locale: 'es', // Configurar el idioma a español
                        buttonText: {
                        today: 'Hoy',
                        month: 'Mes',
                        week: 'Semana',
                        day: 'Día'
                    },
                        events: 'calendario/programacion',
                        editable: true,
                        eventLimit: true,
                        eventContent: function(info) {
                            return {
                                html: '<b>' + info.event.title + '</b><br>' + info.event.notas
                            };
                        },
                        dateClick: function(info) {
                        // Check if it's today and no events exist
                        if (info.dayEl.classList.contains('fc-day-today') && info.events.length === 0) {
                            var formattedDate = info.dateStr; // Obtener la fecha seleccionada
                            // Setear la fecha en el input date
                            document.querySelector('input[name="date"]').value = formattedDate;
                            $('#exampleModal').modal('show'); // Abrir el modal
                        } else {
                            var formattedDate = info.dateStr; // Obtener la fecha seleccionada
                            // Setear la fecha en el input date
                            document.querySelector('input[name="date"]').value = formattedDate;
                            $('#exampleModal').modal('show'); // Abrir el modal
                        }
                    },
                    eventClick: function(info) {
                        if (info.event.url) {
                            window.location.href = info.event.url;
                        } else {
                            alert('Event: ' + info.event.title);
                        }
                    }
                    });

                    calendar.render();
                });
            </script>
        </div> <!-- Fin de la clase container-fluid -->
    </div> <!-- Fin de la clase container-fluid -->
@endsection
