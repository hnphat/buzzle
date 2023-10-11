<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập quản trị</title>
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
<style>
        body {
    background: #000
}

.card {
    border: none;
    height: 320px
}

.forms-inputs {
    position: relative
}

.forms-inputs span {
    position: absolute;
    top: -18px;
    left: 10px;
    background-color: #fff;
    padding: 5px 10px;
    font-size: 15px
}

.forms-inputs input {
    height: 50px;
    border: 2px solid #eee
}

.forms-inputs input:focus {
    box-shadow: none;
    outline: none;
    border: 2px solid #000
}

.btn {
    height: 50px
}

.success-data {
    display: flex;
    flex-direction: column
}

.bxs-badge-check {
    font-size: 90px
}
    </style>
</head>
<body>
<div class="container">
<div class="container mt-5">
    <div class="row d-flex justify-content-center">
        <div class="col-md-6">
            <div class="card px-5 py-5" id="form1">
                <form action="{{route('postlogin')}}" method="post">
                    @csrf
                    <div class="form-data">
                        <div class="forms-inputs mb-4"> 
                            <span>Tài khoản</span> 
                            <input autofocus name="account" autocomplete="off" type="text" class="form-control">
                        </div>
                        <div class="forms-inputs mb-4"> 
                            <span>Mật khẩu</span> 
                            <input name="password" autocomplete="off" type="password" class="form-control">
                        </div>
                        @if(isset($error))
                            <h5>{{$error}}</h5>
                        @endif
                        <div class="mb-3"> <button class="btn btn-dark w-100">Login</button> </div>
                    </div> 
                </form>              
            </div>
        </div>
    </div>
</div>
</div>
<!-- ./wrapper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>