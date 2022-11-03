<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
class socialiteController extends Controller
{
    public function google_redirect(){
        try {
            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            return redirect('/signin');
        }
    }

    public function google_callback(){
        $socialite_user = Socialite::driver('google')->user();

        $check_user = User::where("email", $socialite_user->getEmail())
                            ->where("provider", null)
                            ->first();
        
        $user = User::where(['provider' => 'google', 'provider_id' => $socialite_user->getId()])->first();
        
        if(!$user){
    
           if($check_user){
                return response('Email already being used by another sigin method!', 422);
           }
           
            $user = new User;
            $user->f_name = $socialite_user->user["given_name"];
            $user->l_name = $socialite_user->user["family_name"];
            $user->email = $socialite_user->getEmail();
            $user->provider = 'google';
            $user->provider_id = $socialite_user->getId();
            $user->email_verified_at = now();
            $user->account_type = 'USER';
            $user->verification_status = 'VERIFIED';
            $user->save();
            Auth::login($user);
        }
        Auth::login($user);
        return redirect('/myAccount');
    }

    public function facebook_redirect(){
        try {
            return Socialite::driver('facebook')->redirect();
        } catch (\Exception $e) {
            return redirect('/signin');
        }
    }

    public function facebook_callback(){
        $socialite_user = Socialite::driver('facebook')->user();
        $names = explode(' ',$socialite_user->getName());
        $check_user = User::where("email", $socialite_user->getEmail())
                          ->where("provider", null)
                          ->first();
    
        $user = User::where(['provider' => 'facebook', 'provider_id' => $socialite_user->getId()])->first();
        if(!$user){
    
           if($check_user){
                return redirect('/signin')->withErrors(['msg' => "Email already being used by another sigin method!"]);
           }
            $user = new User;
            $user->f_name = $names[0];
            $user->l_name = (count($names) > 1)? $names[1]:"";
            $user->email = $socialite_user->getEmail();
            $user->provider = 'facebook';
            $user->provider_id = $socialite_user->getId();
            $user->email_verified_at = now();
            $user->account_type = 'USER';
            $user->verification_status = 'VERIFIED';
            $user->save();
            Auth::login($user);
        }
        Auth::login($user);
        return redirect('/myAccount');
    }

    public function mobGoogleLogin(Request $request){
        $this->validate($request, [
            "displayName" => "required",
            "email" => "required",
            "id" => "required",
        ]);
        $check_user = User::where("email", $request->email)
                          ->where("provider", null)
                          ->first();
        if($check_user){
            return response("Email already being used by another sigin method!", 422);
            
        }
        $user = User::where(['provider' => 'google', 'provider_id' => $request->id])->first();
        if($user){
            $user_token = $user->createToken($user->f_name);

            return ['token' => $user_token->plainTextToken];
        }
        else{
            $names = explode(' ',$request->displayName);
            $user = new User;
            $user->f_name = $names[0];
            $user->l_name = (count($names) > 1)? $names[1]: $names[0];
            $user->email = $request->email;
            $user->provider = 'google';
            $user->provider_id = $request->id;
            $user->email_verified_at = now();
            $user->account_type = 'USER';
            $user->verification_status = 'VERIFIED';
            $user->save();

            $user_token = $user->createToken($user->f_name);

            return ['token' => $user_token->plainTextToken];
        }

    }

    public function mobFacebookLogin(Request $request){
        $this->validate($request, [
            "displayName" => "required",
            "email" => "required",
            "id" => "required",
        ]);
        $check_user = User::where("email", $request->email)
                          ->where("provider", null)
                          ->first();
        if($check_user){
            return response("Email already being used by another sigin method!", 422);
        }
        $user = User::where(['provider' => 'facebook', 'provider_id' => $request->id])->first();
        if($user){
            $user_token = $user->createToken($user->f_name);

            return ['token' => $user_token->plainTextToken];
        }
        else{
            $names = explode(' ',$request->displayName);
            $user = new User;
            $user->f_name = $names[0];
            $user->l_name = (count($names) > 1)? $names[1]: $names[0];
            $user->email = $request->email;
            $user->provider = 'facebook';
            $user->provider_id = $request->id;
            $user->email_verified_at = now();
            $user->account_type = 'USER';
            $user->verification_status = 'VERIFIED';
            $user->save();
            $user_token = $user->createToken($user->f_name);

            return ['token' => $user_token->plainTextToken];
        }

    }
}
