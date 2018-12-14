<?php
/**
 * Created by PhpStorm.
 * User: flexsin
 * Date: 24/10/18
 * Time: 6:05 PM
 */

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckAdminLogin
{
    /**
     * Check if the admin user is logged in? If yes then it will move further else it will send an message Unauthorised Access
     * and redirects the admin to the login page
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('admin')->user();

        $check_admin = ($user ? true : false);

        if (!$check_admin) {
            return redirect()->route('admin.auth.login')->withInfo('Unauthorised Access');
        }
        return $next($request);
    }
}
