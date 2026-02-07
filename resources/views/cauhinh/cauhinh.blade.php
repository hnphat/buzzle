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
                <div class="form-group">
                    <label>Hình cho trò ghép hình</label>
                    <input class="form-control" type="text" placeholder="VD: https://abc.com/anh.jpg" name="hinhNenGhepHinh" />
                </div>               
            </div>
            <div class="col-md-4">           
                <div class="form-group">
                    <label>Chọn trò chơi</label>
                    <select name="cheDoQuay" class="form-control">
                        <option value="1">Game lật hình</option>
                        <option value="2">Trả lời trắc nghiệm</option>
                        <option value="3">Khảo sát</option>
                        <option value="4">Ghép hình</option>
                        <option value="5">Vua tiếng Việt</option>
                        <option value="6">Đuổi hình bắt chữ</option>
                    </select>
                    <p><i>1/ Game lật hình -> khách hàng nhập thông tin -> hệ thống xác nhận -> đúng thông tin khách hàng và còn quà tặng -> vào game chọn hình giống nhau -> chiến thắng -> trả lời khảo sát -> nhận quà -> kết thúc.</i></p>
                    <p><i>2/ Trả lời trắc nghiệm -> khách hàng nhập thông tin -> hệ thống xác nhận -> đúng thông tin khách hàng và còn quà tặng -> vào trả lời câu hỏi trắc nghiệm -> trả lời đúng chọn số máy mắn -> kết thúc</p>
                    <p><i>3/ Khảo sát -> khách hàng nhập thông tin biển số xe -> hệ thống xác nhận -> Chuyển qua mẫu khảo sát google form</p>
                    <p><i>4/ Ghép hình -> khách hàng nhập thông tin biển số xe -> hệ thống xác nhận -> Chuyển qua chơi trò chơi -> thực hiện khảo sát</p>
                    <p><i>5/ Vua tiếng Việt -> Ghép chữ (dùng cho nhân viên Hyundai)</p>
                    <p><i>6/ Đuổi hình bắt chữ -> Nhìn hình đoán chữ (dùng cho nhân viên Hyundai)</p>
                </div> 
                <div class="form-group">
                    <label>Nếu chọn trò chơi Khảo sát nhập link google form:</label>
                    <input class="form-control" type="text" placeholder="Nhập link google form" name="linkGoogleForm" />
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
                        $("input[name=hinhNenGhepHinh]").val(response.data.hinhNenGhepHinh);  
                        $("select[name=cheDoQuay]").val(response.data.cheDoQuay);
                        $("input[name=linkGoogleForm]").val(response.data.linkGoogleForm);                          
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