<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function index()
    {
        if (!empty(Auth::guard('web')->check())) {

            return redirect('tasks')->with('success','Admin Login Successfully');
        }
        return view('auth.login');
    }


    public function register_view()
    {

        return view('auth.register');
    }

    public function register_post(Request $request)
    {
        try{
            $validatedData = $request->validate([
                "name" => "required",
                "email" => "required|email|unique:users",
                "password" => "required|string|min:6|confirmed",
                "password_confirmation" => "required"
            ]);
    
            User::create($validatedData);
            return redirect()->route('login')->with("success", "Register Successfully");
        }
        catch(ValidationException $e){
            $errors = $e->validator->getMessageBag();
            return redirect()->back()->withErrors($errors)->withInput();
        }
    }

    public function login_post(Request $request)
    {
        if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect('tasks')->with('success', 'Login Successfully');
        } else {
            return back()->with('error', 'Invalid email or password');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
