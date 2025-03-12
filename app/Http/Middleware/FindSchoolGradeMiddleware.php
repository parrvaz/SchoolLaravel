<?php

namespace App\Http\Middleware;

use App\Models\SchoolGrade;
use App\Traits\MessageTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FindSchoolGradeMiddleware
{
    use MessageTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try{
            $code = $request->schoolGrade;
            $schoolGrade =SchoolGrade::whereCode($code)->first();
            if ($schoolGrade==null)
                return $this->error();
            $request->schoolGrade=$schoolGrade;
            return $next($request);
        }catch (\Exception $exception){
            return $this->error();
        }

    }
}
