<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CandidateNavigationController extends Controller
{
    public function dashboard()
    {
        return view('candidate.dashboard');
    }
}
