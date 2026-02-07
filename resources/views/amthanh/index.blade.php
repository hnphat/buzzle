@extends('layout.index')
@section('title')
    <title>Quản lý âm thanh</title>
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
  <h1>Quản lý âm thanh</h1>
  <button id="pressAdd" class="btn btn-success" data-toggle="modal" data-target="#importModal"><span class="fas fa-plus-circle"></span></button> 
  &nbsp;
  <select name="amLuong" id="amLuong" class="form-control" style="width: auto; display: inline-block;">
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
    <option value="6">6</option>
    <option value="7">7</option>
    <option value="8">8</option>
    <option value="9">9</option>
    <option value="10">10</option>
  </select>
  <button id="sendAmLuong" class="btn btn-primary">Send Âm lượng</button>
  &nbsp;
  <button id="sendDungPhat" class="btn btn-danger">Send Dừng phát</button>
  <hr>
  <table id="dataTable" class="display" style="width:100%">
      <thead>
      <tr class="bg-gradient-lightblue">
          <!-- <th>TT</th> -->
          <th>ID</th>
          <th>Âm thanh</th>
          <th>Nội dung</th>   
          <th>Ghi chú</th> 
          <th>Tác vụ</th>
      </tr>
      </thead>
      <tbody>
        
      </tbody>
  </table>
</div>
    <!-- Medal Import Data -->
    <div class="modal fade" id="importModal">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">THÊM ÂM THANH MỚI</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"> 
                    <form method="POST" id="importForm" enctype="multipart/form-data" autocomplete="off" enctype="multipart/form-data">
                        @csrf 
                        <div class="form-group">
                            <label>Âm thanh</label> 
                            <input type="file" class="form-control" name="importFile" placeholder="Choose File" id="importFile" required>
                            <span>Tối đa 20MB (.MP3)</span>
                        </div>     
                        <div class="form-group">
                            <label>Nội dung</label> 
                            <input placeholder="Nội dung âm thanh" type="text" name="tenAmThanh" class="form-control" required>
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
                ajax: "{{route('amthanh.danhsach')}}",
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
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return "<a href='upload/amthanh/"+row.amthanh+"' target='_blank'>Tải về</a>";                            
                        }
                    }, 
                    { "data": "noidung" },
                    { "data": "ghiChu" },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return `
                                <button id="phatNhac" data-id="`+data.id+`" class="btn btn-success btn-sm"><span class="fas fa-play-circle"></span></button>
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
                   $('input[name=noidung]').focus();
                }, 500);
            });

            //Delete data
            $(document).on('click','#delete', function(){
                if(confirm('Bạn có chắc muốn xóa?')) {
                    $.ajax({
                        url: "{{url('management/amthanh/ajax/delete/')}}",
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
                        url: "{{route('amthanh.post')}}",
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
                                icon: 'error',
                                title: 'Lỗi ' + response.responseJSON.message
                            })
                            $("#btnImport").attr('disabled', false).html("LƯU");
                        }
                    });
                }
            });

            // Send âm lượng
            $("#sendAmLuong").click(function(){
                var amLuong = $("#amLuong").val();
                $.ajax({
                    type:'POST',
                    url: "{{ route('amthanh.setamluong') }}",
                    data: {
                      "_token": "{{ csrf_token() }}",
                      "amluong": amLuong,
                      "stop": 0
                    },
                    success: (response) => {
                        if (response.code == 200) {
                          Toast.fire({
                              icon: 'success',
                              title: 'Đã gửi âm lượng: ' + amLuong
                          })
                        } else {
                          Toast.fire({
                              icon: 'error',
                              title: 'Lỗi không thể gửi âm lượng'
                          })
                        }
                    },
                    error: function(response){
                        Toast.fire({
                            icon: 'error',
                            title: 'Lỗi khi gửi âm lượng'
                        })
                    }
                });
            });

            // Send dừng phát
            $("#sendDungPhat").click(function(){
                if (confirm('Bạn có chắc muốn dừng phát âm thanh này?')) {
                    $.ajax({
                        type:'POST',
                        url: "{{ route('amthanh.setdungphat') }}",
                        data: {
                        "_token": "{{ csrf_token() }}",
                        "amluong": 0,
                        "stop": 1
                        },
                        success: (response) => {
                            if (response.code == 200) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Đã gửi lệnh dừng phát'
                            })
                            } else {
                            Toast.fire({
                                icon: 'error',
                                title: 'Lỗi không thể gửi lệnh dừng phát'
                            })
                            }
                        },
                        error: function(response){
                            Toast.fire({
                                icon: 'error',
                                title: 'Lỗi khi gửi lệnh dừng phát'
                            })
                        }
                    });
                }
            });

            // Play nhạc
            $(document).on('click','#phatNhac', function(){
                if (confirm('Bạn có chắc muốn phát âm thanh này?')) {
                    $.ajax({
                        type:'POST',
                        url: "{{ route('amthanh.setphatnhac') }}",
                        data: {
                        "_token": "{{ csrf_token() }}",
                        "amluong": 5,
                        "stop": 0,
                        "id": $(this).data('id')
                        },
                        success: (response) => {
                            if (response.code == 200) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Đã gửi lệnh phát nhạc'
                            })
                            } else {
                            Toast.fire({
                                icon: 'error',
                                title: 'Lỗi không thể gửi lệnh phát nhạc'
                            })
                            }
                        },
                        error: function(response){
                            Toast.fire({
                                icon: 'error',
                                title: 'Lỗi khi gửi lệnh phát nhạc'
                            })
                        }
                    });
                }
            });
        });
    </script>
@endsection