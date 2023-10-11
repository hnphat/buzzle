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
        <h5 id="ques" class="text-primary text-center">
        Quý khách hãy ghép các cặp hình giống nhau để hoàn thành phần chơi?
        </h5>
        <h4 id="showResult" style="display:none;" class="text-success">
            <i>Xin chúc mừng quý khách đã hoàn thành phần chơi, vui lòng thực hiện khảo sát sau đây để nhận quà từ chương trình nhé!</i>
            <p class="text-center"><img src="./upload/image/cr.png" alt="CSKH" style="max-width: 400px;"></p>
            <p class="text-center"><button class="btn btn-primary" id="startSurvey">Bắt đầu khảo sát</button></p>
        </h4>
        <?php $dem = 0; $i = 1; ?>
        @foreach($anh as $row)
            @if($row->isPic)
                @if ($dem == 0)
                    <div class="container">
                @endif
                    <div style="width: 33.3%; float: left; padding: 1px;" id="chon{{$i}}">
                        <img id="anh{{$i}}" src="upload/image/blanks.png" alt="pic{{$i}}" style="width: 100%; height: 150px;">
                    </div>
                <?php $dem++; $i++; ?>
                @if ($dem % 3 == 0 || $dem == count($anh))
                    </div><div class="container">
                @endif
            @endif
        @endforeach
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

        // Used like so
        // let arr = ["upload/image/a1.png", 
        // "upload/image/a2.png", 
        // "upload/image/b1.png", 
        // "upload/image/b2.png", 
        // "upload/image/c1.png",
        // "upload/image/c2.png",
        // "upload/image/d1.png",
        // "upload/image/d2.png"];
        let arr = {!! str_replace("\\", "", json_encode($pics))  !!};
        shuffle(arr);
        let counter = 0;
        let timetohide = 500;
        let chonsen = "";
        let isPic = "";
        let finalcom = {{$dem/2}};
        let temp = "";
        function checkFinal() {
            if (finalcom == 0) {
                $("#ques").hide();
                setTimeout(() => {
                    $("#showResult").show(); 
                }, 1000);
            }
        }

        $("#startSurvey").click(function(){
            open("{{route('khaosat')}}",'_self');
        });

        function showBlank() {
            @for($k = 1; $k <= $i; $k++)
                $("#anh{{$k}}").attr("src","upload/image/blanks.png");
            @endfor
        }
        @for($k = 1; $k <= $i; $k++)
        $("#chon{{$k}}").click(function() {
            if (counter != 2 && temp != arr[{{$k-1}}])  {
                $("#anh{{$k}}").attr("src",arr[{{$k-1}}]);
                temp = arr[{{$k-1}}];
                counter++;
                if (counter == 1) {
                    chosen = arr[{{$k-1}}];
                    isPic = "#anh{{$k}}";
                }
                if (counter == 2 && arr[{{$k-1}}].substr(12).substr(1,1) == chosen.substr(12).substr(1,1)) {
                    setTimeout(() => {
                        $(isPic).css('visibility', 'hidden');
                        $("#anh{{$k}}").css('visibility', 'hidden');
                    }, timetohide);
                    chosen = "";
                    finalcom--;
                    checkFinal();
                }
                if (counter == 2) {
                    setTimeout(() => {
                        showBlank();
                        counter = 0;
                    }, timetohide);
                }               
            } 
        })
        @endfor
    });
</script>
</body>
</html>
