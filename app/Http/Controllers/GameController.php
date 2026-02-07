<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\DuoiHinhBatChu;
use App\AmThanh;
use App\EventReal;
use Excel;
use Session;
use Symfony\Component\HttpFoundation\StreamedResponse;
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

    public function indexAmThanh(){
        return view('amthanh.index');
    }

    public function getDanhSachAmThanh(Request $request){
        //
        $data = AmThanh::all();
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

    public function postAmThanh(Request $request){
        $data = new AmThanh();
        $this->validate($request, [
            'importFile' => 'required|file|max:125120',
        ]);

        if ($files = $request->file('importFile')) {
            $extension = strtolower($files->getClientOriginalExtension());
            $clientMime = $files->getClientMimeType();
            $mime = $files->getMimeType();

            if (!in_array($extension, ['mp3', 'mpga']) && !in_array($clientMime, ['audio/mpeg', 'audio/mp3']) && !in_array($mime, ['audio/mpeg', 'audio/mp3'])) {
                return response()->json([
                    "type" => 'error',
                    "message" => 'The import file must be a file of type: mp3, MP3',
                    "code" => 422
                ], 422);
            }

            $etc = strtolower($files->getClientOriginalExtension());
            $name =  \HelpFunction::changeTitle($request->tenAmThanh) .".". $etc;
            while(file_exists("upload/amthanh/" . $name)) {
                $name = \HelpFunction::changeTitle($request->tenAmThanh) . "-" . rand() . ".". $etc;
            }
            $data->amthanh = $name;
            $data->noidung = $request->tenAmThanh;
            $data->ghiChu = "";
            $data->save();
            $files->move('upload/amthanh/', $name);
            
            if ($data) {
                return response()->json([
                    "type" => 'success',
                    "message" => 'File: Đã upload âm thanh và thông tin thành công',
                    "code" => 200,
                    "file" => $files
                ]);
            } else {
                return response()->json([
                    "type" => 'error',
                    "message" => 'File: lỗi upload âm thanh và thông tin',
                    "code" => 500
                ]);
            }           
        }

        return response()->json([
            "type" => 'error',
            "message" => 'Không thể cập nhật âm thanh và thông tin',
            "code" => 500
        ]);
    }

    public function deleteAmThanh(Request $request){
        $id = $request->id;
        $data = AmThanh::find($id);
        if($data){
            // xóa file âm thanh
            $filePath = "upload/amthanh/" . $data->amthanh;
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

    public function realTime() {
        $amthanh = "";
        $code = 0;
        $amluong = 0;
        $noidung = "";
        $stop = false;
        $id = 0;
        
        $getdata = EventReal::orderBy('id', 'desc')->first();

        $data = [
            'id' => $getdata ? $getdata->id : 0,
            'code' => $getdata ? $getdata->code : 0,
            'amluong' => $getdata ? $getdata->amluong : 0,
            'noidung' => $getdata ? $getdata->noidung : "",
            'stop' => $getdata ? $getdata->stop : false
        ];
        $response = new StreamedResponse();
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->setCallback(
            function() use ($data) {
                    echo "data: ".json_encode($data)."\n\n";
                    flush();
            });
        $response->send();
    }

    public function setHandled(Request $request) {
        $id = $request->id;
        $data = EventReal::find($id);
        if($data){
            $data->code = 0;
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

    public function setAmLuong(Request $request){
        $data = new EventReal();
        $data->code = 1; // Set âm lượng
        $data->amluong = $request->amluong;
        $data->noidung = "";
        $data->stop = false;
        $data->save();
        if ($data) {
            // Successfully saved
            return response()->json([
                'code' => 200,
                'type' => 'success',
                'message' => "Cập nhật âm lượng thành công"
            ]);
        } else {
            return response()->json([
                'code' => 500,
                'type' => 'error',
                'message' => "Lỗi khi cập nhật âm lượng"
            ]);
        }
    }

    public function setDungPhat(Request $request){
        $data = new EventReal();
        $data->code = 2; // Dừng phát
        $data->amluong = 0;
        $data->noidung = "";
        $data->stop = true;
        $data->save();
        if ($data) {
            // Successfully saved
            return response()->json([
                'code' => 200,
                'type' => 'success',
                'message' => "Đã gửi lệnh dừng phát"
            ]);
        } else {
            return response()->json([
                'code' => 500,
                'type' => 'error',
                'message' => "Lỗi khi gửi lệnh dừng phát"
            ]);
        }
    }

    public function setPhatNhac(Request $request){
        $getdata = AmThanh::find($request->id);
        $data = new EventReal();
        $data->code = 3; // Phát nhạc
        $data->amluong = 0;
        $data->noidung = $getdata ? $getdata->amthanh : "";
        $data->stop = false;
        $data->save();
        if ($data) {
            // Successfully saved
            return response()->json([
                'code' => 200,
                'type' => 'success',
                'message' => "Đã gửi lệnh phát nhạc"
            ]);
        } else {
            return response()->json([
                'code' => 500,
                'type' => 'error',
                'message' => "Lỗi khi gửi lệnh phát nhạc"
            ]);
        }
    }
}
