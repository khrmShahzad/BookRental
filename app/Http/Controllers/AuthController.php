<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login(Request $request)
    {
//        return view('login');
        return view('login-new');
    }

    public function register()
    {
//        return view('register');
        return view('register-new');
    }

    public function authenticating(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        // cek apakah login valid

        if (Auth::attempt($credentials)) {
            // cek apakah status  = active
            if (Auth::user()->status != 'active') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                Session::flash('status', 'fail');
                Session::flash('message', 'Your account is not active yet. Please contact admin!');
                return redirect('/login');
            }

            // kasi session
            $request->session()->regenerate();

            if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2) {
                return redirect('dashboard');
            }
            if (Auth::user()->role_id == 3) {
                return redirect('profile');
            }

            // return redirect()->intended('dashboard');
        } else {
            Session::flash('status', 'fail');
            Session::flash('message', 'Login Invalid!');
            return redirect('/login');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function registerProcess(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:users|max:255',
            'password' => 'required|max:255',
            'phone' => 'max:255',
            'address' => 'required',
            'role_id' => 'required'
        ]);

        if ($request->role_id == 3 || $request->role_id == '3'){
            $request->merge(['status' => 'active']);
        }
        // $request->password = Hash::make($request->password);
        $request->merge(['password' => Hash::make($request->password)]);


        $newName = '';
        if ($request->file('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $newName = 'user-' . now()->timestamp . '.' . $extension;
            $request->file('image')->move(public_path('users'), $newName);
        }
        $request['avatar'] = $newName;

        $user = User::create($request->all());

        Session::flash('status', 'success');
        if ($request->role_id == 3 || $request->role_id == '3'){
            Session::flash('message', 'Register success!');
        }else{
            Session::flash('message', 'Register success, wait admin approval!');
        }

        return redirect('/register');
    }
}
