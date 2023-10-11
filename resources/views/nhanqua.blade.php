<!DOCTYPE html>
<html lang="vi-VN">
<head>
    <title>Nhận quà</title>
    <meta charset="UTF-8"/>
    <base href="{{asset('')}}" />
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
        <h5 id="ques" class="text-primary text-center">
            Cảm ơn quý khách đã thực hiện khảo sát, mời quý khách chọn 01 phần quà từ bên dưới!
        </h5>
        <div id="box">
        <?php $dem = 0; $i = 1; ?>
        @foreach($anh as $row)
            @if(!$row->isPic && $row->counter > 0)
                @if ($dem == 0)
                    <div class="container">
                @endif
                    <div style="width: 50%; float: left; padding: 1px;" id="chon{{$i}}">
                            <img id="anh{{$i}}" src="upload/image/gift.png?id=<?php echo rand(1,1000);?>" alt="pic{{$i}}" style="width: 100%; height: 150px;">
                    </div>
                <?php $dem++; $i++; ?>
                @if ($dem % 2 == 0 || $dem == count($anh))
                    </div><div class="container">
                @endif
            @endif
        @endforeach
        </div></div>
        <div id="finalbox" style="display:none;">
            <div class="row container">
                <p class="text-center">
                    <img id="finalgift" src="upload/image/gift.png?id=<?php echo rand(1,1000);?>" alt="final" style="max-width: 400px;"/>                
                </p>
                <p class="text-center text-success">
                    <strong><i>Xin chúc mừng quý khách đã nhận được quà! <br/>CSKH Hyundai An Giang sẽ liên hệ trao quà cho quý khách. Chúc quý khách sức khoẻ và thành đạt. Xin chân thành cảm ơn!</i></strong>
                </p>
            </div>
        </div>
    </div>
</main>
<script src="plugins/jquery/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        function shuffle(array) {
            let currentIndex = array.length,  randomIndex;
            // While there remain elements to shuffle.
            while (currentIndex > 0) {
                // Pick a remaining element.
                randomIndex = Math.floor(Math.random() * currentIndex);
                currentIndex--;
                // And swap it with the current element.
                [array[currentIndex], array[randomIndex]] = [
                array[randomIndex], array[currentIndex]];
            }
            return array;
        }
        function setGift(namegift) {
            $.ajax({
                url: "{{route('sendtocskh')}}",
                type: "post",
                dataType: "json",
                data: {
                    "_token": "{{csrf_token()}}",
                    "name": namegift
                },
                success: function(response) {
                    console.log(response);
                },
                error: function() {
                }
            });
        }
        // Used like so
        let arr = {!! str_replace("\\", "", json_encode($pics))  !!};
        shuffle(arr);
        let chonsen = true;
        let timetoshow = 1000;
        @for($k = 1; $k < $i; $k++)
            $("#chon{{$k}}").click(function() {
                if (chonsen == true) {
                    $("#anh{{$k}}").attr("src",arr[{{$k-1}}]);
                    chonsen = false;
                    setTimeout(() => {                        
                        $("#box").hide();
                        $("#finalbox").show();
                        $("#finalgift").attr("src",arr[{{$k-1}}]);
                        $("#ques").hide();
                        setGift(arr[{{$k-1}}]);
                    }, timetoshow);
                }            
            })
        @endfor
    });
</script>
</body>
</html>
