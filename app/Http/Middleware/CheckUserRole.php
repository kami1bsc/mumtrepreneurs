<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // return $next($request);

        if(Auth::check())
        {
            $prefix = $request->route()->getPrefix();
            if($prefix == '/admin')
            {
                if(Auth::user()->isCandidate())
                {
                    return redirect()->route('candidate.dashboard');
                }

                if(Auth::user()->isRecruiter())
                {
                    return redirect()->route('recruiter.dashboard');
                }
            }else if($prefix == '/candidate')
            {
                if(Auth::user()->isAdmin())
                {
                    return redirect()->route('admin.dashboard');
                }

                if(Auth::user()->isRecruiter())
                {
                    return redirect()->route('recruiter.dashboard');
                }                
            }else if($prefix == '/recruiter')
            {
                if(Auth::user()->isAdmin())
                {
                    return redirect()->route('admin.dashboard');
                }

                if(Auth::user()->isCandidate())
                {
                    return redirect()->route('candidate.dashboard');
                }                
            }
        }


        return $next($request);
    }
}
