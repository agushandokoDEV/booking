<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\RolesAccess;
class RolesUserAccess
{
    use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $access)
    {
        $auth = Auth::user();
        $access = RolesAccess::where('role_id', $auth->role_id)->where('menu_code', $access)->first();
        if (!$access) {
            return $this->forbiddenResponse('Akses tidak terdaftar');
        }
        if (!$access->allowed) {
            return $this->forbiddenResponse();
        }
        return $next($request);
    }
}
