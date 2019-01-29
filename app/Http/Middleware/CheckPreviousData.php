<?php

namespace App\Http\Middleware;

use Closure;

class CheckPreviousData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next , $step, $param)
    {
        $lease_id = $request->route($param);
        if($lease_id)
        {

            $confrim_steps = \App\LeaseCompletedSteps::query()->where('lease_id', '=', $lease_id)->where('completed_step','=', $step)->count();
            if($confrim_steps > 0){
                return $next($request);
            } else {
                abort(404);
            }
        }
        return $next($request);
    }
}
