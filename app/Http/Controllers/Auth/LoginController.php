<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Session;
use Carbon\Carbon;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function getLogin()
    {   
        if (Auth::check()) {
            
            $user = Auth::user();
            
            if($user->role == 0){
                return redirect('/admin/dashboard');
            }elseif($user->role == 1){
                return redirect('/employee/dashboard');
            }elseif($user->role == 2){
                return redirect('/seller/dashboard');
            }elseif($user->role == 3){
                return redirect('/user/dashboard');
            }

        } else {
            return view('auth.login');
        }
    }
    public function postLogin(LoginRequest $request)
    {
        
        $userdata = array(
            'username'  => $request->username,
            'password'  => $request->password
        );
    
        if (Auth::attempt($userdata)) {

            $user = Auth::user();
            
            if($user->role == 0){
                return redirect('/admin/dashboard');
            }elseif($user->role == 1){
                return redirect('/employee/dashboard');
            }elseif($user->role == 2){
                return redirect('/seller/dashboard');
            }elseif($user->role == 3){
                return redirect('/user/dashboard');
            }
                
        } else {      

            Session::flash('danger', "The credentials you entered did not match our records.");
            return back();
            
        }
        
    }
    public function logout() {
        if (Auth::user()){
            Auth::logout();
            Session::flush();
            return redirect('/login');
        } else {
            return redirect('/login');  
        }
    }

}
