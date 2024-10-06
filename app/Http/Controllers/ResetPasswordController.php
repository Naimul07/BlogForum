<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
    //
    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'token' => ['required'],
                'email' => ['required', 'email'],
                'password' => ['required', 'min:8', 'confirmed']
            ]);

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user, string $password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->setRememberToken(Str::random(60));
                    $user->save();
                    event(new PasswordReset($user));
                }
            );
            if ($status === Password::PASSWORD_RESET) {
                return response()->json(['message' => 'Password has been reset.'], 200);
            }
            return response()->json(['message' => 'Unable to reset password.'], 400);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => $e->errors(),
            ]);
        }

    }
    public function sendResetLinkEmail(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'email']
            ]);
            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status == Password::RESET_LINK_SENT) {
                return response()->json([
                    'message' => 'reset link sent to your email'
                ]);
            }
            return response()->json(['message' => 'Unable to send reset link']);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => $e->errors(),
            ]);
        }
    }
}
