<?php

namespace App\Exports;

use App\Traits\ServiceTrait;
use Hamcrest\SelfDescribing;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class ExamExport implements FromCollection,WithMapping,WithHeadings, ShouldAutoSize, WithEvents,WithStyles
{
    use ServiceTrait;
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
            'نام',
            'نام خانوادگی',
            'کلاس',
            'نمره',
            'بازخورد',
        ];
    }

    public function map($row): array
    {
        return [
            $row->student->firstName,
            $row->student->lastName,
            $row->student->classroom->title,
            $this->zeroChar($row->score),
            $row->rank ?? null,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        foreach ($sheet->getRowIterator() as $row) {
            $index= $row->getRowIndex();

            // تنظیم ارتفاع برای هر ردیف
            $sheet->getRowDimension($index)->setRowHeight(30); // ارتفاع مورد نظر
            // تنظیم وسط‌چین کردن افقی و عمودی
            $sheet->getStyle($index)->getAlignment()->setHorizontal('center');
            $sheet->getStyle($index)->getAlignment()->setVertical('center');

        }
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

                $cellRange = 'A1:E1';

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
