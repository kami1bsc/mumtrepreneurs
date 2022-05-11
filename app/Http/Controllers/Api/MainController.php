<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use URL;
use Carbon\Carbon;
use App\Models\SecurityPackage;
use App\Models\SecurityRequest;
use App\Models\Rating;

class MainController extends Controller
{
    //User Model
    public function user_model($user_id)
    {
        $user = User::where('id', $user_id)->first(['id', 'id_number','name', 'username', 'profile_image', 'phone', 'latitude', 'longitude', 'security_type', 'dress_code', 'average_rating','is_online', 'is_verified', 'type','created_at']);
        
        if(!empty($user))
        {
            if($user->profile_image != "")
            {
                $user->profile_image = IMAGE_URL.$user->profile_image;
            }   
        }
        
        $user->created_time = $user->created_at->diffForHumans();
        
        if($user->type == '1')
        {
            $user->total_rides = SecurityRequest::where('user_id', $user_id)->count();
        }
        
        if($user->type == '2')
        {
            $user->total_rides = SecurityRequest::where('security_id', $user_id)->count();
        }
        
        return $user;
    }

}
 