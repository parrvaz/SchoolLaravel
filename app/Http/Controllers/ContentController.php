<?php

namespace App\Http\Controllers;

use App\Http\Resources\Course\ContentCollection;
use App\Http\Resources\Course\CourseCollection;
use App\Models\Content;
use App\Models\Course;
use Illuminate\Http\Request;

class ContentController extends Controller
{
//    public function show(Request $request){
//        return new ContentCollection(Content::where('grade_id',$request->userGrade->grade_id)->paginate(config("constant.bidPaginate")));
//    }
}
