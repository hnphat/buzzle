<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Âm thanh event</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <base href="{{asset('')}}" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="container-fluid">
    <div>
      <audio id="gameSong" style="display: none;"></audio>
      <button id="startBtn" class="btn btn-primary btn-lg" style="margin-top: 20px;">🎵 Bắt đầu lắng nghe</button>
      <div id="playerUI" style="display: none;">
        <h2 class="mt-3">Bài hát hiện tại: <span id="baiHat">Chưa có</span></h2>
        <h4>Mức âm lượng: <span id="mucAmLuong">0</span></h4>
        <h4>Trạng thái: <span id="trangThai">Chưa có</span></h4>
      </div>
    </div>
  <script>
    // Danh sách bài hát sẽ được load từ server
    var musicSongs = []; // array of URLs
    var audio = document.getElementById('gameSong');
    // Tải danh sách âm thanh từ server và lưu vào `musicSongs`
    $.getJSON("{{ route('amthanh.danhsach') }}", function(res) {
      if (res && res.code == 200 && Array.isArray(res.data)) {
        musicSongs = res.data.map(function(item) {
          return "{{ asset('upload/amthanh') }}" + "/" + item.amthanh;
        });
        // cập nhật hiển thị tên bài hát đầu tiên nếu có
        if (musicSongs.length > 0) {
          console.log('Danh sách âm thanh đã được tải thành công.');
        }
        console.log('Loaded music list:', musicSongs);
      } else {
        console.warn('Không thể tải danh sách âm thanh');
      }
    }).fail(function() {
      console.warn('Lỗi khi gọi API danh sách âm thanh');
    });

    // =========================================================
    var es = null;
    $(document).ready(function() {
      // Chờ người dùng click nút "Bắt đầu lắng nghe" trước khi khởi động EventSource
      $('#startBtn').click(function() {
        $(this).hide(); // Ẩn nút
        $('#playerUI').show(); // Hiện giao diện player
        startListening(); // Khởi động EventSource
      });
    });
    
    function startListening() {
      es = new EventSource("{{route('action')}}");
        es.onmessage = function(e) {
          let fullData = JSON.parse(e.data);
          console.log('SSE received', fullData);
          if (fullData.id && fullData.code != 0) {
            // Update UI
            if (fullData.noidung) {
              $('#baiHat').text(fullData.noidung);
            }
            if (typeof fullData.amluong !== 'undefined') {
              $('#mucAmLuong').text(fullData.amluong);
            }
            $('#trangThai').text(fullData.stop ? 'Dừng' : 'Đang phát');
            // Xử lý dữ liệu âm thanh đã xử lý về server
            $.ajax({
                type:'POST',
                url: "{{ route('action.sethandled') }}",
                data: {
                  "_token": "{{ csrf_token() }}",
                  "id": fullData.id
                },
                success: (response) => {
                    if (response.code == 200) {
                      console.log("Đã đánh dấu sự kiện là đã xử lý:", fullData.id);
                    } else {
                      console.log("Lỗi: không thể đánh dấu sự kiện đã xử lý");
                    }
                },
                error: function(response){
                    console.log("Lỗi khi đánh dấu sự kiện đã xử lý");
                }
            });
            // -----------------
            // Xử lý phát âm thanh
            if (fullData.stop == 0 && fullData.noidung) {
              let songUrl = "{{ asset('upload/amthanh') }}" + "/" + fullData.noidung;
              audio.volume = Math.min(Math.max(fullData.amluong / 100, 0), 1); // Chuyển đổi mức âm lượng từ 0-100 sang 0.0-1.0
              audio.src = songUrl;
              audio.play().catch(function(error) {
                console.log('Không thể phát nhạc:', error);
              });
            } else if (fullData.stop) {
              console.log('Phát âm thanh đã bị dừng bởi server');
              audio.pause();
            } else {
              console.warn('Chưa có tên file âm thanh từ server');
            }
          } else {
            console.warn('Chưa có dữ liệu mới từ server');
          }     
        }
      }
  </script>
</body>
</html>
