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

    public function getDoiMatKhau(){
        return view('admin.doimatkhau');
    }

    public function postDoiMatKhau(Request $request){
        $user = Auth::user();
        $currentPassword = $request->input('oldPass');
        $newPassword = $request->input('newPass');

        if (password_verify($currentPassword, $user->password)) {
            // Mật khẩu hiện tại đúng, tiến hành cập nhật mật khẩu mới
            $user->password = bcrypt($newPassword);
            $user->save();

            return response()->json(['code' => 200, 'type' => 'success', 'message' => 'Đổi mật khẩu thành công']);
        } else {
            // Mật khẩu hiện tại không đúng
            return response()->json(['code' => 400, 'type' => 'error', 'message' => 'Mật khẩu cũ không đúng'], 400);
        }
    }
}
