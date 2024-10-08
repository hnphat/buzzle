@extends('layout.index')
@section('title')
    <title>Quản lý cấu hình</title>
@endsection
@section('css')
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content_header')
 <!-- <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Welcome</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Welcome</li>
            </ol>
          </div>
        </div>
      </div>
  </div> -->
@endsection
@section('content')
<div class="container_fluid">
    <h1>Quản lý cấu hình</h1>
    <br/><br/>
    <form id="formData" method="post">
        @csrf
        <div class="row container">
            <div class="col-md-4"> 
                <div class="form-group">
                    <label>Hình nền quay số</label>
                    <input class="form-control" type="text" placeholder="VD: https://abc.com/anh.jpg" name="hinhNen" />
                </div>               
            </div>
            <div class="col-md-4">           
                <div class="form-group">
                    <label>Chọn trò chơi</label>
                    <select name="cheDoQuay" class="form-control">
                        <option value="1">Game lật hình</option>
                        <option value="2">Trả lời trắc nghiệm</option>
                    </select>
                    <p><i>1/ Game lật hình -> khách hàng nhập thông tin -> hệ thống xác nhận -> đúng thông tin khách hàng và còn quà tặng -> vào game chọn hình giống nhau -> chiến thắng -> trả lời khảo sát -> nhận quà -> kết thúc.</i></p>
                    <p><i>2/ Chế độ quay thưởng bỏ chọn: Phần thưởng sẽ được quay ngẫu nhiên và tự xoá chính nó khi quay trúng. Sử dụng dữ liệu <strong class="text-danger">Ảnh chủ đề</strong> để quay ngẫu nhiên và tự xoá chính nó trong cơ sỡ dữ liệu, kèm <strong class="text-danger">Chi tiết ảnh</strong> liên quan cũng sẽ bị xoá</i></p>
                </div> 
            </div>
        </div>
        <button id="saveConfig" class="btn btn-info">LƯU CẤU HÌNH</button>
    </form>
</div>
@endsection
@section('script')
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Below is plugin for datatables -->
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });       

        $(document).ready(function(){
            function onLoadData(){
                $.ajax({
                    url: "{{url('management/cauhinh/ajax/get/')}}",
                    type: "get",
                    dataType: "json",
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })                  
                        $("input[name=hinhNen]").val(response.data.hinhNen);  
                        $("input[name=mauKhung]").val(response.data.mauKhung);
                        $("input[name=doDay]").val(response.data.doDay);
                        $("input[name=choiNgoai]").val(response.data.choiNgoai);
                        $("input[name=choiTrong]").val(response.data.choiTrong);
                        $("input[name=doRong]").val(response.data.doRong);
                        $("input[name=chieuCao]").val(response.data.chieuCao);
                        $("input[name=viTriTren]").val(response.data.viTriTren);
                        $("input[name=viTriTrai]").val(response.data.viTriTrai);
                        $("select[name=cheDoQuay]").val(response.data.cheDoQuay);
                        $("select[name=batDauTrucTiep]").val(response.data.batDauTrucTiep);
                        $("select[name=active]").val(response.data.active);
                        $("select[name=test]").val(response.data.test);
                        // $("input[name=amThanh]").val(response.data.amThanh);
                        $("input[name=thoiGianCho]").val(response.data.thoiGianCho);
                        $("input[name=btnTop]").val(response.data.btnTop);
                        $("input[name=btnLeft]").val(response.data.btnLeft);
                        $("input[name=tocDoXoay]").val(response.data.tocDoXoay);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không thể tải cấu hình!"
                        })
                    }
                });
            }
            onLoadData();

            $("#saveConfig").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/cauhinh/ajax/saveconfig/')}}",
                    type: "post",
                    dataType: "json",
                    data: $("#formData").serialize(),
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })   
                        onLoadData();
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không thể lưu cấu hình!"
                        })
                    }
                });
            });
        });
    </script>
@endsection