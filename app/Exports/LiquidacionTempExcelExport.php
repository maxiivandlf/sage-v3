<?php

namespace App\Exports;

use App\Models\Liquidacion\LiquidacionTempExcelModel;
use Maatwebsite\Excel\Concerns\FromCollection;

class LiquidacionTempExcelExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return LiquidacionTempExcelModel::all();
    }
}
