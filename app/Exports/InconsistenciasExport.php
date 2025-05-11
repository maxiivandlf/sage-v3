<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InconsistenciasExport implements FromArray, WithHeadings
{
    protected $inconsistencias;

    public function __construct(array $inconsistencias)
    {
        $this->inconsistencias = $inconsistencias;
    }

    public function array(): array
    {
        $data = [];

        foreach ($this->inconsistencias as $inc) {
            $data[] = [
                'Tipo de Inconsistencia' => $inc['tipo'],
                'Nombre Archivo'         => $inc['dato']->nombre ?? $inc['temp']->nombre,
                'DNI Archivo'            => $inc['dato']->dni ?? $inc['temp']->dni,
                'Plaza Archivo'          => $inc['dato']->plaza ?? $inc['temp']->plaza,
                'Nombre Oficial'         => $inc['oficial']->nombre ?? '',
                'DNI Oficial'            => $inc['oficial']->dni ?? '',
                'Plaza Oficial'          => $inc['oficial']->plaza ?? '',
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Tipo de Inconsistencia',
            'Nombre Archivo',
            'DNI Archivo',
            'Plaza Archivo',
            'Nombre Oficial',
            'DNI Oficial',
            'Plaza Oficial'
        ];
    }
}
