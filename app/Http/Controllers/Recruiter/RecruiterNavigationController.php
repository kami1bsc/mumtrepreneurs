<?php

namespace App\Http\Controllers\Recruiter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RecruiterNavigationController extends Controller
{
    public function dashboard()
    {
        return view('recruiter.dashboard');
    }
}
