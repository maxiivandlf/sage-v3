<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PofExport implements FromArray, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Agente',
            'Cuil',
            'ApeNom',
            'Sexo',
            'CUECOMPLETO',
            'Nombre_Institucion',
            'Zona',
            'Localidad',
            'Nivel',
            'Origen',
            'SitRev',
            'Horas',
            'Antiguedad',
            'CargoSalarial',
            'CodigoSalarial',
            'Aula',
            'Division',
            'Turno',
            'EspCur',
            'Matricula',
            'FechaAltaCargo',
            'FechaDesignado',
            'Condicion',
            'Activo',
            'Motivo',
            'DatosPorCondicion',
            'FechaDesde',
            'FechaHasta',
            'AgenteR',
            'Presentes',
            'Relevos',
            'Faltas Justificadas',
            'Faltas Injustificadas',
            'Faltas Licencias',
            'Faltas Otros',
            'Observaciones',
            'Carrera',
            'Orientacion',
            'Titulo',
        ];
    }
}
