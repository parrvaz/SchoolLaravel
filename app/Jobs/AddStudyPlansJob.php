<?php

namespace App\Jobs;

use App\Models\Plan;
use App\Models\StudyPlan;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Morilog\Jalali\Jalalian;

class AddStudyPlansJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $nowInTehran = Carbon::now('Asia/Tehran');
        $jalaliDate = Jalalian::fromCarbon($nowInTehran)->getDayOfWeek();
        $date = Jalalian::fromCarbon(Carbon::yesterday('Asia/Tehran'));

        $allPlanStudents = Plan::with("students")
            ->with(['coursePlans' => function ($query) use ($jalaliDate) {
                $query->where('day', $jalaliDate);
            }])
            ->get();

        $data = [];
        foreach ($allPlanStudents as $item) {
            foreach ($item->students as &$itemStd) {
                $planCourses = $item->coursePlans;
                foreach ($planCourses as $planCours) {
                    $data[] = [
                        'student_id' => $itemStd->id,
                        'course_id' => $planCours->course_id,
                        "date" => $date,
                        "start" => $planCours->start,
                        "end" => $planCours->end,
                    ];
                }
            }

            if (count($data) > 200)
            {
                StudyPlan::insert($data);
                $data=[];
            }
        }
        StudyPlan::insert($data);
    }
}
