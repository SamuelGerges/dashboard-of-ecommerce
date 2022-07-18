<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request\LoginRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function GetLogin()
    {
        return view('admin.auth.login');
    }

    public function Login(LoginRequest $request)
    {

        $remember_me = $request->has('remember_me') ? true : false;
//        $credentials = $request->only(['email','password']);
        $admin = auth()->guard('admin')
            ->attempt(['email' => $request->input('email'),'password' => $request->input('password')]);
        if($admin){
            // notify()->success('تم الدخول بنجاح  ');
            return redirect()->route('admin.dashboard');
        }
        // notify()->error('خطا في البيانات  برجاء المجاولة مجدا ');
        return redirect()->back()->with(['error' => 'هناك خطا بالبيانات']);


    }
}
