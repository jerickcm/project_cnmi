<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromArray;
use Carbon\Carbon;

class WFListing_data  implements FromArray, WithHeadings, ShouldAutoSize
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
                $content['full_name'],
                $content['major_soc_code'],
                $content['minor_soc_code'],
                $content['position'],
                $content['employment_status'],
                $content['wage'],
                $content['country_of_citizenship'],
                $content['visa_type_class'],
                $content['employment_start_date'],
                $content['employment_end_date'],
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
        ];
    }

    public function headings(): array
    {
        return [
            "No.",
            "Employee Name",
            "Major Soc Code",
            "Minor Soc Code",
            "Position",
            "Employment Status",
            "Wage",
            "Country of Citizenship",
            "VISA TYPE / CLASS",
            "Start Date of Employment",
            "End Date of Employment",
        ];
    }
}
