<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function store(Request $request)
    {
        $otp = rand(100000,999999);
        try{
            $attribute = $request->validate([
                'firstName'=>['required','string','max:20'],
                'lastName'=>['required','string','max:20'],
                'email'=>['required','email','unique:users'],
                'password'=>['required','min:6','confirmed'],
            ]);
            $attribute['otp'] = $otp;
            $attribute['otp_expires_at']=Carbon::now()->addMinutes(10);
            // dd($attribute);
            $user = User::create($attribute);
            Mail::to($user->email)->queue(new OtpMail($otp));
            Auth::login($user);
            $token = $user->createToken('auth')->plainTextToken;
            return response()->json([
                'message'=>'User Registered Successfully',
                'token'=>$token,
                'email_verified_at'=>$user->hasVerifiedEmail(),
            ]);
        }
        catch(ValidationException $e)
        {
            return response()->json([
                'message'=>'Error occured',
                'errors'=>$e->errors(),
            ]);
        }
    }
    public function login(Request $request)
    {
        try {

            $attribute = $request->validate([
                'email'=>['required','email'],
                'password'=>['required']
            ]);
            if(!Auth::attempt($attribute))
            {
                return response()->json([
                    'message'=>'Invalid Credential',
                ]);
            }
            $user = $request->user();
            $token = $user->createToken('auth')->plainTextToken;
            return response()->json([
                'message'=>'User Registered Successfully',
                'token'=>$token,
                'email_verified_at'=>$user->hasVerifiedEmail(),
            ]);
           
        } catch (ValidationException $e) {
            return response()->json([
                'message'=>'Error occured',
                'errors'=>$e->errors(),
            ]);
        }
        
    }
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        return response()->json([
            'message' => 'Logged out successfully',
        ], 200);
        
    }
}
