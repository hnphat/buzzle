<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Khảo sát</title>
  <base href="{{asset('')}}" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body {
      background-image: url("{{asset('images/nen.jpg')}}");
      background-attachment: fixed;
    }
  </style>
</head>
<body class="container">
  <h4>Xin chào Quý khách hàng, để tham gia chương trình Quý khách vui lòng điền thông tin như bên dưới</h4>
    <form method="post" action="{{route('postthongtintracnghiem')}}" id="addForm" autocomplete="off">
        @csrf
        <div class="form-group">
            <label>Biển số xe <span class="text-danger">(*)</span></label> 
            <input required placeholder="Ví dụ: 67A-23123 (không chứa khoảng trắng)" type="text" name="bienSoXe" value="{{ old('bienSoXe') }}"  class="form-control">
            <span><i>Quý khách vui lòng nhập đúng định dạng biển số xe như ví dụ để hệ thống kiểm tra xác thực đúng thông tin</i></span>
        </div>          
        <button id="btnAdd" class="btn btn-primary">THAM GIA</button>         
    </form>
    <div>
        <div class="modal fade" id="notifyModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">THÔNG BÁO</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">    
                       <p><i>
                        @if (session('error'))                        
                         {{ session('error') }}
                        @endif            
                       </i></p>
                    </div>
                    <div class="modal-footer">
                        <p class="text-center"><button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button><p>
                    </div>
            </div>
        </div>
    </div>     
    <script>
      $(document).ready(function(){
        @if (session('error'))     
          $("#notifyModal").modal();       
        @endif
        @if (session('success'))     
          setTimeout(() => {
            open("{{$linkGoogleDrive}}",'_self');
          }, 5000);  
        @endif
      });
    </script>
</body>
</html>
