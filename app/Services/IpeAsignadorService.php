<?php

namespace App\Services;

use App\Models\liquidacion\AgentesAgrupadosModel;
use Illuminate\Support\Collection;

class IpeAsignadorService
{
    /**
     * Determina si corresponde asignar 'SI' al campo s_ipe para una persona según sus agrupamientos.
     */
    public function asignar(array $preliq, Collection $agrup): array
    {

        $condicionesFase1 = [
            'ACTUAL',
            'DESDOBLAMIENTO',
            'VOLANTE',
            'ITINERANTE',
            'PERMUTA',
            'TRASLADO',
            'REUBICACIÓN',
            'CONTRATO'
        ];

        $cumpleCondicionFase1 = $agrup->first(function ($agente) use ($condicionesFase1) {
            // dd($agente->Activo);
            // $agenteActivo = in_array($agente->Condicion, $condicionesFase1) && $agente->Activo === 'SI';
            // dd($agenteActivo);
            return in_array($agente->Condicion, $condicionesFase1) && $agente->Activo === 'SI';
        });


        $origenesDirectivos = [
            'DIRECTOR C/ANEXO',
            'DIRECTOR/A',
            'RECTOR/DIRECTOR',
            'VICE RECTOR',
            'VICE RECTOR/REGENTE',
            'VICEDIRECTOR',
            'VICERECTOR/VICEDIRECTOR',
            'JEFE DE PRECEPTORES',
            'PRECEPTOR DE ALBERGUE',
            'PRECEPTOR/A',
            'REGENTE',
            'BEDEL',
            'SECRETARIO/A',
            'RECTOR',
            'RECTOR/REGENTE',
            'SUPERVISOR',
            'PRO SECRETARIO'
        ];


        $cumpleCondicionFase2 = $agrup->first(function ($a) use ($origenesDirectivos) {
            return $a->Condicion === 'ACTUAL' && in_array($a->Origen, $origenesDirectivos);
        });


        $codigosCargoEspecial = [
            'D07',
            'D08',
            'D10',
            'D12',
            'D13',
            'D15',
            'D17',
            'D19',
            'D20',
            'D23',
            'D24',
            'D25',
            'D26',
            'D27',
            'D32',
            'D33',
            'D34',
            'E03',
            'E05',
            'E07',
            'E09',
            'E10',
            'E15',
            'E17',
            'E21',
            'E22',
            'E24',
            'E27',
            'E30',
            'E31',
            'E33',
            'E34',
            'E36',
            'E39',
            'E40',
            'E41',
            'E43',
            'E44',
            'E46',
            'E47',
            'E49',
            'E51',
            'E55',
            'E59',
            'E60',
            'E61',
            'E63',
            'E65',
            'E66',
            'E68',
            'E69',
            'E70',
            'E71',
            'E72',
            'E85',
            'E88',
            'E89',
            'E92',
            'E93',
            'E94',
            'E96',
            'E98',
            'E99'
        ];


        $cumpleCondicionFase3 = $agrup->first(function ($a) use ($codigosCargoEspecial) {
            return $a->Condicion === 'ACTUAL' && collect($codigosCargoEspecial)->contains(fn($c) => str_contains($a->Cargo, $c));
        });


        $cumpleCondicionFase4 = $agrup->first(function ($a) {
            return $a->Origen === 'PERSONAL ANEXO'
                && $a->SitRev === 'VOLANTE'
                && !str_contains($a->Condicion, 'BAJA')
                && !str_contains($a->Condicion, 'RENUNCIA');
        });

        //Encontrar si la unidad de liquidacion de pre liquidacion escu,area,conincida con las unidades de liq de (arup.escu1, agrup.area2) (agrup.escu2,agrup.area2)etc, si hay conincidencia devolver en una variable 

        if ($cumpleCondicionFase1 || $cumpleCondicionFase2 || $cumpleCondicionFase3 || $cumpleCondicionFase4) {
            $IPE =  'SI';
        } else {
            $IPE = 'NO';
        }

        //retornar un array con las variables de ip y unidades de liquidacion validadas con un true o false por liquidacion, si hay incosistencia retornar la incosnsistencia
        $unidadPreliq = strtoupper(trim($preliq['escu'] ?? '') . trim($preliq['area'] ?? ''));

        $coincidencias = [];
        $inconsistencias = [];

        foreach ($agrup as $_agenteAgrup) {

            for ($i = 1; $i <= 3; $i++) {
                // dd($_agenteAgrup);

                $escu = $_agenteAgrup["escu{$i}"] ?? null;
                $area = $_agenteAgrup["area{$i}"] ?? null;

                if ($escu && $area) {
                    $unidadAgrup = strtoupper(trim($escu) . trim($area));

                    $coincide = $unidadPreliq === $unidadAgrup;
                    $coincidencias[] = [
                        'UnidadDePago' => $unidadPreliq,
                        "UnidadDeclarada{$i}" => $escu . $area,
                        'coincide' => $coincide
                    ];
                } else {
                    $inconsistencias[] = "Faltan datos en escu{$i} o area{$i}";
                }
            }
        }

        return [
            'ipe' => $IPE,
            'unidadesValidadas' => $coincidencias,
            'inconsistencias' => $inconsistencias
        ];
    }
}
