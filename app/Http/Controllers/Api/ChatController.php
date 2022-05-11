<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use App\Events\GetMessanger;
use App\Events\NotificationCounter;
use App\Events\GetMessages;
use App\Events\ChatNotificationEvent;
use App\Events\AssignTask;
use App\Events\MessageCounter;

class ChatController extends Controller
{
    public function user_model($user_id)
    {
        $user = User::where('id', $user_id)->first(['id', 'id_number', 'name', 'username', 'profile_image', 'type']);
        $user->profile_image = IMAGE_URL.$user->profile_image;
        return $user;
    }

    public function get_messanger($user_id)
    {
        try{
            $user = User::find($user_id);
            
            if(empty($user))
            {
                return response()->json([
                    'status' => false,
                    'message' => 'User does not Exists!',                    
                ], 200);
            }
            
            $user_array = array();
            
            $latest_messages = Message::orWhere('from_id', $user_id)           
            ->orWhere('to_id', $user_id)
            ->orderBy('time', 'desc')
            ->get();
            
            if(!empty($latest_messages))
            {
                foreach($latest_messages as $message)
                {
                    if($message->from_id == $user_id)
                    {
                        array_push($user_array, $message->to_id);
                        
                    }else if($message->to_id == $user_id){
                        
                        array_push($user_array, $message->from_id);
                    }
                }
            }
            
            $arr = array_unique($user_array);
        
            $arr1 = array();
            
            if(!empty($arr))
            {
                foreach($arr as $ar)
                {
                    array_push($arr1, $ar);
                }
            }
            
            // return $arr1; // messanger array;
            $messanger_array = array();
            
            if(sizeof($arr1) > 0)
            {
                foreach($arr1 as $ar1)
                {
                    // return $ar1;
                    
                    $message1 = Message::where('from_id', $ar1)
                    ->where('to_id', $user_id)
                    ->orderBy('time', 'desc')
                    ->first(['id', 'from_id', 'to_id', 'text', 'media', 'media_type', 'time', 'seen']);
                    
                    $message2 = Message::where('from_id', $user_id)
                    ->where('to_id', $ar1)
                    ->orderBy('time', 'desc')
                    ->first(['id', 'from_id', 'to_id', 'text', 'media', 'media_type', 'time', 'seen']);
                    
                    if(!empty($message1) && !empty($message2))
                    {
                        if($message1->id > $message2->id)
                        {
                            array_push($messanger_array, $message1);
                        }else{
                            array_push($messanger_array, $message2);
                        }
                    }else if(empty($message1) && !empty($message2))
                    {
                        array_push($messanger_array, $message2);
                    }else if(empty($message2) && !empty($message1))
                    {
                        array_push($messanger_array, $message1);
                    }
                }
            }
            
            $inbox = array();
            
            if(sizeof($messanger_array) > 0)
            {
                foreach($messanger_array as $messanger)
                {
                    if($messanger->to_id == $user_id)
                    {
                        $to1 = Message::where('to_id', $user_id)
                        ->where('from_id', $messanger->from_id)
                        ->where('seen', 'false')
                        ->count();
                        
                        $user_details = User::where('id', $messanger->from_id)->first();
                        
                        $messanger->other_user_id = $user_details->id;
                        // if(empty($user_details->name) || $user_details->name == "" || $user_details->name == null)
                        // {
                        //     $messanger->other_user_name = $user_details->username;
                        // }else{
                        //     $messanger->other_user_name = $user_details->name;    
                        // }
                        $messanger->time = json_decode($messanger->time);
                        $messanger->other_user_name = $user_details->username;
                        $messanger->other_user_avatar = IMAGE_URL.$user_details->profile_image;
                        $messanger->other_user_type = $user_details->type;
                        $messanger->unread_messages = $to1;
                        
                        array_push($inbox, $messanger);
                    }else if($messanger->from_id == $user_id)
                    {
                        $user_details = User::where('id', $messanger->to_id)->first();
                        
                        // $from1 = Message::where('from_id', $user_id)
                        // ->where('to_id', $messanger->to_id)
                        // ->where('seen', 'false')
                        // ->count();
                        
                        $messanger->other_user_id = $user_details->id;                        
                        // if(empty($user_details->name) || $user_details->name == "" || $user_details->name == null)
                        // {
                        //     $messanger->other_user_name = $user_details->username;
                        // }else{
                        //     $messanger->other_user_name = $user_details->name;    
                        // }
                        $messanger->time = json_decode($messanger->time);
                        $messanger->other_user_name = $user_details->username;
                        $messanger->other_user_avatar = IMAGE_URL.$user_details->profile_image;
                        $messanger->other_user_type = $user_details->type;
                        $messanger->unread_messages = 0;
                        array_push($inbox, $messanger);
                    }
                }
            }
            
            // return $inbox;
            
            return response()->json([
                'status' => sizeof($inbox) > 0 ? true : true,
                'message' => sizeof($inbox) > 0 ? 'Messanger Found' : 'No Messanger Found',
                'data' => sizeof($inbox) > 0 ? $inbox : [],
            ], 200);
            
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'message' => 'There is some trouble to proceed your action!',                
            ], 200);
        }
    }

    public function get_messages($self_id, $user_id)
    {
        try{
            $self = User::find($self_id);
            
            if(empty($self))
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Self User does not Exists!',                    
                ], 200);
            }

            $user = User::find($user_id);
            
            if(empty($user))
            {
                return response()->json([
                    'status' => false,
                    'message' => 'User does not Exists!',                   
                ], 200);
            }           
            
            $messages1 = Message::where('from_id', $user_id)
            ->where('to_id', $self_id)
            ->orderBy('time', 'desc')
            ->get(['id', 'from_id', 'to_id', 'text', 'message_type','media', 'media_type', 'time', 'seen']);
            
            if(!empty($messages1))
            {
                foreach($messages1 as $message1)
                {                     
                    $message1->time = json_decode($message1->time);
                    $message1->seen = json_decode($message1->seen);

                    if($message1->media != "")
                    {
                        $message1->media = IMAGE_URL.$message1->media;
                    }                    

                    if($message1->from_id != $self_id)
                    {
                        $message1->other_user = $this->user_model($message1->from_id);
                    }else{
                        $message1->other_user = $this->user_model($message1->to_id); 
                    }
                }
            }
            
            // return $messages1;
            
            $messages2 = Message::where('from_id', $self_id)
            ->where('to_id', $user_id)
            ->orderBy('time', 'desc')
            ->get(['id', 'from_id', 'to_id', 'text', 'message_type','media', 'media_type', 'time', 'seen']);
            
            if(!empty($messages2))
            {
                foreach($messages2 as $message2)
                {
                    $message2->time = json_decode($message2->time);
                    $message2->seen = json_decode($message2->seen);

                    if($message2->media != "")
                    {
                        $message2->media = IMAGE_URL.$message2->media;
                    }                    
                    
                    if($message2->from_id != $self_id)
                    {
                        $message2->other_user = $this->user_model($message2->from_id);
                    }else{
                        $message2->other_user = $this->user_model($message2->to_id); 
                    }                    
                }
            }
            
            $merged = $messages1->merge($messages2);
            
            // return $merged;
            
            $sorted = $merged->sortBy('id');
            
            $all_messages = $sorted->values()->all();
            // return $all_messages;
            return response()->json([
                'status' => sizeof($all_messages) > 0 ? true : true,
                'message' => sizeof($all_messages) > 0 ? 'Messages Found' : 'No Messages Found',
                'data' => sizeof($all_messages) > 0 ? $all_messages : [],
            ], 200);
            
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'message' => 'There is some trouble to proceed your action!',             
            ], 200);
        }
    }

    public function send_message(Request $request)
    {
        try{
            $from = User::find($request->from_id);
            
            if(empty($from))
            {
                return response()->json([
                    'status' => false,
                    'message' => 'From User does not Exists!',                  
                ], 200);
            }
            
            $to = User::find($request->to_id);
            
            if(empty($to))
            {
                return response()->json([
                    'status' => false,
                    'message' => 'To User does not Exists!',                   
                ], 200);
            }
            
            $message = new Message;
            $message->from_id = $request->from_id;
            $message->to_id = $request->to_id;
            $message->text = $request->text;

            if($request->has('message_type') && $request->message_type != "")
            {
                $message->message_type = $request->message_type;
            }

            if($request->has('media_type') && $request->media_type != "")
            {
                if($request->has('media') && $request->media != "")
                {
                    // if($request->media->getClientOriginalExtension() == 'PNG' ||$request->media->getClientOriginalExtension() == 'png' || $request->media->getClientOriginalExtension() == 'JPG' || $request->media->getClientOriginalExtension() == 'jpg' || $request->media->getClientOriginalExtension() == 'jpeg' || $request->media->getClientOriginalExtension() == 'JPEG' || $request->media->getClientOriginalExtension() == 'MP4' || $request->media->getClientOriginalExtension() == 'mp4' || $request->media->getClientOriginalExtension() == 'flv' || $request->media->getClientOriginalExtension() == 'FLV' || $request->media->getClientOriginalExtension() == 'wav' || $request->media->getClientOriginalExtension() == 'WAV' || $request->media->getClientOriginalExtension() == 'MP3' || $request->media->getClientOriginalExtension() == 'mp3' || $request->media->getClientOriginalExtension() == 'JPEG')
                    // {
                        $newfilename = md5(mt_rand()) .'.'. $request->media->getClientOriginalExtension();
                        $request->file('media')->move(public_path("/chat_media"), $newfilename);
                        $new_path1 = 'chat_media/'.$newfilename;
                        $message->media = $new_path1;
                        $message->media_type = $request->media_type;

                    // }else{
                    //     return response()->json([
                    //         'status' => false,
                    //         'message' => 'Choose a Valid Image!',                        
                    //     ], 200);
                    // }                       
                }
            }

            $message->time = time();
            
            if($message->save())
            {
                $messages1 = Message::where('from_id', $request->from_id)
                ->where('to_id', $request->to_id)
                ->orderBy('time', 'desc')
                ->get();
            
                if(!empty($messages1))
                {                  
                    $user_array = array();
            
                    $user_id = $to->id;    
                        
                    $latest_messages = Message::orWhere('from_id', $user_id)                    
                    ->orWhere('to_id', $user_id)
                    ->orderBy('time', 'desc')
                    ->get();

                    if(!empty($latest_messages))
                    {
                        foreach($latest_messages as $message)
                        {
                            if($message->from_id == $user_id)
                            {
                                array_push($user_array, $message->to_id);
                                
                            }else if($message->to_id == $user_id){
                                
                                array_push($user_array, $message->from_id);
                            }
                        }
                    }
                    
                    $arr = array_unique($user_array);
                    $arr1 = array();
                    
                    if(!empty($arr))
                    {
                        foreach($arr as $ar)
                        {
                            array_push($arr1, $ar);
                        }
                    }

                    $messanger_array = array();
                    
                    if(sizeof($arr1) > 0)
                    {
                        foreach($arr1 as $ar1)
                        {                           
                            $message1 = Message::where('from_id', $ar1)
                            ->where('to_id', $user_id)
                            ->orderBy('time', 'desc')
                            ->first();
                            
                            $message2 = Message::where('from_id', $user_id)
                            ->where('to_id', $ar1)
                            ->orderBy('time', 'desc')
                            ->first();
                            
                            if(!empty($message1) && !empty($message2))
                            {
                                if($message1->id > $message2->id)
                                {
                                    array_push($messanger_array, $message1);
                                }else{
                                    array_push($messanger_array, $message2);
                                }
                            }else if(empty($message1) && !empty($message2))
                            {
                                array_push($messanger_array, $message2);
                            }else if(empty($message2) && !empty($message1))
                            {
                                array_push($messanger_array, $message1);
                            }
                        }
                    }

                    $inbox = [];
                    
                    event(new GetMessanger(User::find($request->to_id), $inbox));                   
                }
               
                $messages2 = Message::where('from_id', $request->to_id)
                ->where('to_id', $request->from_id)
                ->orderBy('time', 'desc')
                ->get();
                
                $merged = $messages1->merge($messages2);
                
                $sorted = $merged->sortBy('id');
                
                $all_messages = $sorted->values()->all();             
                
                $arr12 = end($all_messages);
                
                $from_user = $arr12;
                $from_user->time = json_decode($from_user->time);
                $from_user->seen = json_decode($from_user->seen);

                if($from_user->media != "")
                {
                    $from_user->media = IMAGE_URL.$from_user->media;
                }                

                $from_user->other_user = $this->user_model($arr12->to_id);
                
                event(new GetMessages(User::find($request->from_id), json_decode(json_encode($from_user))));
                
                $to_user = $arr12;
                $to_user->time = json_decode($to_user->time);
                $to_user->seen = json_decode($to_user->seen);

                $to_user->other_user = $this->user_model($arr12->from_id);
                
                event(new GetMessages(User::find($request->to_id), json_decode(json_encode($to_user))));
                
                //Start FCM iOS Code   
                
                $sender_name = $to->name;               
                
                $token = User::where('id', $request->to_id)->first();        
                
                // // return $token;
                $json_data = array('to'=> $token->token, 'mutable_content' => true, 'content_available' => true, 'notification'=>array("title"=>$from->name, "body" => $request->text, "sound" => "default", "priority" => "high", "badge" => 1), 'data'=>array('from_id'=> $from->id, 'from_name' => $from->name, 'from_image' => IMAGE_URL.$from->profile_image, 'from_type' => $from->type, 'notification_type' => 'chat'));
                
                $data = json_encode($json_data);
                // return $data;                
                //FCM API end-point
                $url = 'https://fcm.googleapis.com/fcm/send';
                //api_key in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
                $server_key = 'AAAADQ3msYg:APA91bEf68CnRSmi8fRceaxbJJe2Bsycn46isuA-6v0GXHSDuC47xvbIdF0LBXx3kIejIFtue0snfsqy7OCLy28PkeOm6iPIx_rHk9Mcst9uzr66hA4pz3C8b3EMFKq3tQh765a3eu4a';
                //header with content_type api key
                $headers = array(
                    'Content-Type:application/json',
                    'Authorization:key='.$server_key
                );
                //CURL request to route notification to FCM connection server (provided by Google)
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                if ($result === FALSE) {
                    die('Oops! FCM Send Error: ' . curl_error($ch));
                }
                curl_close($ch);
                
                //End FCM iOS Code                  
                
                return response()->json([
                    'status' => true,
                    'message' => 'Message Sent',                    
                ], 200);   
            }
            
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => 'There is some trouble to proceed your action!',
                'data' => null,
            ], 200);
        }
    }

    public function delete_message($message_id)
    {
        try{    
            $message = Message::find($message_id);

            if(empty($message))
            {
                return response()->json([
                    'status' => true,
                    'message' => 'Message does not Exists',                    
                ], 200);
            }

            $message->delete();

            return response()->json([
                'status' => false,
                'message' => 'Message Deleted!',                
            ], 200);

        }catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'message' => 'There is some trouble to proceed your action!',               
            ], 200);
        }        
    }    

    public function edit_message(Request $request)
    {
        try{    
            $message = Message::find($request->message_id);

            if(empty($message))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'Message does not Exists',
                    'data' => null,
                ], 200);
            }

            if($request->has('text'))
            {
                $message->text = $request->text;
                $message->save();
            }

            return response()->json([
                'status' => 200,
                'message' => 'Message Updated!',
                'data' => null,
            ], 200);
            
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => 'There is some trouble to proceed your action!',
                'data' => null,
            ], 200);
        }        
    } 
    
    public function delete_messenger($self_id, $user_id)
    {
        try{
            $self = User::where('id', $self_id)->first('id');
            
            if(empty($self))
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Self does not Exists!',                   
                ], 200);
            }
            
            $user = User::where('id', $user_id)->first('id');
            
            if(empty($user))
            {
                return response()->json([
                    'status' => false,
                    'message' => 'User does not Exists!',                  
                ], 200);
            }
            
            $from = Message::where('from_id', $self_id)
            ->where('to_id', $user_id)
            ->delete();
            
            $to = Message::where('to_id', $self_id)
            ->where('from_id', $user_id)
            ->delete();
            
            return response()->json([
                'status' => true,
                'message' => 'Chat Deleted',                
            ], 200);
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'message' => 'There is some trouble to proceed your action!',              
            ], 200);
        }
    }
    
    public function read_messages($self_id, $user_id)
    {
        try{
            $self = User::where('id', $self_id)->first('id');
            
            if(empty($self))
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Self User does not Exists',                    
                ], 200);
            }
            
            $user = User::where('id', $user_id)->first('id');
            
            if(empty($user))
            {
                return response()->json([
                    'status' => false,
                    'message' => 'User does not Exists',                    
                ], 200);
            }
            
            $to = Message::where('to_id', $self_id)
            ->where('from_id', $user_id)
            ->where('seen', 'false')
            ->get();
            
            if(!empty($to))
            {
                foreach($to as $t)
                {
                    $t->seen = 1;
                    $t->save();
                }
            }
            
            $from = Message::where('from_id', $self_id)
            ->where('to_id', $user_id)
            ->where('seen', 'false')
            ->get();
            
            if(!empty($from))
            {
                foreach($from as $f)
                {
                    $f->seen = 1;
                    $f->save();
                }
            }
            
            return response()->json([
                'status' => true,
                'message' => 'Messages Seen',               
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
