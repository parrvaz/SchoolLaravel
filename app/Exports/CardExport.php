<?php

namespace App\Exports;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class CardExport implements FromCollection,WithMapping,WithHeadings, ShouldAutoSize, WithEvents,WithStyles
{
    protected $items;

    public function __construct($items)
    {
        $this->items = $items;
    }
    public function collection()
    {
       return $this->items;
    }

    public function headings(): array
    {
        return [
            'نام درس',
            'ضریب درس',
            'نمره',
        ];
    }

    public function map($row): array
    {
        return [
            Course::find($row->course_id)->name,
            $row->factor,
            $row->score,
        ];
    }

    public function styles(Worksheet $sheet)
    {
//        foreach ($sheet->getRowIterator() as $row) {
//            $index= $row->getRowIndex();
//
//            // تنظیم ارتفاع برای هر ردیف
//            $sheet->getRowDimension($index)->setRowHeight(30); // ارتفاع مورد نظر
//            // تنظیم وسط‌چین کردن افقی و عمودی
//            $sheet->getStyle($index)->getAlignment()->setHorizontal('center');
//            $sheet->getStyle($index)->getAlignment()->setVertical('center');
//
//        }
    }
    public function registerEvents(): array
    {

        Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
            $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
        });

        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->setRightToLeft(true);
                $styleArray = array('fill' => array(
                    'color' => array('rgb' => '#ff0000')
                ));

                $cellRange = 'A1:C1';

                $event->sheet->styleCells(
                    $cellRange,
                    [

                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'color' => ['argb' => 'BB66d979']
                        ]

                    ]
                );
            },
        ];
    }
}
