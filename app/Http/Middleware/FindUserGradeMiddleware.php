<?php

namespace App\Http\Middleware;

use App\Models\UserGrade;
use App\Traits\MessageTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FindUserGradeMiddleware
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
            $code = $request->userGrade;
            $userGrade =UserGrade::whereCode($code)->first();
            if ($userGrade==null)
                return $this->error();
            $request->userGrade=$userGrade;
            return $next($request);
        }catch (\Exception $exception){
            return $this->error();
        }

    }
}
