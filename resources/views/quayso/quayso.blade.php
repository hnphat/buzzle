@extends('layout.index')
@section('title')
    <title>Quay số</title>
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
  <h1>Quay số</h1>
  <button id="pressAdd" class="btn btn-success" data-toggle="modal" data-target="#addModal"><span class="fas fa-plus-circle"></span></button> 
  <a class="btn btn-info" href="{{route('quayso')}}">BẮT ĐẦU QUAY SỐ</a>
  <br/><br/>
  <table id="dataTable" class="display" style="width:100%">
      <thead>
      <tr class="bg-gradient-lightblue">
          <!-- <th>TT</th> -->
          <th>Thời gian</th>
          <th>Số</th>
          <th>Trạng thái</th>    
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
                    <h4 class="modal-title">Khởi tạo dãy số</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"> 
                    <form method="POST" id="addForm" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label>Số bắt đầu</label> 
                            <input placeholder="Số bắt đầu" type="number" name="soBatDau" class="form-control" required>
                        </div>  
                        <div class="form-group">
                            <label>Số kết thúc</label> 
                            <input placeholder="Số kết thúc" type="number" name="soKetThuc" class="form-control" required>
                        </div>
                        <i><strong class="text-danger">Lưu ý: Khởi tạo sẽ xoá tất cả dãy số đang có và tạo dãy số mới</strong></i>     
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
                ajax: "{{route('quayso.danhsach')}}",
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
                    { "data": "ngayTao" },
                    { "data": "so" },
                    {
                        "data": null,
                        render: function(data, type, row) {
                           if (row.daChon) {
                            return "<strong class='text-success'>Đã quay trúng</strong>";
                           } else {
                            return "<strong class='text-secondary'>Chưa quay</strong>";
                           }
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

            // Add data
            $("#btnAdd").click(function(e){  
                e.preventDefault(); 
                if (confirm("Xác nhận khởi tạo dãy số mới và xoá dãy số cũ?")) {
                    $.ajax({
                        type:'POST',
                        url: "{{ url('management/quayso/ajax/post/')}}",      
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
                }
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