@extends('layout.index')
@section('title')
    <title>Đổi mật khẩu</title>
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
  <h1>Đổi mật khẩu</h1>
    <form id="changeInfo" autocomplete="off">
        {{csrf_field()}}
        <div class="form-group">
            <label for="oldPass">Mật khẩu cũ</label>
            <input autofocus="autofocus" type="password" name="oldPass" id="oldPass" class="form-control" required="required"/>
        </div>
        <div class="form-group">
            <label for="newPass">Mật khẩu mới</label>
            <input type="password" name="newPass" id="newPass" class="form-control" required="required"/>
        </div>
        <div class="form-group">
            <label for="newPassAgain">Nhập lại mật khẩu mới</label>
            <input type="password" name="newPassAgain" id="newPassAgain" class="form-control" required="required"/>
        </div>
        <button id="updateInfo" class="btn btn-success" form="changeInfo">CẬP NHẬT THÔNG TIN</button>
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
        // Exe
        $(document).ready(function() {
            $("#updateInfo").click(function(){   
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $("#changeInfo").one("submit", submitFormFunction);
                function submitFormFunction(e) {
                    e.preventDefault();   
                    var formData = new FormData(this);
                    // Kiểm tra mật khẩu có đang khớp đúng không
                    var newPass = $("#newPass").val();
                    var newPassAgain = $("#newPassAgain").val();
                    if (newPass !== newPassAgain) {
                        Toast.fire({
                            icon: 'error',
                            title: 'Mật khẩu mới không khớp!'
                        });
                        $("#updateInfo").attr('disabled', false).html("CẬP NHẬT THÔNG TIN");
                        return false;
                    }

                    $.ajax({
                        type:'POST',
                        url: "{{route('change.password')}}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $("#updateInfo").attr('disabled', true).html("Đang upload vui lòng đợi....");
                        },
                        success: (response) => {
                            if (response.code == 200) {
                                this.reset();
                                Toast.fire({
                                    icon: response.type,
                                    title: response.message
                                })
                                $("#updateInfo").attr('disabled', false).html("CẬP NHẬT THÔNG TIN");
                            }                           
                        },
                            error: function(response){
                            Toast.fire({
                                icon: 'error',
                                title: 'Lỗi ' + response.responseJSON.message
                            })
                            $("#updateInfo").attr('disabled', false).html("CẬP NHẬT THÔNG TIN");
                        }
                    });
                }
            });
        });
    </script>
@endsection