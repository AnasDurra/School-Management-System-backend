<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Container\RewindableGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Crypt;


class isAdminMiddleWare
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
        //just for try
//        if( !Auth::check() || Auth::user()->role!=1 )
//            return response()->json([
//                'message'=>'unauthorized user'
//            ],403);
//
//
//        $req_string = json_encode($request->d);
//        $decrypted = Crypt::decryptString($req_string);
//        $decrypted=json_decode($decrypted,true);
//
//       $request->merge($decrypted);

        return $next($request);
    }
}
