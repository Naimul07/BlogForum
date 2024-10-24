<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MailVerifyController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ResetPasswordController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Auth 
Route::post('/login',[AuthController::class,'login']);
Route::post('/register',[AuthController::class,'store']);
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum');

//password_reset
Route::post('/password/email',[ResetPasswordController::class,'sendResetLinkEmail'])->name('password.email');
Route::post('/password/reset',[ResetPasswordController::class,'resetPassword'])->name('password.reset'); //naming is must for password resetting

//email verification using otp
Route::post('/verify/otp',[MailVerifyController::class,'verifyOtp'])->middleware('auth:sanctum');
Route::get('/resent/email',[MailVerifyController::class,'Resentemail'])->middleware('auth:sanctum');


 Route::middleware(['auth:sanctum','verified'])->group(function(){
    Route::apiResource('post',PostController::class);
    // Route::put('/post/{post}',[PostController::class,'update']);

    //comments route
    Route::put('/post/comments/{id}',[CommentController::class,'update']);
    Route::post('/post/comments',[CommentController::class,'store']);
    Route::delete('/post/comments/{id}',[CommentController::class,'destroy']);

}); 