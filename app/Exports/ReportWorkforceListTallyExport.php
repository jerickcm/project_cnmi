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

class ReportWorkforceListTallyExport implements FromArray, WithHeadings, ShouldAutoSize
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
                $content['fulltime_us_workers'],
                $content['parttime_us_workers'],
                $content['fulltime_non_us_workers'],

                $content['parttime_non_us_workers'],
                $content['name_and_position'],
                $content['year_and_quarter'],
                $content['company_name'],
                $content['dba'],

                $content['day'],
                $content['month'],
                $content['year'],
                $content['quarter'],
                // $content['year'],

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
            'J' => 20,

            'K' => 20,
            'L' => 20,
            'M' => 20,
            'N' => 20,
            'O' => 20,
        ];
    }

    public function headings(): array
    {
        return [
            "No.",
            "Company Name",
            "Fulltime US workers",
            "Parttime US workers",
            "Fulltime non-US workers",

            "Parttime non-US workers",
            "Name and position",
            "Year and Quarter",
            "Company Name",
            "DBA",

            "Day",
            "Month",
            "Year",
            "Quarter",
        ];
    }
}
