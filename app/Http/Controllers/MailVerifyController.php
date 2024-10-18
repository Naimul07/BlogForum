<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class MailVerifyController extends Controller
{
    public function verifyOtp(Request $request)
    {
        try {
            $attribute = $request->validate([
                'otp' => ['required'],
            ]);
            $user = $request->user();
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
            if ($user->otp != $request->otp) {
                return response()->json(['message' => 'Invalid OTP'], 400);
            }
            if (Carbon::now()->isAfter($user->otp_expires_at)) {
                return response()->json(['message' => 'OTP expired'], 400);
            }
            $user->email_verified_at = Carbon::now();
            $user->otp = null;
            $user->otp_expires_at = null;
            $user->save();
            return response()->json([
                'message' => 'Email verified successfully',
                'user' => $user,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => $e->errors()
            ], 422);
        }
    }
    public function Resentemail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.'], 409);
        }
        $otp = rand(100000, 999999); // email verification otp random generator
        $user = $request->user();
        $otp_expires = Carbon::now()->addMinutes(10);
        $user->otp = $otp;
        $user->otp_expires_at = $otp_expires;
        $user->save();
        Mail::to($user->email)->queue(new OtpMail($otp));
        return response()->json([
            'message' => 'email resent',
        ]);
    }
}
