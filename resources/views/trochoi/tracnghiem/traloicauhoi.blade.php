<!DOCTYPE html>
<html lang="vi-VN">
<head>
    <title>Chương trình</title>
    <base href="{{asset('')}}" />
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">      
    <style>
        * {
            box-sizing: border-box;
        }
    </style>
</head>
<body>
<main>
    <div class="container">   
        <h4 id="showEnd" style="display:none;" class="text-primary">
        Hyundai An Giang chân thành cảm ơn Quý khách hàng đã tin tưởng và sử dụng dịch vụ của Đại lý<br/>
        Kính mời Quý khách hàng theo dõi Buổi Livestream trực tiếp trên Fanpage vào ngày 16/11/2024 để có cơ hội nhận phần quà trị giá lên đến 3.000.000 đồng
        <p class="text-center"><img src="./upload/image/cr.png" alt="CSKH" style="max-width: 400px;"></p>
        </h4>     
        <h4 id="showResult" style="display:none;" class="text-success">
            <i>Xin chúc mừng quý khách đã trả lời đúng, vui lòng chọn số may mắn bên dưới!</i>
            <br/>
            <select id="soMayMan" class="form-control">
                <option value="0" selected>Chọn số may mắn</option> 
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
            </select>
            <button id="xacNhan" class="btn btn-info btn-sm">XÁC NHẬN</button>
        </h4>
        <div id="cauHoi">
        <h5 id="ques" class="text-primary text-center">
        Quý khách hãy trả lời các câu hỏi sau đây để hoàn thành phần chơi?
        </h5>
        <p>
        <strong>Câu hỏi 1: Thời gian khuyến cáo Bảo dưỡng định kỳ dành cho xe Hyundai là bao lâu?</strong>	<br/>
        A. Xe chạy cách 5.000km		<br/>
        B. Xe chạy cách 5.000km hoặc 3 tháng tuỳ theo điều kiện nào đến trước 	<br/>	
        C. Xe chạy cách 5.000km hoặc 6 tháng tuỳ theo điều kiện nào đến trước		<br/>
        Câu trả lời của quý khách:
        <select id="chonc1" class="form-control">
            <option value="" selected disabled>Chọn</option> 
            <option value="1">A</option>
            <option value="2">B</option>
            <option value="3">C</option>
        </select>
        </p>

        <p>
        <strong>Câu hỏi 2: Khách hàng sử dụng dịch vụ tại Đại lý có cài đặt Hyundai Me có thể thực hiện tính năng đánh giá dịch vụ (thực hiện rating) trên ứng dụng Hyundai Me đúng hay sai?	</strong>	<br/>
        A. Đúng	<br/>
        B. Sai	<br>
        Câu trả lời của quý khách:
        <select id="chonc2" class="form-control">
            <option value="" selected disabled>Chọn</option> 
            <option value="1">A</option>
            <option value="2">B</option>
        </select>
        </p>     
        <button id="guiTraLoi" class="btn btn-success btn-sm">GỬI CÂU TRẢ LỜI</button>   
        </div>      
    </div>
</main>
<script src="plugins/jquery/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $("#guiTraLoi").click(function(){
            if ($("#chonc1").val() == 3 && $("#chonc2").val() == 1) {
                $("#cauHoi").hide();
                setTimeout(() => {
                    $("#showResult").show();
                }, 500);
            } else {
                alert("Quý khách trả lời chưa chính xác, hãy thử chọn lại đáp án khác!")
            }
        });
        

        $("#xacNhan").click(function() {
            if ($("#soMayMan").val() == 0) {
                alert("Quý khách vui lòng chọn một số may mắn!")
            } else {
                $.ajax({
                    url: "{{route('chonsomayman.post')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "soMayMan": $("#soMayMan").val()
                    },
                    success: function(response) {
                        if (response.code == 200) {
                            $("#showResult").hide();
                            setTimeout(() => {
                                $("#showEnd").show();
                            }, 500);
                        }
                    },
                    error: function() {
                    }
                });                
            }
        });
    });
</script>
</body>
</html>
