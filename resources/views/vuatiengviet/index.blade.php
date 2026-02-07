@extends('layout.index')
@section('title')
    <title>Vua Tiếng Việt</title>
@endsection
@section('css')
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content_header')
@endsection
@section('content')
<div class="container_fluid">
  <h1>Vua Tiếng Việt</h1>
  <button id="pressAdd" class="btn btn-success" data-toggle="modal" data-target="#addModal"><span class="fas fa-plus-circle"></span></button> 
  &nbsp;
  <button id="importData" class="btn btn-primary" data-toggle="modal" data-target="#importModal">NHẬP TỪ FILE</button><br/><br/>
  <hr>
  <table id="dataTable" class="display" style="width:100%">
      <thead>
      <tr class="bg-gradient-lightblue">
          <!-- <th>TT</th> -->
          <th>ID</th>
          <th>Câu hỏi</th>
          <th>Câu trả lời</th>   
          <th>Trạng thái</th>
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
                    <h4 class="modal-title">Thêm câu hỏi mới</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"> 
                    <form method="POST" id="addForm" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label>Câu hỏi</label> 
                            <input placeholder="Câu hỏi" type="text" name="cauHoi" class="form-control" required>
                        </div>  
                        <div class="form-group">
                            <label>Câu trả lời</label> 
                            <input placeholder="Câu trả lời" type="text" name="cauTraLoi" class="form-control" required>
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


    <!-- Medal Edit-->
    <div class="modal fade" id="editModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Sửa câu hỏi</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"> 
                    <form method="POST" id="editForm" autocomplete="off">
                        @csrf
                        <input type="hidden" name="id" id="edit_id">
                        <div class="form-group">
                            <label>Câu hỏi</label> 
                            <input placeholder="Câu hỏi" type="text" name="ecauHoi" class="form-control" required>
                        </div>  
                        <div class="form-group">
                            <label>Câu trả lời</label> 
                            <input placeholder="Câu trả lời" type="text" name="ecauTraLoi" class="form-control" required>
                        </div>    
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                    <button id="btnEdit" class="btn btn-primary" form="editForm">Lưu</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- Medal Import Data -->
    <div class="modal fade" id="importModal">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">NHẬP TỪ FILE</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"> 
                    <form method="POST" id="importForm" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>File import mẫu : <a href="./upload/template/vuatiengviet.xlsx"> Tải về </a></label>                             
                        </div>  
                        <div class="form-group">
                            <label>File import</label> 
                            <input type="file" class="form-control" name="importFile" placeholder="Choose File" id="importFile">
                            <span>Tối đa 2MB (xlsx)</span>
                        </div>     
                        <i><strong class="text-danger">Lưu ý: Nhập từ file sẽ xoá tất cả dữ liệu đang có và tạo dữ liệu mới</strong></i>     
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
    <!--  MEDAL -->  
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
            var table = $('#dataTable').DataTable({
                // paging: false,    use to show all data
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: "{{route('vuatiengviet.danhsach')}}",
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
                    // { "data": null },
                    { "data": "id" },
                    { "data": "cauhoi" },
                    { "data": "cautraloi" },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (data.isActive) {
                                return `<span class="badge badge-success">Chưa trả lời</span>`;
                            } else {
                                return `<span class="badge badge-danger">Đã trả lời</span>`;
                            }
                        }
                    },  
                    { "data": "ghiChu" },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return `
                                <button id="edit" data-id="`+data.id+`" class="btn btn-primary btn-sm mr-1" data-toggle="modal" data-target="#editModal"><span class="fas fa-edit"></span></button>
                                &nbsp;
                                <button id="delete" data-id="`+data.id+`" class="btn btn-danger btn-sm"><span class="fas fa-trash-alt"></span></button>
                            `;
                        }
                    }   
                ]
            });
            // table.on( 'order.dt search.dt', function () {
            //     table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            //         cell.innerHTML = i+1;
            //         table.cell(cell).invalidate('dom');
            //     } );
            // } ).draw();

            $("#pressAdd").click(function(){
                setTimeout(() => {
                   $('input[name=cauHoi]').focus();
                }, 500);
            });

            // Add data
            $("#btnAdd").click(function(e){  
                e.preventDefault(); 
                $.ajax({
                    type:'POST',
                    url: "{{ url('management/vuatiengviet/ajax/post/')}}",      
                    dataType: "json",
                    data: $('#addForm').serialize(),             
                    beforeSend: function () {
                        $("#btnAdd").attr('disabled', true).html("Đang xử lý....");
                    },
                    success: (response) => { 
                        $('#addForm')[0].reset();
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        $("#addModal").modal('hide');
                        $("#btnAdd").attr('disabled', false).html("LƯU");
                        table.ajax.reload();
                    },
                        error: function(response){
                        Toast.fire({
                            icon: 'error',
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
                        url: "{{url('management/vuatiengviet/ajax/delete/')}}",
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

            //Delete data
            $(document).on('click','#edit', function(){
                $.ajax({
                    url: "{{url('management/vuatiengviet/ajax/edit/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        if (response.code == 200) {
                            $('#edit_id').val(response.data.id);
                            $('input[name=ecauHoi]').val(response.data.cauhoi);
                            $('input[name=ecauTraLoi]').val(response.data.cautraloi);
                        } else {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                        }
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không thể chỉnh sửa lúc này!"
                        })
                    }
                });
            });

            // Post Edit data
            $("#btnEdit").click(function(e){  
                e.preventDefault(); 
                $.ajax({
                    type:'POST',
                    url: "{{ url('management/vuatiengviet/ajax/update/')}}",      
                    dataType: "json",
                    data: $('#editForm').serialize(),             
                    beforeSend: function () {
                        $("#btnEdit").attr('disabled', true).html("Đang xử lý....");
                    },
                    success: (response) => { 
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        $("#editModal").modal('hide');
                        $("#btnEdit").attr('disabled', false).html("LƯU");
                        table.ajax.reload();
                    },
                        error: function(response){
                        Toast.fire({
                            icon: 'error',
                            title: 'Lỗi ' + response.responseJSON.message
                        })
                        $("#editModal").modal('hide');
                        $("#btnEdit").attr('disabled', false).html("LƯU");
                        console.log(response);
                    }
                });
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
                        url: "{{route('import.vuatiengviet')}}",
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