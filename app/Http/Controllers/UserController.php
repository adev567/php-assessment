<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Auth;
use Validator;


class UserController extends Controller
{
    public function register(Request $request){
        $findUser = User::where('email',$request->email)->first();
        if($findUser){         // check email exist with other user
            return response()->json(['error'=>'Email already exist with other User'], 500);            
        }
        else{
            // Register new user
            $validator = Validator::make($request->all(), [ 
                'email' => 'required|email', 
                'password' => 'required', 
                'confirm_password' => 'required|same:password', 
            ]);
            if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], 500);            
            }
            $code = rand (10000, 99999);
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'verification_code' => $code,
            ]);
            if($user){
                $success['token'] =  $user->createToken($user)->accessToken; 
                $success['id'] =  $user->id; 
                // Sending verification code email
                if($this->sendVerificationEmail($user->id)){
                    return response()->json(['error'=>'Failed to send verification email, please try again.'], 500);            
                }
                else{
                    return response()->json(['success'=>$success], 200);            
                }
            }
            else{
                return response()->json(['error'=>'Failed to create user, please try again.'], 500);            
            }
        }
    }
    private function sendVerificationEmail($id){

        $user = User::find($id);
        $userEmail = $user->email;
        // Triggering Email for verification code
        Mail::send(['html'=>'sendVerificationEmail'],['verification_code'=> $user->verification_code],function($message) use ($userEmail){
            $message->to($userEmail)->subject("Verify Your Account");
        });
    }

    public function verifyCode($id, $getUserCode){
        $user = User::find($id);
        if($user){
            if($user->verification_code == $getUserCode){
                $user->update(['verification_status'=> true]); // update verification status
                return response()->json(['success'=>'Verification Code verified successfully'], 200);            
            }
            else{
                return response()->json(['error'=>'Verification Code missmatched'], 500);            
            }
        }
        else{
            return response()->json(['error'=>'User not found'], 500);            
        }
    }

    public function login(Request $request){ 
        // verifying email and password
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken($user)->accessToken; 
            return response()->json(['success' => $success], 200); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised User'], 401); 
        } 
    }

    public function logout(){
        if(Auth::check()){
            $token = Auth::user()->token();
            $token->revoke();
            return response()->json(['success'=>'Logged out successfully'], 200); 
        }
        else{
            return response()->json(['error'=>'Unauthorised User'], 401); 
        }
    }
}
