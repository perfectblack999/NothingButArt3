<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                $numberOfPreviews = 6;
                $totalImageCount = DB::select('SELECT COUNT(id) as count FROM images WHERE user <> 0');
                $numberOfBatches = floor($totalImageCount[0]->count / $numberOfPreviews);
                $offsetBatch = rand(0, $numberOfBatches);
                $offset = $offsetBatch * $numberOfBatches;
                $previewIDPaths = DB::select("SELECT id,path FROM images WHERE user <> 0 LIMIT $numberOfPreviews OFFSET $offset");                
                
                return redirect()->guest('/login')->with('name', 'nkem');
                
//                return redirect()->guest('login');
            }
        }

        return $next($request);
    }
}
