<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use App\Events\AssignTask;

class AdminNavigationController extends Controller
{  
    public function dashboard()
    {
        $users = User::where('type', '2')
        ->where('is_verified', '0')
        ->get();

        return view('admin.dashboard', compact('users'));        
    }

    public function verify_recruiter($user_id)
    {
        try{
            $user = User::where('id', $user_id)->first(['id', 'is_verified']);

            if(empty($user))
            {
                return back()->with('error', 'User does not Exists');
            }

            $user->is_verified = 1;
            $user->save();

            return back()->with('message', 'Recruiter Verified Successfully');
        }catch(\Exception $e)
        {
            return back()->with('error', 'There is some trouble to proceed your action');
        }
    }

    public function delete_user($user_id)
    {
        try{
            $user = User::where('id', $user_id)->first(['id', 'is_verified']);

            if(empty($user))
            {
                return back()->with('error', 'User does not Exists');
            }
            
            $user->delete();

            return back()->with('message', 'User Deleted Successfully');
        }catch(\Exception $e)
        {
            return back()->with('error', 'There is some trouble to proceed your action');
        }
    }

    public function admin_logout()
    {
        try{
            Auth::logout();

            return view('auth.login');
        }catch(\Exception $e)
        {
            return back()->with('error', 'There is some trouble to proceed your action');
        }
    }
    
    public function unverified_recruiters()
    {
        try{
            $users = User::where('type', '2')
            ->where('is_verified', '0')
            ->get();

            return view('admin.users.index', compact('users'));
        }catch(\Exception $e)
        {
            return back()->with('error', 'There is some trouble to proceed your action');
        }
    }

    public function verified_recruiters()
    {
        try{
            $users = User::where('type', '2')
            ->where('is_verified', '1')
            ->get();

            return view('admin.users.verified', compact('users'));
        }catch(\Exception $e)
        {
            return back()->with('error', 'There is some trouble to proceed your action');
        }
    }

    public function candidates()
    {
        try{
            $users = User::where('type', '1')->get();

            return view('admin.users.candidates', compact('users'));
        }catch(\Exception $e)
        {
            return back()->with('error', 'There is some trouble to proceed your action');
        }
    }

    public function event()
    {
        event(new AssignTask());
    }
}
