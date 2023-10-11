@extends('layout.index')
@section('title')
    <title>Buzzle Game</title>
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
  <h1>Buzzle Game</h1>
  <button id="pressAdd" class="btn btn-success" data-toggle="modal" data-target="#addModal"><span class="fas fa-plus-circle"></span></button><br/><br/>
  <table id="dataTable" class="display" style="width:100%">
      <thead>
      <tr class="bg-gradient-lightblue">
          <th>TT</th>
          <th>Tên</th>
          <th>Hình ảnh</th>       
          <th>Loại</th>       
          <th>Số lượng</th>
          <th>Tác vụ</th>
      </tr>
      </thead>
  </table>
</div>
<!--  MEDAL -->
    <div>
        <!-- Medal Add -->
        <div class="modal fade" id="addModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Thêm nhóm ảnh</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"> 
                        <form method="POST" enctype="multipart/form-data" id="addForm" autocomplete="off">
                            <div class="form-group">
                               <label>Nội dung</label> 
                               <input type="text" name="noiDung" class="form-control" placeholder="Chú ý 1 cặp hình ký tự đầu giống nhau">
                            </div>                          
                            <div class="form-group">
                                <input type="file" class="form-control" name="file" placeholder="Choose File" id="file">
                                <span>Tối đa 2MB (png,jpg,PNG,JPG)</span>
                            </div>              
                            <div class="form-group">
                               <label>Loại</label> 
                               <select name="loai" id="loai" class="form-control">
                                    <option value="1">Hình game</option>
                                    <option value="0">Quà tặng</option>
                               </select>
                            </div>  
                            <div class="form-group">
                               <label>Số lượng</label> 
                               <input type="number" name="soLuong" id="soLuong" class="form-control" placeholder="Nếu là quà tặng nhập số lượng quà">
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
<!--- -->  
<!--  MEDAL -->
<div>
        <!-- Medal Add -->
        <div class="modal fade" id="editModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Chỉnh sửa thông tin</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"> 
                        <form method="POST" enctype="multipart/form-data" id="editForm" autocomplete="off">
                            <input type="hidden" name="idToEdit">
                            <div class="form-group">
                               <label>Nội dung</label> 
                               <input type="text" name="enoiDung" class="form-control" placeholder="Nhập nội dung hình">
                            </div>          
                            <div class="form-group">
                                <label>Hình cũ:</label>
                                <img id="oldPic" src="" alt="Pic" style="width: 200px; height: auto;"> <br/>
                                <label>Hình mới:</label>
                                <input type="file" class="form-control" name="efile" placeholder="Choose File" id="file">
                                <span>Tối đa 2MB (png,jpg,PNG,JPG)</span>
                            </div>            
                            <div class="form-group">
                               <label>Loại</label> 
                               <select name="eloai" id="eloai" class="form-control">
                                    <option value="1">Hình game</option>
                                    <option value="0">Quà tặng</option>
                               </select>
                            </div>  
                            <div class="form-group">
                               <label>Số lượng</label> 
                               <input type="number" name="esoLuong" id="esoLuong" class="form-control" placeholder="Nếu là quà tặng nhập số lượng quà">
                            </div>  
                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                        <button id="btnUpdate" class="btn btn-primary" form="editForm">Lưu</button>
                    </div>
                    </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </div> 
<!--- -->                        
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
            var table = $('#dataTable').DataTable({
                // paging: false,    use to show all data
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: "{{ url('management/nhomanh/ajax/list/') }}",
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
                    { "data": "noiDung" },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return "<a href='upload/image/"+row.url+"' target='_blank'><img src='upload/image/"+row.url+"' style='width:100px; height:auto;'/></a>";                            
                        }
                    },                       
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return (row.isPic == true) ? "<strong class='text-pink'>Hình game</strong>" : "<strong class='text-success'>Quà tặng</strong>";                            
                        }
                    },
                    { "data": "counter" },           
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return "<button id='btnEdit' data-id='"+row.id+"' data-toggle='modal' data-target='#editModal' class='btn btn-success btn-sm'><span class='far fa-edit'></span></button>&nbsp;&nbsp;" + "<button id='delete' data-id='"+row.id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button>";
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
            $("#btnAdd").click(function(){   
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $("#addForm").one("submit", submitFormFunction);
                function submitFormFunction(e) {
                    e.preventDefault();   
                    var formData = new FormData(this);
                    $.ajax({
                        type:'POST',
                        url: "{{ url('management/nhomanh/ajax/post/')}}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $("#btnAdd").attr('disabled', true).html("Đang xử lý....");
                        },
                        success: (response) => {
                            this.reset();
                            Toast.fire({
                                icon: 'info',
                                title: response.message
                            })
                            table.ajax.reload();
                            $("#addModal").modal('hide');
                            $("#btnAdd").attr('disabled', false).html("LƯU");
                            console.log(response);
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
                }
            });

            //Delete data
            $(document).on('click','#delete', function(){
                if(confirm('Bạn có chắc muốn xóa?')) {
                    $.ajax({
                        url: "{{url('management/nhomanh/ajax/delete/')}}",
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

            // get data to edit
            $(document).on("click","#btnEdit", function(){
                $.ajax({
                    url: "{{route('nhom.getedit')}}",
                    type: "get",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        $("input[name=enoiDung]").val(response.data.noiDung);
                        $("select[name=eloai]").val(response.data.isPic);
                        $("input[name=esoLuong]").val(response.data.counter);
                        $("input[name=idToEdit]").val(response.data.id);
                        $("#oldPic").attr("src","./upload/image/" + response.data.url);
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                    },
                    error: function() {

                    }
                });
            });

            // Edit data
            $("#btnUpdate").click(function(){   
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $("#editForm").one("submit", submitEditFormFunction);
                function submitEditFormFunction(e) {
                    e.preventDefault();   
                    var formData = new FormData(this);
                    $.ajax({
                        type:'POST',
                        url: "{{ url('management/nhomanh/ajax/postedit/')}}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $("#btnUpdate").attr('disabled', true).html("Đang xử lý....");
                        },
                        success: (response) => {
                            this.reset();
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            table.ajax.reload();
                            $("#editModal").modal('hide');
                            $("#btnUpdate").attr('disabled', false).html("LƯU");
                        },
                            error: function(response){
                            Toast.fire({
                                icon: 'error',
                                title: 'Lỗi ' + response.responseJSON.message
                            })
                            $("#editModal").modal('hide');
                            $("#btnUpdate").attr('disabled', false).html("LƯU");
                            console.log(response);
                        }
                    });
                }
            });
        });
    </script>
@endsection