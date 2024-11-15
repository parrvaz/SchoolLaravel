<?php

namespace App\Exports;

use App\Models\Course;
use App\Models\Student;
use App\Traits\ServiceTrait;
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
    use ServiceTrait;
    protected $students;
    protected $courses;

    public function __construct($items)
    {
        $this->students = collect($items['students']);
        $this->courses = $items['courses'];
    }
    public function collection()
    {
       return $this->students;
    }

    public function headings(): array
    {
        $courseName = $this->courses->pluck("title")->toArray();
        return array_merge([
            'نام',
            'نام خانوادگی',
            'کلاس',
            'معدل کل',
        ],
            $courseName
        );
    }

    public function map($row): array
    {
        $std = Student::find($row['scores']->first()->student_id);
        $scores = $row['scores'];
        $result =[];
        $result[]= $std->firstName;
        $result[]= $std->lastName;
        $result[]= $std->classroom->title;
        $result[]= $this->zeroChar($row['average']);

        foreach ($this->courses as $course){
            $result[]= $this->zeroChar($scores->where("course_id",$course->id)->first()->score ?? "");
        }

        return $result;
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

                $cellRange = 'A1:I1';

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
