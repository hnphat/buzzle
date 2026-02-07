<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\DuoiHinhBatChu;
use Excel;

class GameController extends Controller
{
    //
    public function index(){
        return view('duoihinhbatchu.index');
    }

    public function getDanhSach(Request $request){
        //
        $data = DuoiHinhBatChu::all();
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

    public function postHinh(Request $request){
        $data = new DuoiHinhBatChu();
        $this->validate($request,[
            'importFile'  => 'required|mimes:jpg,png,JPG,PNG|max:2048',
        ]);
    
        if ($files = $request->file('importFile')) {
            $etc = strtolower($files->getClientOriginalExtension());
            $name =  \HelpFunction::changeTitle($request->cauTraLoi) .".". $etc;
            while(file_exists("upload/duoihinhbatchu/" . $name)) {
                $name = \HelpFunction::changeTitle($request->cauTraLoi) . "-" . rand() . ".". $etc;
            }
            $data->cauhoi = $name;
            $data->cautraloi = $request->cauTraLoi;
            $data->isActive = true;
            $data->ghiChu = "";
            $data->save();
            $files->move('upload/duoihinhbatchu/', $name);
            
            if ($data) {
                return response()->json([
                    "type" => 'success',
                    "message" => 'File: Đã upload hình và thông tin thành công',
                    "code" => 200,
                    "file" => $files
                ]);
            } else {
                return response()->json([
                    "type" => 'error',
                    "message" => 'File: lỗi upload hình và thông tin',
                    "code" => 500
                ]);
            }           
        }

        return response()->json([
            "type" => 'error',
            "message" => 'Không thể cập nhật hình và thông tin',
            "code" => 500
        ]);
    }

    public function deleteAjax(Request $request){
        $id = $request->id;
        $data = DuoiHinhBatChu::find($id);
        if($data){
            // xóa file hình
            $filePath = "upload/duoihinhbatchu/" . $data->cauhoi;
            if(file_exists($filePath)){
                unlink($filePath);
            }
            // xóa bản ghi
            $data->delete();
            return response()->json([
                'code' => 200,
                'type' => 'success',
                'message' => "Xóa dữ liệu thành công"
            ]);
        }else{
            return response()->json([
                'code' => 500,
                'type' => 'error',
                'message' => "Dữ liệu không tồn tại"
            ]);
        }
    }

    public function layCauHoi(Request $request){
        $entry = DuoiHinhBatChu::where('isActive',true)->inRandomOrder()->first();
        if($entry){
            return response()->json([
                'code' => 200,
                'type' => 'success',
                'message' => "Lấy câu hỏi thành công!",
                'data' => $entry
            ]);
        } else {
            return response()->json([
                'code' => 404,
                'type' => 'error',
                'message' => "Không tìm thấy câu hỏi nào!"
            ]);
        }
    }

    public function setNotActive(Request $request){
        $id = $request->id;
        $data = DuoiHinhBatChu::find($id);
        if($data){
            $data->isActive = false;
            $data->save();
            return response()->json([
                'code' => 200,
                'type' => 'success',
                'message' => "Cập nhật trạng thái thành công"
            ]);
        }else{
            return response()->json([
                'code' => 500,
                'type' => 'error',
                'message' => "Dữ liệu không tồn tại"
            ]);
        }
    }
}
