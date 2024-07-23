<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;
    public function showResetPasswordForm($token)
    {
       return view('auth.forgetPassword',['token' => $token]);
    }
    
    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = User::where('remember_token',$request->token)->first();
        if(!$updatePassword){
            return back()->withInput()->with('error', 'Invalid token!');
        }
        $user = User::where('remember_token', $request->token)->update([
            'password' => bcrypt($request->password),
            'actual_password'=> $request->password,
            'remember_token' => null, 
        ]);
        return redirect()->route('login')->with('message', 'Your password has been changed!');

    }
}
