<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\QuaySo;

class QuaySoController extends Controller
{
    //
    public function index() {
        return view('quayso.quayso');
    }

    public function getDanhSach() {
        $data = QuaySo::all();
        $arr = [];
        foreach($data as $row) {
            $temp = $row;
            $temp->ngayTao = \HelpFunction::revertCreatedAt($row->updated_at);
            array_push($arr, $temp);
        }
        if($data)
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
        $start = $request->soBatDau;
        $end = $request->soKetThuc;
        if ($start && $end) {
            QuaySo::truncate();
            $flag = false;
            for($i = $start; $i <= $end; $i++) { 
                $data = new QuaySo();           
                $data->so = $i;
                $data->save();
                $flag = true;
            }
            if ($flag) {
                return response()->json([
                    "type" => 'success',
                    "message" => 'Đã thêm dãy số',
                    "code" => 200
                ]);
            } else {
                return response()->json([
                    "type" => 'error',
                    "message" => 'Lỗi',
                    "code" => 500
                ]);
            }
        } else {
            return response()->json([
                "type" => 'error',
                "message" => ' Lỗi nhập liệu',
                "code" => 500
            ]);
        }        
    }

    public function batDauQuaySo() {
        $jsonString = file_get_contents('upload/cauhinh/config.json');
        $conf = json_decode($jsonString, true);   
        $data = QuaySo::select("*")->where('daChon', false)->orderBy('so')->get();
        $arr = [];
        foreach($data as $row) {
            array_push($arr, $row->so);
        }
        return view('quayso.batdauquayso', ['data' => $arr, 'conf' => $conf]);
    }

    public function setSo(Request $request) {
        $so = $request->so;
        $data = QuaySo::where('so',$so)->first();
        if ($data) {
            $setSo = QuaySo::find($data->id);
            $setSo->daChon = true;
            $setSo->save();
            return response()->json([
                "type" => 'success',
                "message" => 'Set số thành công',
                "code" => 200
            ]);
        } else {
            return response()->json([
                "type" => 'error',
                "message" => 'Lỗi',
                "code" => 500
            ]);
        }
    }
}
