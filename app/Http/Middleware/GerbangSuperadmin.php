<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GerbangSuperadmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $superadmin = $request->session()->get('posisi');
        if ($superadmin === 'superadmin') {
            return $next($request);
        }else {
            return redirect('welcome')->with('warning','Hanya Superadmin yang dapat mengakses');
        }
    }
}
