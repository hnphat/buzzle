@extends('layout.index')
@section('title')
    <title>Quản lý khách hàng</title>
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
  <h1>Quản lý khách hàng</h1>
  <button id="pressAdd" class="btn btn-success" data-toggle="modal" data-target="#addModal"><span class="fas fa-plus-circle"></span></button> 
  <button id="importGuest" class="btn btn-info" data-toggle="modal" data-target="#importModal">Import Guest</button>&nbsp;
  <button id="xoaAll" class="btn btn-danger">Xoá tất cả</button><br/><br/>
  <br/><br/>
  <table id="dataTable" class="display" style="width:100%">
      <thead>
      <tr class="bg-gradient-lightblue">
          <th>TT</th>
          <th>Họ và tên</th>
          <th>Điện thoại</th>          
          <th>Biển số xe</th>
          <th>Địa chỉ</th>
          <th>Quà tặng</th>
          <th>Kết quả</th>
          <th>Thời gian tham gia</th>
          <th>Ghi chú</th>
          <th>Tác vụ</th>
      </tr>
      </thead>
      <tbody>
        
      </tbody>
  </table>
</div>
<!--  MEDAL -->
<div>
    <!-- Medal Add -->
    <div class="modal fade" id="addModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Thêm khách hàng</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"> 
                    <form method="POST" id="addForm" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label>Biển số xe</label> 
                            <input placeholder="Biển số xe" type="text" name="bienSoXe" class="form-control">
                        </div>  
                        <div class="form-group">
                            <label>Họ và tên</label> 
                            <input placeholder="Họ và tên" type="text" name="hoTen" class="form-control">
                        </div>    
                        <div class="form-group">
                            <label>Điện thoại</label> 
                            <input placeholder="Điện thoại" type="text" name="dienThoai" class="form-control">
                        </div>                             
                        <div class="form-group">
                            <label>Địa chỉ</label> 
                            <input placeholder="Địa chỉ" type="text" name="diaChi" class="form-control">
                        </div>               
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                    <button id="btnAdd" class="btn btn-primary" form="addForm">Lưu</button>
                </div>
                </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>   
<!--  MEDAL -->
<div>
    <!-- Medal Add -->
    <div class="modal fade" id="importModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Import guest</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"> 
                    <form method="POST" id="importForm" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>File import mẫu : <a href="./upload/template/sample.xlsx"> Tải về </a></label>                             
                        </div>  
                        <div class="form-group">
                            <label>File import</label> 
                            <input type="file" class="form-control" name="importFile" placeholder="Choose File" id="importFile">
                            <span>Tối đa 2MB (xlsx)</span>
                        </div>     
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button id="btnImport" class="btn btn-primary" form="importForm">Lưu</button>
                </div>
                </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
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
            // $('#dataTable').DataTable({
            //     // paging: false,    use to show all data
            //     responsive: true,
            //     dom: 'Blfrtip',
            //     buttons: [
            //         'copy', 'csv', 'excel', 'pdf', 'print'
            //     ]
            // });
            var table = $('#dataTable').DataTable({
                // paging: false,    use to show all data
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: "{{route('guest.danhsach')}}",
                "columnDefs": [ {
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                } ],
                "order": [
                    [ 0, 'desc' ]
                ],
                lengthMenu:  [5, 10, 25, 50, 75, 100 ],
                columns: [
                    { "data": null },
                    { "data": "hoTen" },
                    { "data": "dienThoai" },
                    { "data": "bienSoXe" },
                    { "data": "diaChi" },
                    {
                        "data": null,
                        render: function(data, type, row) {
                           return `<img src="{{asset('')}}${row.quaTang}" height="100" alt="Qua">`;
                        }
                    },
                    { "data": "dapAn" },
                    {
                        "data": null,
                        render: function(data, type, row) {
                           return `${row.real_time}`
                        }
                    },
                    { "data": "ghiChu" },
                    {
                        "data": null,
                        render: function(data, type, row) {                            
                            return `<button id='delete' data-id='${row.id}' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button>`;
                        }
                    }
                ]
            });
            table.on( 'order.dt search.dt', function () {
                table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                    table.cell(cell).invalidate('dom');
                } );
            } ).draw();

            // Add data
            $("#btnAdd").click(function(e){  
                e.preventDefault(); 
                $.ajax({
                    type:'POST',
                    url: "{{ url('management/guest/ajax/post/')}}",      
                    dataType: "json",
                    data: $('#addForm').serialize(),             
                    beforeSend: function () {
                        $("#btnAdd").attr('disabled', true).html("Đang xử lý....");
                    },
                    success: (response) => { 
                        $('#addForm')[0].reset();
                        Toast.fire({
                            icon: 'info',
                            title: response.message
                        })
                        // table.ajax.reload();
                        $("#addModal").modal('hide');
                        $("#btnAdd").attr('disabled', false).html("LƯU");
                        table.ajax.reload();
                    },
                        error: function(response){
                        Toast.fire({
                            icon: 'info',
                            title: 'Lỗi ' + response.responseJSON.message
                        })
                        $("#addModal").modal('hide');
                        $("#btnAdd").attr('disabled', false).html("LƯU");
                        console.log(response);
                    }
                });
            });

            //Delete data
            $(document).on('click','#delete', function(){
                if(confirm('Bạn có chắc muốn xóa?')) {
                    $.ajax({
                        url: "{{url('management/guest/ajax/delete/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $(this).data('id')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            table.ajax.reload();
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không thể xóa lúc này!"
                            })
                        }
                    });
                }
            });

            //Delete all
            $(document).on('click','#xoaAll', function(){
                if(confirm('Bạn có chắc muốn xóa\nLưu ý: Không thể hoàn tác thao tác khi đã xoá?')) {
                    $.ajax({
                        url: "{{url('management/guest/ajax/delete/all')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $(this).data('id')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            table.ajax.reload();
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không thể xóa lúc này!"
                            })
                        }
                    });
                }
            });

            // Import data
            $("#btnImport").click(function(){   
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $("#importForm").one("submit", submitFormFunction);
                function submitFormFunction(e) {
                    e.preventDefault();   
                    var formData = new FormData(this);
                    $.ajax({
                        type:'POST',
                        url: "{{route('import.guest')}}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $("#btnImport").attr('disabled', true).html("Đang upload vui lòng đợi....");
                        },
                        success: (response) => {
                            if (response.code == 200) {
                                this.reset();
                                Toast.fire({
                                    icon: response.type,
                                    title: response.message
                                })
                                $("#importModal").modal('hide');
                                $("#btnImport").attr('disabled', false).html("LƯU");
                                table.ajax.reload();
                            }                           
                        },
                            error: function(response){
                            Toast.fire({
                                icon: 'info',
                                title: 'Lỗi ' + response.responseJSON.message
                            })
                            $("#importModal").modal('hide');
                            $("#btnImport").attr('disabled', false).html("LƯU");
                        }
                    });
                }
            });
        });
    </script>
@endsection