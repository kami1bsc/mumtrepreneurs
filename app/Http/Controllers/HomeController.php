<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // return view('home');

        if(Auth::check()){
            if(Auth::user()->isCandidate()) {
                return redirect()->route('candidate.dashboard');
            } else if(Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }else if(Auth::user()->isRecruiter()) {
                return redirect()->route('recruiter.dashboard');
            } 
        } 
    }
}
