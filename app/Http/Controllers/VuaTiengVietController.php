<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\VuaTiengViet;
class VuaTiengVietController extends Controller
{
    //
    public function index(){
        return view('vuatiengviet.index');
    }

    public function getDanhSach(Request $request){
        $data = VuaTiengViet::all();
        if($data)
            return response()->json([
                'code' => 200,
                'type' => 'success',
                'message' => "Đã tải dữ liệu",
                'data' => $data
            ]);
        else
            return response()->json([
                'code' => 500,
                'type' => 'error',
                'message' => "Lỗi máy chủ"
            ]);
    }

}
