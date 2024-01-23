<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Quay số</title>
    <base href="{{asset('')}}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <link rel="stylesheet" href="{{asset('css/app.css')}}" /> -->
    <style>
        @import url(https://fonts.googleapis.com/css?family=Numans);
        /* Made with love by Mutiullah Samim*/

        html,body{
        /* background-image: url('http://getwallpapers.com/wallpaper/full/a/5/d/544750.jpg'); */
        background-image: url('./images/bg2024.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        height: 100%;
        font-family: 'Numans', sans-serif;
        }

        .container{
        height: 100%;
        align-content: center;
        }

        .card{
        height: 370px;
        margin-top: auto;
        margin-bottom: auto;
        width: 400px;
        background-color: rgba(0,0,0,0.5) !important;
        }

        .social_icon span{
        font-size: 60px;
        margin-left: 10px;
        color: #FFC312;
        }

        .social_icon span:hover{
        color: white;
        cursor: pointer;
        }

        .card-header h3{
        color: white;
        }

        .social_icon{
        position: absolute;
        right: 20px;
        top: -45px;
        }

        .input-group-prepend span{
        width: 50px;
        background-color: #FFC312;
        color: black;
        border:0 !important;
        }

        input:focus{
        outline: 0 0 0 0  !important;
        box-shadow: 0 0 0 0 !important;

        }

        .remember{
        color: white;
        }

        .remember input
        {
        width: 20px;
        height: 20px;
        margin-left: 15px;
        margin-right: 5px;
        }

        .login_btn{
        color: black;
        background-color: #FFC312;
        width: 100px;
        }

        .login_btn:hover{
        color: black;
        background-color: white;
        }

        .links{
        color: white;
        }

        .links a{
        margin-left: 4px;
        }
    </style>
</head>
<body>
<div class="container">
	<div class="d-flex justify-content-center h-100">
		<div class="card">
			<h1 id="so" class="text-white text-center" style="font-size: 1500%;">00</h1>
            <button id="batDau" class="btn btn-primary">BẮT ĐẦU</button>
		</div>
	</div>
</div>
<script>
    function randomInRange(start,end){
       return Math.floor(Math.random() * (end - start + 1) + start);
    }
    $(document).ready(function() {
        let arr = {!! json_encode($data) !!};
        let len_arr = arr.length;
        $("#batDau").click(function(){
            let i = 0;
            let started = setInterval(() => {
                if (i <= len_arr)
                    $("#so").text(arr[i++]);
                else {
                    i = 0;
                    $("#so").text(arr[0]);
                    i++;
                }
            }, randomInRange(10,69));

            setTimeout(() => {
                clearInterval(started);

                $.ajax({
                    type:'POST',
                    url: "{{ url('management/quayso/ajax/set/')}}",      
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "so": $("#so").text()
                    }, 
                    success: (response) => { 
                        console.log(response);
                    },
                    error: function(response){
                        console.log(response);
                    }
                });

                var removeItem = parseInt($("#so").text());
                arr = jQuery.grep(arr, function(value) {
                    return value != removeItem;
                });
                console.log("Dãy số hiện tại: " + arr);
            }, 5000);
           
        });
    });
</script>
</body>
</html>