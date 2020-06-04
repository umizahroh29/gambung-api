<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class isPembeli
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

    if(Auth::check()) {
      if(Auth::user()->role != 'ROLPB'){
        return redirect('/home');
      }
    }

    return $next($request);
  }
}
