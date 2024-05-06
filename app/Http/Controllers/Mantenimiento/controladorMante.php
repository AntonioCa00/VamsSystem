<?php

namespace App\Http\Controllers\Mantenimiento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unidades;
use App\Models\CamionServicioPreventivo;
use Carbon\Carbon;


class controladorMante extends Controller
{
    public function mantenimiento (){
        // Recupera las unidades que cumplen con los criterios especificados y las ordena por 'id_unidad'
        $unidades = Unidades::leftJoin('camion_servicios_preventivos as servicios','unidades.id_unidad','=','servicios.unidad_id')
        ->where('estatus','1')
        ->where('id_unidad','!=','1')
        ->where('estado','Activo')->orderBy('id_unidad','asc')->get();

        $unidades->each(function ($unidad) {
            $filtro_aireG = 100-((($unidad->kilometraje-$unidad->filtro_aire_grande)/30000)*100);
            $filtro_aireC = 100-((($unidad->kilometraje-$unidad->filtro_aire_chico)/45000)*100);
            $filtro_diesel = 100-((($unidad->kilometraje-$unidad->filtro_diesel)/15000)*100);
            $filtro_aceite = 100-((($unidad->kilometraje-$unidad->filtro_aceite)/15000)*100);
            $wk1060_trampa = 100-((($unidad->kilometraje-$unidad->wk1016_trampa)/45000)*100);
            $aceite_motor = 100-((($unidad->kilometraje-$unidad->aceite_motor)/15000)*100);
            $filtro_urea = 100-((($unidad->kilometraje-$unidad->filtro_urea)/45000)*100);
            $anticongelante = 100-((($unidad->kilometraje-$unidad->anticongelante)/100000)*100);
            $aceite_direccion = 100-((($unidad->kilometraje-$unidad->aceite_direccion)/150000)*100);
            $banda_poles = 100-((($unidad->kilometraje-$unidad->banda_poles)/90000)*100);
            $ajuste_frenos = 100-((($unidad->kilometraje-$unidad->ajuste_frenos)/15000)*100);
            $engrasado_chasis = 100-((($unidad->kilometraje-$unidad->engrasado_chasis)/15000)*100);

            $promedio = ($filtro_aireG + $filtro_aireC + $filtro_diesel + $filtro_aceite + $wk1060_trampa +
            $aceite_motor + $filtro_urea + $anticongelante + $aceite_direccion + $banda_poles +
            $ajuste_frenos + $engrasado_chasis) / 12; // Dividiendo por 12 para obtener el promedio de los 12 parámetros

            $unidad->tiempo = $promedio; // Agregar el promedio como un nuevo atributo
        });
        
        // Carga y muestra la vista con el listado de unidades activas
        return view('Solicitante.mantenimiento',compact('unidades'));
    }

    public function infoMantenimiento ($id){
        $unidad = Unidades::where('id_unidad',$id)->first();
        $kmInicial = $unidad->kilometraje;
        $servicio = CamionServicioPreventivo::where('unidad_id',$id)->first();

        $filtro_aireG = 100-((($kmInicial-$servicio->filtro_aire_grande)/30000)*100);
        $filtro_aireC = 100-((($kmInicial-$servicio->filtro_aire_chico)/45000)*100);
        $filtro_diesel = 100-((($kmInicial-$servicio->filtro_diesel)/15000)*100);
        $filtro_aceite = 100-((($kmInicial-$servicio->filtro_aceite)/15000)*100);
        $wk1060_trampa = 100-((($kmInicial-$servicio->wk1016_trampa)/45000)*100);
        $aceite_motor = 100-((($kmInicial-$servicio->aceite_motor)/15000)*100);
        $filtro_urea = 100-((($kmInicial-$servicio->filtro_urea)/45000)*100);
        $anticongelante = 100-((($kmInicial-$servicio->anticongelante)/100000)*100);
        $aceite_direccion = 100-((($kmInicial-$servicio->aceite_direccion)/150000)*100);
        $banda_poles = 100-((($kmInicial-$servicio->banda_poles)/90000)*100);
        $ajuste_frenos = 100-((($kmInicial-$servicio->ajuste_frenos)/15000)*100);
        $engrasado_chasis = 100-((($kmInicial-$servicio->engrasado_chasis)/15000)*100);

        $datos [] = [
            "filtro_aireC" =>$filtro_aireG,
            "filtro_aireG" =>$filtro_aireC,
            "filtro_diesel" =>$filtro_diesel,
            "filtro_aceite" =>$filtro_aceite,
            "wk1060_trampa" =>$wk1060_trampa,
            "aceite_motor" =>$aceite_motor,
            "filtro_urea" =>$filtro_urea,
            "anticongelante" =>$anticongelante,
            "aceite_direccion" =>$aceite_direccion,
            "banda_poles" =>$banda_poles,
            "ajuste_frenos" =>$ajuste_frenos,
            "engrasado_chasis" =>$engrasado_chasis,
        ];

        return view('Solicitante.infoMantenimiento',compact('unidad','datos'));
    }

    public function updateKilom(Request $req, $id){
        $kilometraje = $req->kilometraje;

        Unidades::where('id_unidad',$id)->update([
            "kilometraje"=>$kilometraje,
            "updated_at"=>Carbon::now(),
        ]);

        return back()->with('kilometraje','kilometraje');
    }

    public function actualizarkms(Request $request)
    {
        // Validar que se haya subido un archivo
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Obtener el archivo subido
        $file = $request->file('file');

        // Cargar el archivo Excel
        $spreadsheet = IOFactory::load($file);

        // Obtener la primera hoja del archivo
        $sheet = $spreadsheet->getActiveSheet();

        // Obtener el número total de filas y columnas
        $highestRow = $sheet->getHighestDataRow();
        $highestColumn = $sheet->getHighestDataColumn();        

        // Iterar sobre las filas y leer los datos
        for ($row = 1; $row <= $highestRow; $row++) {
            // Obtener los datos de la celda A (id_unidad) y G (kilometraje) de cada fila
            $id_unidad = $sheet->getCell('A' . $row)->getValue();
            $kilometraje = $sheet->getCell('G' . $row)->getValue();

            // Verificar si la unidad existe en la base de datos
            $unidad = Unidades::where('id_unidad', $id_unidad)->first();

            if ($unidad) {
                // Si la unidad existe, actualizar el kilometraje
                Unidades::where('id_unidad',$unidad->id_unidad)->update([
                    "kilometraje" => $kilometraje,
                    "updated_at" => Carbon::now(),
                ]);
            }
        }

        return redirect()->back()->with('importado', 'importado');
    }   
}