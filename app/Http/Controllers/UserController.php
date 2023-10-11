<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function login(){
        if (Auth::check()) {
            return view('admin.home');
        }
        return view('admin.login');
    }

    public function postLogin(Request $request) {
        $data = ['name' => $request->account, 'password' => $request->password];
        if (Auth::attempt($data)) {   
            session([
                'admin' => 1
            ]);       
           return view('admin.home');
        } else {
            return view('admin.login', ['error' => 'Sai tài khoản hoặc mật khẩu']);
        }
        return view('login', ['error' => 'Sai tài khoản hoặc mật khẩu']);
    }
}
