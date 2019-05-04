<?php

namespace App\Http\Middleware;

use App\Seller;
use Closure;

class SellerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token=request()->get('api_token');
        if ($token!==null) {
            if (Seller::where('api_token', $token)->exists()) {
                return $next($request);
            }
        }
        return response(['result'=>'error']);
    }
}
