<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Guest;
use Excel;

class GuestController extends Controller
{
    public function getList() {
        $guest = Guest::all();
        return view('khachhang.danhsach', ['guest' => $guest]);
    }

    public function getAjaxList() {
        $guest = Guest::all();
        $arr = [];
        foreach($guest as $row) {
            $temp = $row;
            $temp->real_time = \HelpFunction::revertCreatedAt($row->updated_at);
            array_push($arr, $temp);
        }
        if($guest)
            return response()->json([
                'code' => 200,
                'type' => 'success',
                'message' => "Đã tải dữ liệu",
                'data' => $arr
            ]);
        else
            return response()->json([
                'code' => 500,
                'type' => 'error',
                'message' => "Lỗi máy chủ"
            ]);
    }

    public function postAjax(Request $request) {
        $guest = new Guest();    
        $guest->hoTen = $request->hoTen;
        $guest->dienThoai = $request->dienThoai;
        $guest->bienSoXe = strtoupper($request->bienSoXe);
        $guest->diaChi = $request->diaChi;
        $guest->save();
        if ($guest) {
            return response()->json([
                "type" => 'success',
                "message" => 'Đã thêm thông tin khách hàng',
                "code" => 200
            ]);
        } else {
            return response()->json([
                "type" => 'error',
                "message" => 'File: lỗi upload',
                "code" => 500
            ]);
        }
    }

    public function deleteAjax(Request $request) {
        $guest = Guest::find($request->id);
        $guest->delete();
        if ($guest) {
            return response()->json([
                'type' => 'success',
                'message' => 'Đã xóa',
                'code' => 200
            ]);
        }
        else
            return response()->json([
                'type' => 'error',
                'message' => 'Lỗi xóa từ máy chủ!',
                'code' => 500
            ]);
    }

    public function importGuest(Request $request) {
        $this->validate($request,[
            'importFile'  => 'required|mimes:xls,xlsx|max:2048',
        ]);
        $counter = 1;
        $numlen = 0;
        if ($files = $request->file('importFile')) {
            $theArray = Excel::toArray([], request()->file('importFile'));  
            if (strval($theArray[0][0][0]) == "BIENSO") {
                $numlen = count($theArray[0]);
                for($i = 1; $i < $numlen; $i++) {
                    $counter++;
                    if ($theArray[0][$i][0]) {
                        $guest = new Guest();    
                        $guest->bienSoXe = strtoupper($theArray[0][$i][0]);
                        $guest->save();
                    }                   
                }
            }
            if ($counter >= $numlen) {
                return response()->json([
                    "type" => 'info',
                    "message" => 'Đã upload danh sách từ file excel',
                    "code" => 200
                ]);
            }
        }
        return response()->json([
            "type" => 'error',
            "message" => 'File: Không thể upload file và nội dung',
            "code" => 500
        ]);
    }

    public function postSoMayMan(Request $request) {
        $idguest = session('guest') ? session('guest') : null;
        if ($idguest != null) {
            $guest = Guest::find($idguest);
            $guest->ghiChu = $request->soMayMan;
            $guest->save();
            if ($guest){                
                session([
                    'active' => 0,
                    'guest' => 0
                ]);
                return response()->json([
                    'code' => 200,
                    'message' => 'Success'
                ]);
            }
            else 
                return response()->json([
                    'code' => 500,
                    'message' => 'Fail'
                ]);
        }            
        else
            return response()->json([
                'code' => 500,
                'type' => 'error',
                'message' => 'Không hợp lệ'
            ]);
    }

    public function tachSo() {
        $arr = [];
        for ($i = 1; $i < 100; $i++) { 
            array_push($i);
        }
        
    }
}
