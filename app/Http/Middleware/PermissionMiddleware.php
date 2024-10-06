<?php

namespace App\Http\Middleware;

use App\BusinessHasGuest;
use App\Traits\GetTrait;
use App\Traits\MessageTrait;
use App\Traits\Service\GenericServiceTrait;
use App\Traits\ServiceTrait;
use App\Traits\StatisticsTrait;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PermissionMiddleware
{
    use MessageTrait,ServiceTrait;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws ValidationException
     */
    public function handle($request, Closure $next, $role, $guard = null)
    {

        if (app('auth')->guard('api')->guest()) {
            return $this->permissionDeniedForUser();
        }

        $userGrade =$request->userGrade;
        $user = auth('api')->user();
        $userRole = $user->getRoleNames()->first();

        // todo check packages

        //is owner grade
        if( $userGrade->user_id == $user->id || $userRole == "assistant" || $role=="general")
        {
              return $next($request);

//            if ($userGrade->is_active == 1 || $request->isMethod('get'))
//            {
//                return $next($request);
//            }
//            $this->permissionDenied();
        }

        //has permission
        if ($role == $userRole){
            return $next($request);
        }

        return $this->permissionDeniedForUser();
    }


}

//        $permissions = is_array($permission)
//            ? $permission
//            : explode('|', $permission);

//        foreach ($permissions as $permission) {
//        }
