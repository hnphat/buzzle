<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Guest;
use App\NhomAnh;
use App\KhaoSat;
use Excel;
class CauHinhController extends Controller
{
    //
    public function getList() {
        return view('cauhinh.cauhinh');
    }

    public function getAjax() {
        $jsonString = file_get_contents('upload/cauhinh/config.json');
        $data = json_decode($jsonString, true);   
        return response()->json([
            'type' => 'success',
            'message' => 'Đã tải',
            'code' => 200,
            'data' => $data
        ]);
    }

    public function saveConfig(Request $request) {
        $data["hinhNen"] = $request->hinhNen;      
        $data["hinhNenGhepHinh"] = $request->hinhNenGhepHinh;        
        $data["cheDoQuay"] = $request->cheDoQuay;   
        $data["linkGoogleForm"] = $request->linkGoogleForm;        
        $newJsonString = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents('upload/cauhinh/config.json', $newJsonString);
        return response()->json([
            'type' => 'success',
            'message' => 'Đã lưu cấu hình',
            'code' => 200,
            'data' => $data
        ]);
    }

    public function postSubmit(Request $request) {
        $check = Guest::where('bienSoXe',strtoupper($request->bienSoXe))->exists();
        if($check) {
            $checkPoll = Guest::where([
                ['bienSoXe','=',strtoupper($request->bienSoXe)],
                ['quaTang','!=',null]
            ])->exists();
            if ($checkPoll) {               
                return redirect()->back()->withInput()->with('error','Quý khách đã tham gia và đã chọn quà từ lần trước rồi ạ! Xin cảm ơn quý khách!');        
            } else {
                $id_guest = Guest::where('bienSoXe',strtoupper($request->bienSoXe))->first()->id;
                $guest = Guest::where('bienSoXe',strtoupper($request->bienSoXe))->update([
                    'hoTen' => $request->hoTen,
                    'dienThoai' => $request->dienThoai,
                    'diaChi' => $request->diaChi
                ]);
                if ($guest) {
                    session([
                        'guest' => $id_guest,
                        'active' => 1,
                        'bsx' => $request->bienSoXe
                    ]);
                    return redirect()->back()->withInput()->with('error','Quý khách đã đủ điều kiện tham gia chương trình, hệ thống sẽ chuyển chương trình trong 5 giây nữa. Vui lòng đợi....')->with('success','ok');
                }                
                else
                    return redirect()->back()->withInput()->with('error','Vì lý do kỹ thuật hệ thống đang quá tải, quý khách vui lòng thử lại ạ. Hoặc liên hệ bộ phận CSKH 0868 50 50 50 để được hỗ trợ ạ! Xin cảm ơn.');  
            }
        }        
        else
        return redirect()->back()->withInput()->with('error','Xin cảm ơn Quý Khách đã quan tâm chương trình. Xe Quý Khách hàng chưa đủ điều kiện tham gia chương trình này. Hẹn Quý Khách ở các chương trình tiếp theo. Xin cảm ơn!');        
    }

    public function postSubmitTracNghiem(Request $request) {
        $check = Guest::where('bienSoXe',strtoupper($request->bienSoXe))->exists();
        if($check) {
            $checkPoll = Guest::where([
                ['bienSoXe','=',strtoupper($request->bienSoXe)],
                ['ghiChu','!=',null]
            ])->exists();
            if ($checkPoll) {               
                return redirect()->back()->withInput()->with('error','Quý khách đã tham gia từ lần trước rồi ạ! Xin cảm ơn quý khách!');        
            } else {
                $id_guest = Guest::where('bienSoXe',strtoupper($request->bienSoXe))->first()->id;
                $guest = Guest::where('bienSoXe',strtoupper($request->bienSoXe))->update([
                    'hoTen' => $request->hoTen,
                    'dienThoai' => $request->dienThoai,
                    'diaChi' => $request->diaChi,
                    'ghiChu' => true
                ]);
                if ($guest) {
                    session([
                        'guest' => $id_guest,
                        'active' => 1,
                        'bsx' => $request->bienSoXe
                    ]);
                    return redirect()->back()->withInput()->with('error','Quý khách đã đủ điều kiện tham gia chương trình, hệ thống sẽ chuyển chương trình trong 5 giây nữa. Vui lòng đợi....')->with('success','ok');
                }                
                else
                    return redirect()->back()->withInput()->with('error','Vì lý do kỹ thuật hệ thống đang quá tải, quý khách vui lòng thử lại ạ. Hoặc liên hệ bộ phận CSKH 0868 50 50 50 để được hỗ trợ ạ! Xin cảm ơn.');  
            }
        }        
        else
        return redirect()->back()->withInput()->with('error','Xin cảm ơn Quý Khách đã quan tâm chương trình. Xe Quý Khách hàng chưa đủ điều kiện tham gia chương trình này. Hẹn Quý Khách ở các chương trình tiếp theo. Xin cảm ơn!');        
    }

    public function kiemTraTruocGhepHinh(Request $request) {
        $check = Guest::where('bienSoXe',strtoupper($request->bienSoXe))->exists();
        if($check) {
            $checkPoll = Guest::where([
                ['bienSoXe','=',strtoupper($request->bienSoXe)],
                ['dapAn','!=',null],
                ['ghiChu','!=',null]
            ])->exists();
            if ($checkPoll) {               
                return redirect()->back()->withInput()->with('error','Quý khách đã tham gia từ lần trước rồi ạ! Xin cảm ơn quý khách!');        
            } else {
                $id_guest = Guest::where('bienSoXe',strtoupper($request->bienSoXe))->first()->id;
                // $guest = Guest::where('bienSoXe',strtoupper($request->bienSoXe))->update([
                //     'hoTen' => $request->hoTen,
                //     'dienThoai' => $request->dienThoai,
                //     'diaChi' => $request->diaChi,
                //     'ghiChu' => false
                // ]);
                $infoGuest = Guest::where('bienSoXe',strtoupper($request->bienSoXe))->first();
                session([
                    'guest' => $id_guest,
                    'active' => 1,
                    'bsx' => $request->bienSoXe,
                    'isKhaoSat' => $infoGuest->ghiChu,
                    'dapAn' => $infoGuest->dapAn
                ]);
                return redirect()->back()->withInput()->with('error','Quý khách đã đủ điều kiện tham gia chương trình, hệ thống sẽ chuyển chương trình trong 5 giây nữa. Vui lòng đợi....')->with('success','ok');
            }
        }        
        else
        return redirect()->back()->withInput()->with('error','Xin cảm ơn Quý Khách đã quan tâm chương trình. Xe Quý Khách hàng chưa đủ điều kiện tham gia chương trình này. Hẹn Quý Khách ở các chương trình tiếp theo. Xin cảm ơn!');        
    }

    public function getQuayThuong() {
        $jsonString = file_get_contents('upload/cauhinh/config.json');
        $data = json_decode($jsonString, true);   
        $anh = NhomAnh::all();
        $arr = [];
        foreach($anh as $row) {
            if ($row->isPic)
                array_push($arr, "upload/image/" . $row->url);
        }
        // return view('quaythuong', ['data' => $data, 'anh' => $anh, 'pics' => $arr]);
        if (session('active'))
            return view('quaythuong', ['data' => $data, 'anh' => $anh, 'pics' => $arr]);
        else
            return redirect()->route('trangchu');

    }

    public function getGhepHinh() {
        $jsonString = file_get_contents('upload/cauhinh/config.json');
        $data = json_decode($jsonString, true);   
        // return view('ghephinhstep2', ['data' => $data]);
        if (session('active') && session('dapAn') == null)
            return view('ghephinhstep2', ['data' => $data]);
        elseif (session('active') && session('dapAn') != null)
            return redirect()->route('khaosat.ghephinh');
        else 
            return redirect()->route('trangchu');

    }

    public function getNhanQua() { 
        $anh = NhomAnh::all();
        $arr = [];
        foreach($anh as $row) {
            if (!$row->isPic && $row->counter > 0)
                array_push($arr, "upload/image/" . $row->url);
        }            
        // return view('nhanqua', ['anh' => $anh, 'pics' => $arr]);
        if (session('active'))
            return view('nhanqua', ['anh' => $anh, 'pics' => $arr]);
        else
            return redirect()->route('trangchu');

    }

    public function setGift(Request $request) {
        $idguest = session('guest') ? session('guest') : null;
        if ($idguest != null) {
            $guest = Guest::find($idguest);
            $guest->quaTang = $request->name;
            $guest->dapAn = $request->name;
            $guest->save();
            if ($guest){
                $arr = explode('/', $request->name);
                $namepath = $arr[2];
                $countercurent = NhomAnh::where('url',$namepath)->first()->counter;
                $idgift = NhomAnh::where('url',$namepath)->first()->id;
                $up = NhomAnh::find($idgift);
                $up->counter = $countercurent - 1;
                $up->save();
                session([
                    'active' => 0,
                    'guest' => 0
                ]);
                return response()->json([
                    'code' => 200,
                    'message' => 'Success',
                    'name' => $request->name
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

    public function setTimeComplete(Request $request) {
        $idguest = session('guest') ? session('guest') : null;
        if ($idguest != null) {
            $guest = Guest::find($idguest);
            $guest->dapAn = $request->timeCompleted;
            $guest->save();
            if ($guest){
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

    public function routeView(){
        $jsonString = file_get_contents('upload/cauhinh/config.json');
        $data = json_decode($jsonString, true);   
        $nhom = NhomAnh::all();
        $flag = true;
        foreach($nhom as $row) {
            if (!$row->isPic && $row->counter <= 0) {
                $flag = false;
                break;
            } 
            if (!$row->isPic && $row->counter > 0) {
                $flag = true;
            } 
        }
        if ($flag) {
            switch($data["cheDoQuay"]) {
                case 1: return view('welcome'); break;
                case 2: return view('trochoi.tracnghiem'); break;
                case 3: return view('trochoi.khaosat', ['linkGoogleDrive' => $data["linkGoogleForm"]]); break;
                case 4: return view('trochoi.ghephinh'); break;
                default: return "Máy chủ đang bảo trì!";
            }
        }
        else 
            return "Phần thưởng từ chương trình đã hết, xin quý khách vui lòng tham gia vào lần sau nhé. Xin cảm ơn quý khách!";
    }

    public function getTraLoiCauHoi() {
        $bsx = session('bsx') ? session('bsx') : null;
        if (session('active'))
            return view('trochoi.tracnghiem.traloicauhoi',['bsx' => $bsx]);
        else
            return redirect()->route('trangchu');
    }

    public function getKhaoSat() {
        $bsx = session('bsx') ? session('bsx') : null;
        if (session('active'))
            return view('khaosat', ['bsx' => $bsx]);
        else
            return redirect()->route('trangchu');
    }

    public function getKhaoSatV2() {
        $bsx = session('bsx') ? session('bsx') : null;
        if (session('active'))
            return view('khaosatv2', ['bsx' => $bsx]);
        else
            return redirect()->route('trangchu');
    }

    public function getKhaoSatForGhepHinh() {
        $bsx = session('bsx') ? session('bsx') : null;
        if (session('active'))
            return view('khaosatforghephinh', ['bsx' => $bsx]);
        else
            return redirect()->route('trangchu');
    }

    public function getKhaoSatPanel() {
        return view("khaosat.danhsach");
    }

    public function getKhaoSatList() {
        $ks = KhaoSat::all();
        if($ks)
            return response()->json([
                'code' => 200,
                'type' => 'success',
                'message' => "Đã tải dữ liệu",
                'data' => $ks
            ]);
        else
            return response()->json([
                'code' => 500,
                'type' => 'error',
                'message' => "Lỗi máy chủ"
            ]);
    }

    public function postKhaoSat(Request $request) {
        $ks = new KhaoSat();
        $ks->bienSoXe = $request->bienSo;
        $ks->c1 = $request->c1;
        $ks->c2 = $request->c2;
        $ks->c3 = $request->c3;
        $ks->c4 = $request->c4;
        $ks->c5 = $request->c5;
        $ks->c6 = $request->c6 ? $request->c6 : "Không";
        $ks->c7 = $request->c7;
        $ks->c8 = $request->c8;
        $ks->c9 = $request->c9;
        $ks->c10 = $request->c10 ? $request->c10 : "Không";
        $ks->save();
        if ($ks) {
            return response()->json([
                'code' => 200,
                'type' => 'info',
                'message' => 'Đã thực hiện khảo sát'
            ]);
        } else {
            return response()->json([
                'code' => 500,
                'type' => 'error',
                'message' => 'Không thể thực hiện'
            ]);
        }
    }

    public function postKhaoSatV2(Request $request) {
        $ks = new KhaoSat();
        $ks->bienSoXe = $request->bienSo;
        $ks->c1 = $request->c1;
        $ks->c2 = $request->c2;
        $ks->c3 = $request->c3;
        $ks->c4 = $request->c4;
        $ks->c5 = $request->c5;
        $ks->c6 = $request->c6 ? $request->c6 : "Không";
        $ks->c7 = $request->c7;
        $ks->c8 = $request->c8;
        $ks->c9 = $request->c9;
        $ks->c10 = $request->c10 ? $request->c10 : "Không";
        $ks->save();
        if ($ks) {
            $firstGift = NhomAnh::select("*")->where("isPic",false)->orderBy('id','desc')->first();
            if ($firstGift) {
                $up = NhomAnh::find($firstGift->id);
                $up->counter = $firstGift->counter - 1;
                $up->save();
            }            
            session([
                'active' => 0,
                'guest' => 0
            ]);
            return response()->json([
                'code' => 200,
                'type' => 'info',
                'message' => 'Đã thực hiện khảo sát'
            ]);
        } else {
            return response()->json([
                'code' => 500,
                'type' => 'error',
                'message' => 'Không thể thực hiện'
            ]);
        }
    }

    public function postKhaoSatForGhepHinh(Request $request) {
        $ks = new KhaoSat();
        $ks->bienSoXe = $request->bienSo;
        $ks->c1 = $request->c1;
        $ks->c2 = $request->c2;
        $ks->c3 = $request->c3;
        $ks->c4 = $request->c4;
        $ks->c5 = $request->c5;
        $ks->c6 = $request->c6 ? $request->c6 : "Không";
        $ks->c7 = $request->c7;
        $ks->c8 = $request->c8;
        $ks->c9 = $request->c9;
        $ks->c10 = $request->c10 ? $request->c10 : "Không";
        $ks->save();
        if ($ks) {
            $guest = Guest::where('bienSoXe',strtoupper($request->bienSo))
            ->update([
                'ghiChu' => true
            ]);          
            session([
                'active' => 0,
                'guest' => 0
            ]);
            return response()->json([
                'code' => 200,
                'type' => 'info',
                'message' => 'Đã thực hiện khảo sát'
            ]);
        } else {
            return response()->json([
                'code' => 500,
                'type' => 'error',
                'message' => 'Không thể thực hiện'
            ]);
        }
    }

    public function postKhaoSatSoMayMan(Request $request) {
        $ks = new KhaoSat();
        $ks->bienSoXe = $request->bienSo;
        $ks->c1 = $request->c1;
        $ks->c2 = $request->c2;
        $ks->c3 = $request->c3;
        $ks->c4 = $request->c4;
        $ks->c5 = $request->c5;
        $ks->c6 = $request->c6 ? $request->c6 : "Không";
        $ks->c7 = $request->c7;
        $ks->c8 = $request->c8;
        $ks->c9 = $request->c9;
        $ks->c10 = $request->c10 ? $request->c10 : "Không";
        $ks->save();
        if ($ks) {
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
                        'message' => 'Đã thực hiện khảo sát'
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
        } else {
            return response()->json([
                'code' => 500,
                'type' => 'error',
                'message' => 'Không thể thực hiện'
            ]);
        }
    }

    public function deleteKhaoSatAll(Request $request) {
        KhaoSat::truncate();
        return response()->json([
            'type' => 'success',
            'message' => 'Đã xóa tất cả',
            'code' => 200
        ]);
    }

    public function deleteKhaoSat(Request $request) {
        $ks = KhaoSat::find($request->id);       
        $ks->delete();
        if ($ks) {
            return response()->json([
                'type' => 'success',
                'message' => 'Đã xóa khảo sát!',
                'code' => 200
            ]);
        }
        else
            return response()->json([
                'type' => 'error',
                'message' => 'Lỗi xóa file từ máy chủ!',
                'code' => 500
            ]);
    }
}
