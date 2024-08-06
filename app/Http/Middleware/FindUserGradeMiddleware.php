<?php

namespace App\Http\Middleware;

use App\Models\UserGrade;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FindUserGradeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userGrade =UserGrade::whereCode($request->code)->first();
        $request['userGrade']=$userGrade;
        return $next($request);
    }
}
