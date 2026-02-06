<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\VuaTiengViet;
use Excel;
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

    public function postAjax(Request $request){
        $cauhoi = $request->cauHoi;
        $cautraloi = $request->cauTraLoi;
        $newEntry = new VuaTiengViet();
        $newEntry->cauhoi = $cauhoi;
        $newEntry->cautraloi = $cautraloi;
        $newEntry->isActive = true;
        $newEntry->ghiChu = "";
        if($newEntry->save()){
            return response()->json([
                'code' => 200,
                'type' => 'success',
                'message' => "Đã thêm câu hỏi mới"
            ]);
        }else{
            return response()->json([
                'code' => 500,
                'type' => 'error',
                'message' => "Lỗi máy chủ"
            ]);
        }
    }

    public function deleteAjax(Request $request){
        $id = $request->id;
        $entry = VuaTiengViet::find($id);
        if($entry){
            if($entry->delete()){
                return response()->json([
                    'code' => 200,
                    'type' => 'success',
                    'message' => "Đã xóa câu hỏi"
                ]);
            }else{
                return response()->json([
                    'code' => 500,
                    'type' => 'error',
                    'message' => "Lỗi máy chủ"
                ]);
            }
        }else{
            return response()->json([
                'code' => 404,
                'type' => 'error',
                'message' => "Câu hỏi không tồn tại"
            ]);
        }
    }

    public function editAjax(Request $request){
        $entry = VuaTiengViet::find($request->id);
        if($entry){
            return response()->json([
                'code' => 200,
                'type' => 'success',
                'message' => "Lấy thông tin câu hỏi thành công!",
                'data' => $entry
            ]);
        }else{
            return response()->json([
                'code' => 404,
                'type' => 'error',
                'message' => "Câu hỏi không tồn tại"
            ]);
        }
    }

    public function importVuaTiengViet(Request $request){
        $this->validate($request,[
            'importFile'  => 'required|mimes:xls,xlsx|max:2048',
        ]);
        $counter = 1;
        $numlen = 0;
        if ($files = $request->file('importFile')) {
            $theArray = Excel::toArray([], request()->file('importFile'));  
            if (strval($theArray[0][0][0]) == "cauhoi") {
                // Xóa tất cả câu hỏi để tạo mới
                VuaTiengViet::truncate();
                // ------------------------------
                $numlen = count($theArray[0]);
                for($i = 1; $i < $numlen; $i++) {
                    $counter++;
                    if ($theArray[0][$i][0]) {
                        $newEntry = new VuaTiengViet();
                        $newEntry->cauhoi = $theArray[0][$i][0];
                        $newEntry->cautraloi = $theArray[0][$i][1];
                        $newEntry->isActive = true;
                        $newEntry->ghiChu = "";
                        $newEntry->save();
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

    public function getFirstQuestion(Request $request){
        $entry = VuaTiengViet::where('isActive',true)->inRandomOrder()->first();
        if($entry){
            return response()->json([
                'code' => 200,
                'type' => 'success',
                'message' => "Lấy câu hỏi thành công!",
                'data' => $entry
            ]);
        }else{
            return response()->json([
                'code' => 404,
                'type' => 'error',
                'message' => "Không tìm thấy câu hỏi nào!"
            ]);
        }
    }

    public function setNotActive(Request $request){
        $id = $request->id;
        $entry = VuaTiengViet::find($id);
        if($entry){
            $entry->isActive = false;
            if($entry->save()){
                return response()->json([
                    'code' => 200,
                    'type' => 'success',
                    'message' => "Cập nhật trạng thái câu hỏi thành công!"
                ]);
            }else{
                return response()->json([
                    'code' => 500,
                    'type' => 'error',
                    'message' => "Lỗi máy chủ"
                ]);
            }
        }else{
            return response()->json([
                'code' => 404,
                'type' => 'error',
                'message' => "Câu hỏi không tồn tại"
            ]);
        }
    }
}
