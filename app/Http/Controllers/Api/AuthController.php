<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Artisan;
use Illuminate\Support\Facades\Hash;
use App\Jobs\AccountVerificationEmailJob;
use App\Jobs\ForgotPasswordEmailJob;
use App\Models\Post;

class AuthController extends Controller
{
    //Signup Method
    public function signup(Request $request)
    {
        try{

            if(!$request->has('email') || $request->email == "")
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Email is Required',
                ], 200);
            }

            $already = User::where('email', $request->email)->first('id');

            if(!empty($already))
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Email has already been Taken',
                ], 200);
            }

            if(!$request->has('password') || $request->password == "")
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Password is Required',
                ], 200);
            }

            $user = new User;
            $user->email = $request->email;      
            $user->password = bcrypt($request->password);
            
            if($request->has('device_type') && $request->device_type != "")
            {
                $user->device_type = $request->device_type;
            }

            if($request->has('token') && $request->token != "")
            {
                $user->token = $request->token;
            }

            $user->save();

            $user1 = User::find($user->id);

            if($user1->profile_image != "" || !empty($user1->profile_image))
            {
                $user1->profile_image = IMAGE_URL.$user1->profile_image;
            }

            return response()->json([
                'status' => true,
                'message' => "Glad You've Joined Us!",
                'data' => $user1,
            ], 200);
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'message' => 'There is some trouble to proceed your action',
            ], 200);
        }
    }

    // Login Method
    public function login(Request $request)
    {        
        $loginData = $request->validate([
            'email' => 'string|required',
            'password' => 'required|max:255'
        ]);
        
        if(!auth()->attempt($loginData))
        {            
            return response()->json([
                'status' => false,
                'message' => 'Invalid Credentials',
            ], 200);                     
        }
       
        if($request->has('device_type') && $request->device_type != "")
        {
            if($request->device_type == 'android' || $request->device_type == 'ios')
            {
                auth()->user()->device_type = $request->device_type;
            }
        }

        if($request->has('token') && $request->token != "")
        {
            auth()->user()->token = $request->token;
        }

        auth()->user()->save();

        $user1 = User::find(auth()->user()->id);

        $user1->profile_image = IMAGE_URL.$user1->profile_image;

        return response()->json([
            'status' => true,
            'message' => 'Welcome back',
            'data' => $user1,
        ], 200);         
    }


    //Recover Password
    public function recover_password($email)
    {
        try{
            $user = User::where('email', $email)->first();

            if(empty($user))
            {
                return response()->json([
                    'status' => false,
                    'message' => 'This Email is not Associated with any Account',
                ],200);
            }

            $details['email'] = $email;
            $details['code'] = rand(111111, 999999);
  
            dispatch(new ForgotPasswordEmailJob($details));       
            
            return response()->json([
                'status' => true,
                'message' => 'A Verification Code has been Sent to your Email!',
                'data' => [
                    'email' => $email,
                    'code' => json_encode($details['code']),
                ],
            ], 200);
            
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'message' => 'There is some trouble to proceed your action',
            ], 200);
        }
    }

    //Reset Password
    public function reset_password(Request $request)
    {
        try{
            $user = User::where('email', $request->email)->first();

            if(empty($user))
            {
                return response()->json([
                    'status' => false,
                    'message' => 'User does not Exists',
                ], 200);
            }

            if(!$request->has('password') || $request->password == "")
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Password is Required',
                ], 200);
            }

            if(!$request->has('confirm_password') || $request->confirm_password == "")
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Confirm Password is Required',
                ], 200);
            }

            if($request->password != $request->confirm_password)
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Password and Confirm Password are not Same',
                ], 200);
            }

            $user->password = bcrypt($request->password);
            $user->save();

            $user1 = User::where('id', $user->id)->first();

            
            if($user1->profile_image != "")
            {
                $user1->profile_image = IMAGE_URL.$user1->profile_image;
            }

            return response()->json([
                'status' => true,
                'message' => 'Password Reset Successfully',
                'data' => $user1,
            ], 200);
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'message' => 'There is some trouble to proceed your action',
            ], 200);
        }
    }
    
    //Edit Profile
    public function edit_profile(Request $request)
    {
        // try{       
            $user = User::where('id', $request->user_id)->first();
            
            if(empty($user))
            {
                return response()->json([
                    'status' => false,
                    'message' => 'User does not Exists',
                ], 200);
            }
            
            if($request->has('username') && $request->username != "")
            {
                $user->username = $request->username;
            }

            if($request->has('community_url') && $request->community_url != "")
            {
                $user->community_url = $request->community_url;
            }

            if($request->has('community_description') && $request->community_description != "")
            {
                $user->community_description = $request->community_description;
            }

            if($request->has('are_you_disciplined') && $request->are_you_disciplined != "")
            {
                $user->are_you_disciplined = $request->are_you_disciplined;
            }

            if($request->has('community_industry') && $request->community_industry != "")
            {
                $user->community_industry = $request->community_industry;
            }

            if($request->has('community_size') && $request->community_size != "")
            {
                $user->community_size = $request->community_size;
            }

            if($request->has('city') && $request->city != "")
            {
                $user->city = $request->city;
            }

            if($request->has('intrested_in_learning') && $request->intrested_in_learning != "")
            {
                $user->intrested_in_learning = $request->intrested_in_learning;
            }

            if($request->has('like_to_contribute') && $request->like_to_contribute != "")
            {
                $user->like_to_contribute = $request->like_to_contribute;
            }

            if($request->has('social_media_url') && $request->social_media_url != "")
            {
                $user->social_media_url = $request->social_media_url;
            }

            if($request->has('device_type') && $request->device_type != "")
            {
                $user->device_type = $request->device_type;
            }            

            if($request->has('token') && $request->token != "")
            {
                $user->token = $request->token;
            }           
            
            if($request->has('image'))
            {
                if($request->image->getClientOriginalExtension() == 'PNG' ||$request->image->getClientOriginalExtension() == 'png' || $request->image->getClientOriginalExtension() == 'JPG' || $request->image->getClientOriginalExtension() == 'jpg' || $request->image->getClientOriginalExtension() == 'jpeg' || $request->image->getClientOriginalExtension() == 'JPEG')
                {
                    $newfilename = md5(mt_rand()) .'.'. $request->image->getClientOriginalExtension();
                    $request->file('image')->move(public_path("/profile_images"), $newfilename);
                    $new_path1 = 'profile_images/'.$newfilename;
                    $user->profile_image = $new_path1;

                }else{
                    return response()->json([
                        'status' => false,
                        'message' => 'Choose a Valid Image!',
                    ], 200);
                }                       
            }
            
            $user->save();

            $user1 = User::find($user->id);

            if($user1->profile_image != "" || !empty($user1->profile_image))
            {
                $user1->profile_image = IMAGE_URL.$user1->profile_image;
            }

            return response()->json([
                'status' => true,
                'message' => 'Profile Info Updated',
                'data' => $user1,
            ], 200);
            
        // }catch(\Exception $e)
        // {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'There is some trouble to proceed your action',
        //     ], 200);
        // }
    }
    
    //Logout
    public function logout($user_id)
    {
        try{
            $user = User::where('id', $user_id)->first();
            
            if(empty($user))
            {
                return response()->json([
                    'status' => false,
                    'message' => 'User does not Exists'
                ], 200);
            }
            
            $user->token = "";            
            $user->save();
            
            return response()->json([
                'status' => true,
                'message' => 'Logged Out'
            ], 200);
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'message' => 'There is some trouble to proceed your action',
            ], 200);
        }
    }
    
    //DeleteAccount
    public function delete_account($user_id)
    {
        try{
            $user = User::where('id', $user_id)->first();
            
            if(empty($user))
            {
                return response()->json([
                    'status' => false,
                    'message' => 'User does not Exists'
                ], 200);
            }
            
            $user->delete();
            
            return response()->json([
                'status' => true,
                'message' => 'Account Deleted'
            ], 200);
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'message' => 'There is some trouble to proceed your action',
            ], 200);
        }
    }

    public function home_screen()
    {
        try{
            $posts = Post::orderBy('id', 'desc')->paginate(12);

            if(!empty($posts))
            {
                foreach($posts as $post)
                {
                    if($post->post_media != "")
                    {
                        $post->post_media = IMAGE_URL.$post->post_media;
                    }else{
                        $post->post_media = "";
                    }

                    $post_user = User::where('id', $post->user_id)->first(['username', 'profile_image']);

                    $post->user_name = $post_user->username;
                    $post->profile_image = IMAGE_URL.$post_user->profile_image;
                }
            }

            return response()->json([
                'status' => true,
                'message' => $posts->count() > 0 ? 'Newsfeed Found' : 'Newsfeed not Found',
                'data' => $posts,
            ], 200);
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'message' => 'There is some trouble to proceed your action',
            ], 200);
        }
    }
}

 