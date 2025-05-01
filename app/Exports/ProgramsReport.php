<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class ProgramsReport implements FromView,ShouldAutoSize
{
    use Exportable;

    public function __construct($data){
        $this->data = $data;
    }

    public function view() : View
    {
        return view('Reports.excel.ProgramsReport', ['data' => $this->data]);
    }

   
}

