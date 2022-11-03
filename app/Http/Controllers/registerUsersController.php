<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTP;
use App\Mail\PasswordReset;
class registerUsersController extends Controller
{
    public function registerUser(Request $request){
        $this->validate($request, [
            'f_name' => ['required', 'string', 'max:255'],
            'l_name' => ['required', 'string', 'max:255'],
            'preferences' => ['required', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = new User;
        $user->f_name = $request->f_name;
        $user->l_name = $request->l_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->account_type = "USER";
        $user->user_role = "USER";
        $user->style_preference = json_encode($request->preferences);
        $user->otp = rand(1000 , 9999);
        $user->save();

        Mail::to($user)->send(new OTP($user));
        return $user;

    }

    public function verifyOTP(Request $request){
        $this->validate($request, [
            "otp" => "required | max:4",
            "user_id" => "required | integer"
        ]);

        $user = User::find($request->user_id);
        
        if($user->otp != $request->otp){
            return response("OTP mismatch", 422);
        }
        $user->verification_status = "VERIFIED";
        $user->save();
        auth()->login($user);

        return $user;
    }
    public function verifyMobileOTP(Request $request){
        $this->validate($request, [
            "otp" => "required | max:4",
            "user_id" => "required | integer"
        ]);

        $user = User::find($request->user_id);
        
        if($user->otp != $request->otp){
            return response("OTP mismatch", 422);
        }
        $user->verification_status = "VERIFIED";
        $user->save();
        /////////////////////////////////
        $user_token = $user->createToken($user->f_name);

        return ['token' => $user_token->plainTextToken]; 
        
    }

    public function foregetPasswordMailer(Request $request){
        $this->validate($request, [
            "email" => "required | email"
        ]);

        $user = User::where('email', $request->email)->first();
        
        if(!$user){
            return response('Email doesnt exist', 422);
        }
        
        $user->otp = rand(1000,9999);
        $user->reset_status = "REQUESTED";
        $user->save();

        Mail::to($user)->send(new PasswordReset($user));
        return $user;
    }

    public function resetVerify(Request $request){
        $this->validate($request, [
            "otp" => "required | max:4",
            "user_id" => "required | integer"
        ]);

        $user = User::find($request->user_id);
        if($user->otp != $request->otp){
            return response("Wrong OTP", 422);
        }
        $user->reset_status = "ALLOWED";
        $user->save();
        return $user;
    }

    public function reset_password(Request $request){
        $this->validate($request, [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'user_id' => "required | integer"
        ]);

        $user = User::find($request->user_id);
        $user->password = Hash::make($request->password);
        $user->save();

        auth()->login($user);

        $user_token = $user->createToken($user->f_name);

        return ['token' => $user_token->plainTextToken];
    }

    public function resendOTP(Request $request){
        $this->validate($request, [
            'user_id' => "required"
        ]);

        $user = User::find($request->user_id);
        $user->otp = rand(1000,9999);
        $user->save();
        Mail::to($user)->send(new PasswordReset($user));
        return $user;
    }

    public function updateInfo(Request $request){
        $this->validate($request, [
            "detailsForm" => "required",
            "style_preference" => "required"
        ]);

        $user = User::find($request->detailsForm['id']);
        $user->f_name = $request->detailsForm['f_name'];
        $user->l_name = $request->detailsForm['l_name'];
        $user->style_preference = json_encode($request->style_preference);

        $user->save();

        return $user;
    }

    public function updateMobInfo(Request $request){
        $this->validate($request, [
            "detailsForm" => "required",
            "style_preference" => "required"
        ]);
        $request->detailsForm = json_decode($request->detailsForm);
        $user = User::find($request->detailsForm->id);
        $user->f_name = $request->detailsForm->f_name;
        $user->l_name = $request->detailsForm->l_name;
        $user->style_preference = json_encode(json_decode($request->style_preference));

        $user->save();

        return $user;
    }
}
