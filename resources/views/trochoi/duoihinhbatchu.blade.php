<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Đuổi hình bắt chữ Hyundai An Giang</title>
  <base href="{{asset('')}}" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body {
      background-image: url("{{asset('images/assets/newnen.jpg')}}");
      background-size: cover;      /* phủ kín */
      background-position: center; /* căn giữa */
      background-repeat: no-repeat;
    }

    #duoiHinh {
      color: #ff6600;
      font-weight: 900;
      letter-spacing: 2px;
      animation: simple-pulse 1.5s ease-in-out infinite;
    }
    
    @keyframes simple-pulse {
      0%, 100% {
        transform: scale(1);
      }
      50% {
        transform: scale(1.08);
      }
    }
    
    #cauTraLoi {
      font-size: 4rem;
      font-weight: 900;
      color: #ff2a00;
      /* text-shadow: 
        3px 3px 8px rgba(0, 0, 0, 0.6),
        0 0 15px rgba(255, 0, 102, 0.9),
        0 0 25px rgba(255, 0, 102, 0.7),
        0 0 35px rgba(0, 255, 255, 0.5); */
      letter-spacing: 2px;
      animation: pulse-glow 1.5s ease-in-out infinite;
      transform: scale(1);
    }
    
    @keyframes pulse-glow {
      0%, 100% {
        /* text-shadow: 
          3px 3px 8px rgba(0, 0, 0, 0.6),
          0 0 15px rgba(255, 0, 102, 0.9),
          0 0 25px rgba(255, 0, 102, 0.7),
          0 0 35px rgba(0, 255, 255, 0.5); */
        transform: scale(1);
      }
      50% {
        /* text-shadow: 
          3px 3px 8px rgba(0, 0, 0, 0.6),
          0 0 25px rgba(255, 0, 102, 1),
          0 0 40px rgba(255, 0, 102, 0.9),
          0 0 50px rgba(0, 255, 255, 0.7); */
        transform: scale(1.05);
      }
    }
  </style>
</head>
<body class="container-fluid">
    <audio id="gameSong" style="display: none;"></audio>

    <p class="text-center" style="cursor: pointer;">
      <h1 id="duoiHinh" class="text-center">ĐUỔI HÌNH BẮT CHỮ</h1>
      <p class="text-center" id="btnBatDau" style="cursor: pointer;">
        <img src="{{asset('images/assets/start.gif')}}" alt="Starting" style="width: 100%; max-width: 600px; cursor: pointer;" />
      </p>
    </p>    
    <div id="startShow" style="display: none;">
      <input type="hidden" id="idCurrent" value="" />
      <p class="text-center">
        <button id="btnSau" class="btn btn-primary mt-3">CÂU KẾ TIẾP</button>
        &nbsp;&nbsp;&nbsp;
        <button id="btnDapAn" class="btn btn-primary mt-3">ĐÁP ÁN</button>
      </p>
      <div>
        <img id="cauHoi" src="{{asset('images/assets/logo.gif')}}" alt="Đuổi hình bắt chữ" class="img-fluid mx-auto d-block" style="max-height: 400px;"/>
      </div>
    </div>


    <!--  MEDAL -->
    <div>
        <!-- Medal Add -->
        <div class="modal fade" id="thongBaoModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body"> 
                        <h1 class="text-center text-pink">ĐÁP ÁN</h1>
                        <h1 id="ketQua" class="text-center text-success" style="text-transform: uppercase; font-weight: 900;"></h1>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </div> 
  <!--- -->  
  <script>
    // Thay đổi URL bài hát ở đây
    var musicSong = "{{ asset('music/gameshowsong.mp3') }}"; // Đặt đường dẫn bài hát của bạn
    // =========================================================
    $(document).ready(function() {    
      $("#btnBatDau").click(function() {
        // Phát nhạc khi bắt đầu trò chơi
        var audio = document.getElementById('gameSong');
        audio.src = musicSong;
        audio.play().catch(function(error) {
          console.log('Không thể phát nhạc:', error);
        });

        $("#btnBatDau").hide();
        $("#startShow").show();
        // Load first question
        $.ajax({
            type:'POST',
            url: "{{ route('duoihinhbatchu.laycauhoi') }}",
            data: {
              "_token": "{{ csrf_token() }}"
            },
            success: (response) => {
                if (response.code == 200) {
                    $("#cauHoi").attr("src", "{{asset('upload/duoihinhbatchu/')}}" + "/" + response.data.cauhoi);
                    $("#ketQua").text(response.data.cautraloi);
                    $("#idCurrent").val(response.data.id);
                } else {
                    alert("Hết câu hỏi!");
                }
            },
            error: function(response){
                alert("Lỗi tải câu hỏi!");
            }
        });
      });

      $("#btnDapAn").click(function() {
        if (confirm("Bạn có chắc muốn xem đáp án?")) {
          $("#thongBaoModal").modal('show');
          // Chuyển sang not active
          $.ajax({
            type:'POST',
            url: "{{ route('duoihinhbatchu.setnotactive') }}",
            data: {
              "_token": "{{ csrf_token() }}",
              "id": $("#idCurrent").val()
            },
            success: (response) => {
                if (response.code == 200) {
                    console.log("Đã chuyển sang not active");
                } else {
                    alert("Lỗi set not active!");
                }
            },
            error: function(response){
                alert("Lỗi set not active!");
            }
          });
        }
      });

      $("#btnSau").click(function() {
        $("#ketQua").text("");
        $("#thongBaoModal").modal('hide');
        // Load next question
        $.ajax({
            type:'POST',
            url: "{{ route('duoihinhbatchu.laycauhoi') }}",
            data: {
              "_token": "{{ csrf_token() }}"
            },
            success: (response) => {
                if (response.code == 200) {
                    $("#cauHoi").attr("src", "{{asset('upload/duoihinhbatchu/')}}" + "/" + response.data.cauhoi);
                    $("#ketQua").text(response.data.cautraloi);
                    $("#idCurrent").val(response.data.id);
                } else {
                    alert("Hết câu hỏi!");
                }
            },
            error: function(response){
                alert("Lỗi tải câu hỏi!");
            }
        });
      });
    });
  </script>
</body>
</html>
