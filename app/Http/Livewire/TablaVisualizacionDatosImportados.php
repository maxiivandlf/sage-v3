<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Liquidacion\LiquidacionTempExcelModel;

class TablaVisualizacionDatosImportados extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $columnas = [];
    public $columnasTraducidas = [];

    public function mount()
    {
        $this->columnas = array_keys(
            LiquidacionTempExcelModel::select('docu', 'cuil', 'trab', 'nomb', 'zona')->first()?->toArray() ?? []
        );

        $this->columnasTraducidas = [
            'docu' => 'Documento',
            'cuil' => 'CUIL',
            'trab' => 'Trabajo',
            'nomb' => 'Apellido y Nombre',
            'zona' => 'Zona',
        ];
    }

    public function render()
    {
        $datosImportados = LiquidacionTempExcelModel::select('docu', 'cuil', 'trab', 'nomb', 'zona')
            ->orderBy('id', 'desc')
            ->paginate(25);

        return view('livewire.tabla-visualizacion-datos-importados', compact('datosImportados'));
    }
}
