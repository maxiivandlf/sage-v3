<?php

namespace App\Imports;

use App\Models\Liquidacion\LiquidacionTempExcelModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LiquidacionImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new LiquidacionTempExcelModel([
            'docu' => $row['docu'] ?? null,
            'cuil' => $row['cuil'] ?? null,
            'trab' => $row['trab'] ?? null,
            'nomb' => $row['nomb'] ?? null,
            'sexo' => $row['sexo'] ?? null,
            'zona' => $row['zona'] ?? null,
            'escu' => $row['escu'] ?? null,
            'plan' => $row['plan'] ?? null,
            'lcat' => $row['lcat'] ?? null,
            'ncat' => $row['ncat'] ?? null,
            'hora' => $row['hora'] ?? null,
            'agru' => $row['agru'] ?? null,
            'area' => $row['area'] ?? null,
            'dias' => $row['dias'] ?? null,




            // ajusta los campos según pedido de Mati
        ]);
    }
}
