<?php

namespace App\Exports;


use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromArray;
use Carbon\Carbon;

class ReportWorkforcePlanTallyExport implements FromArray, WithHeadings, ShouldAutoSize
{
    use Exportable;
    public $count;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function array(): array
    {
        $contents = $this->items;

        foreach ($contents as $content) {
            $this->count++;
            $data[] =  [
                $this->count,
                $content['company_name'],
                $content['name_and_position'],
                $content['dba'],

                $content['day'],
                $content['month'],
                $content['year'],
                $content['quarter'],

            ];
        }

        return $data;
    }
    public function columnWidths(): array
    {
        return [
            'A' => 4,
            'B' => 20,
            'C' => 20,
            'D' => 20,

            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 20,
        ];
    }

    public function headings(): array
    {


        return [
            "No.",
            "Company Name",
            "Name and Position",
            "DBA",

            "Day",
            "Month",
            "Year",
            "Quarter",
        ];
    }
}
