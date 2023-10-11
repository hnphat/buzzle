<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NhomAnh;

class HinhAnhController extends Controller
{
    // Nhóm ảnh
    public function getNhomList() {
        return view('nhomhinh.nhom');
    }

    public function getNhomAjaxList() {
        $hinh = NhomAnh::all();
        if($hinh)
            return response()->json([
                'code' => 200,
                'type' => 'success',
                'message' => "Đã tải dữ liệu",
                'data' => $hinh
            ]);
        else
            return response()->json([
                'code' => 500,
                'type' => 'error',
                'message' => "Lỗi máy chủ"
            ]);
    }

    public function postNhomAjaxHinh(Request $request) {
        $hinh = new NhomAnh();
        $this->validate($request,[
            'file'  => 'required|mimes:jpg,png,JPG,PNG|max:2048',
        ]);
    
        if ($files = $request->file('file')) {
            $etc = strtolower($files->getClientOriginalExtension());
            // $name = \HelpFunction::changeTitle($files->getClientOriginalName()) . "." . $etc;
            // while(file_exists("upload/image/" . $name)) {
            //     $name = rand() . "-" . $name . "." . $etc;
            // }
            $name =  \HelpFunction::changeTitle($request->noiDung) .".". $etc;
            while(file_exists("upload/image/" . $name)) {
                $name = \HelpFunction::changeTitle($request->noiDung) . "-" . rand() . ".". $etc;
            }
            $hinh->noiDung = $request->noiDung;
            $hinh->url = $name;
            $hinh->isPic = $request->loai;
            $hinh->counter = $request->soLuong ? $request->soLuong : 0;   
            $hinh->save();
            $files->move('upload/image/', $name);
            
            if ($hinh) {
                return response()->json([
                    "type" => 'success',
                    "message" => 'File: Đã upload file',
                    "code" => 200,
                    "file" => $files
                ]);
            } else {
                return response()->json([
                    "type" => 'error',
                    "message" => 'File: lỗi upload',
                    "code" => 500
                ]);
            }
           
        }
        return response()->json([
            "type" => 'danger',
            "message" => 'File: Không thể upload file và nội dung',
            "code" => 500
        ]);
    }

    public function postNhomAjaxHinhEdit(Request $request) {
        $hinh = NhomAnh::find($request->idToEdit);    
        $oldurl = $hinh->url;
        $this->validate($request,[
            'efile'  => 'mimes:jpg,png,JPG,PNG|max:2048',
        ]);
        if ($files = $request->file('efile')) {
            if (file_exists('upload/image/' . $oldurl)) {
                unlink('upload/image/'.$oldurl);
            }
            $etc = strtolower($files->getClientOriginalExtension());
            $name = \HelpFunction::changeTitle($request->enoiDung) .".". $etc;
            while(file_exists("upload/image/" . $name)) {
                $name = \HelpFunction::changeTitle($request->enoiDung) . "-" . rand() . ".". $etc;
            }
            $hinh->noiDung = $request->enoiDung;
            $hinh->isPic = $request->eloai;
            $hinh->counter = $request->esoLuong ? $request->esoLuong : 0;   
            $hinh->url = $name;
            $hinh->save();
            $files->move('upload/image/', $name);
            
            if ($hinh) {
                return response()->json([
                    "type" => 'success',
                    "message" => 'File: Đã upload file',
                    "code" => 200,
                    "file" => $files
                ]);
            } else {
                return response()->json([
                    "type" => 'error',
                    "message" => 'File: lỗi upload',
                    "code" => 500
                ]);
            }
        } else {
            $hinh->noiDung = $request->enoiDung;
            $hinh->isPic = $request->eloai;
            $hinh->counter = $request->esoLuong ? $request->esoLuong : 0;   
            $hinh->save();
            if ($hinh) {
                return response()->json([
                    "type" => 'success',
                    "message" => 'Đã cập nhật thông tin',
                    "code" => 200
                ]);
            } else {
                return response()->json([
                    "type" => 'error',
                    "message" => 'Lỗi cập nhật',
                    "code" => 500
                ]);
            }
        }
        return response()->json([
            "type" => 'danger',
            "message" => 'File: Không thể upload file và nội dung',
            "code" => 500
        ]);
    }

    public function deleteNhomAjaxHinh(Request $request) {
        $hinh = NhomAnh::find($request->id);
        $name = $hinh->url;
        if (file_exists('upload/image/' . $name)) {
            unlink('upload/image/'.$name);
        }
        $hinh->delete();
        if ($hinh) {
            return response()->json([
                'type' => 'success',
                'message' => 'Đã xóa file!',
                'code' => 200,
                'data' => $hinh
            ]);
        }
        else
            return response()->json([
                'type' => 'error',
                'message' => 'Lỗi xóa file từ máy chủ!',
                'code' => 500
            ]);
    }

    public function getNhomEdit(Request $request) {
        $hinh = NhomAnh::find($request->id);       
        if ($hinh) {
            return response()->json([
                'type' => 'success',
                'message' => 'Đã tải thông tin!',
                'code' => '200',
                'data' => $hinh
            ]);
        }
        else
            return response()->json([
                'type' => 'error',
                'message' => 'Lỗi tải thông tin từ máy chủ!',
                'code' => 500
            ]);
    }

    // Hinh anh
    public function getList() {
        $hinh = NhomAnh::all();
        return view('hinh.danhsach', ['nhom' => $hinh]);
    }
}
