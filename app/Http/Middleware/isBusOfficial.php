<?php

namespace App\Http\Middleware;

use App\Models\Admin_responsibility;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class isBusOfficial
{
    public function handle(Request $request, Closure $next)
    {
        if(!(Admin_responsibility::query()->where('admin_id','=',Auth::user()->id)->where('responsibility_id','=',2)->exists()))
            return response()->json([
                'message'=>'unauthorized user'
            ],403);

        return $next($request);
    }
}
