<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class PofmhCargoDetailExport implements FromView
{
    use Exportable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.pofmh_cargo_detail', [
            'infoPofMH' => $this->data
        ]);
    }
}
