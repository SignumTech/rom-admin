<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
class pagePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $uri = $request->path();
        $uri = substr($uri, 6);
        //dd(substr($uri, 6));
        $permissions = User::join('role_permissions', 'users.user_role', '=', 'role_permissions.role')
                           ->where('users.id', auth()->user()->id)->select('permissions')->first();
        
        if($permissions){
            $permissions = collect(json_decode($permissions->permissions))->toArray();
            if(array_key_exists($uri, $permissions)){
                if($permissions[$uri] == false){
                    return redirect('/admin');
                }            
            }
        }
        else{
            return redirect('/');
        }

        return $next($request);
    }
}
