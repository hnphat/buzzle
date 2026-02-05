<!DOCTYPE html>
<html lang="vi-VN">
<head>
    <title>Mảnh ghép kiến thức</title>
    <base href="{{asset('')}}" />
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">      
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            background-color: #99ffff;
        }
    </style>
</head>
<body>
<main>
    <div class="container">
        <h4 id="showEnd" style="display:none;">
            Hyundai An Giang chân thành cảm ơn Quý khách hàng đã tin tưởng và sử dụng dịch vụ của Đại lý<br/>
            <p class="text-center"><img src="./upload/image/cr.png" alt="CSKH" style="max-width: 400px;"></p>
        </h4>     
        <form action="" method="post" id="khaoSatForm">
            <input type="hidden" name="bienSo" value="{{$bsx}}">
            <h5>1. Quý khách vui lòng cho biết quý khách đang sử dụng dòng xe gì</h5>
            <div class="form-group">
                <select name="c1" class="form-control">
                    <option value="Getz">Getz</option>
                    <option value="Kona">Kona</option>
                    <option value="Avante">Avante</option>
                    <option value="i10">Grand i10</option>
                    <option value="i20">Grand i20</option>
                    <option value="i30">Grand i30</option>
                    <option value="Sonata">Sonata</option>
                    <option value="Accent">Accent</option>
                    <option value="Stargazer">Stargazer</option>
                    <option value="Elantra">Elantra</option>
                    <option value="Creta">Creta</option>
                    <option value="Tucson">Tucson</option>
                    <option value="Custin">Custin</option>
                    <option value="Santafe">Santafe</option>
                    <option value="Solati">Solati</option>
                    <option value="Palisade">Palisade</option>
                    <option value="Starex">Starex</option>
                    <option value="H150">H150</option>
                </select>
            </div>
            <h5>2. Quý khách vui lòng cho biết mục đích sử dụng xe của mình?</h5>
            <div class="form-group">
                <select name="c2" class="form-control">
                    <option value="Kinh doanh">Kinh doanh</option>
                    <option value="Sinh hoạt gia đình">Sinh hoạt gia đình</option>
                    <option value="Dịch vụ">Dịch vụ</option>
                    <option value="Khác">Khác</option>
                </select>
            </div>
            <h5>3. Quý khách vui lòng cho biết quý khách hiện tại...?</h5>
            <div class="form-group">
                <select name="c3" class="form-control">
                    <option value="Độc thân">Độc thân</option>
                    <option value="Đã kết hôn">Đã kết hôn</option>
                    <option value="Đã có con">Đã có con</option>
                </select>
            </div>
            <h5>4. Quý khách vui lòng cho biết nghề nghiệp của Quý khách?</h5>
            <div class="form-group">
                <select name="c4" class="form-control">
                    <option value="Buôn bán - Kinh doanh">Buôn bán - Kinh doanh</option>
                    <option value="Nhân viên văn phòng">Nhân viên văn phòng</option>
                    <option value="Tài xế">Tài xế</option>
                    <option value="Lao động tự do">Lao động tự do</option>
                    <option value="Khác">Khác</option>
                </select>
            </div>
            <h5>5. Quý khách vui lòng cho biết Độ tuổi của Quý khách?</h5>
            <div class="form-group">
                <select name="c5" class="form-control">
                    <option value="18-29">18-29</option>
                    <option value="30-39">30-39</option>
                    <option value="40-49">40-49</option>
                    <option value="50-59">50-59</option>
                    <option value=">60">>60</option>
                </select>
            </div>
            <h5>6. Sở thích của quý khách: <strong class="text-danger">(*)</strong></h5>
            <input type="text" name="c6" class="form-control" placeholder="Sở thích của quý khách" required>
            <h5>7. Quý khách có hài lòng về  chương trình tri ân của Đại lý?</h5>
            <div class="form-group">
                <select name="c7" class="form-control">
                    <option value="Có">Có</option>
                    <option value="Không">Không</option>
                </select>
            </div>
            <h5>8. Quý khách có sẵn sàng tham gia các chương trình tiếp theo của Đại lý?</h5>
            <div class="form-group">
                <select name="c8" class="form-control">
                    <option value="Có">Có</option>
                    <option value="Không">Không</option>
                </select>
            </div>
            <h5>9. Quý khách có sẵn sàng giới thiệu bạn bè sở hữu xe Hyundai tham gia các chương trình tiếp theo của Đại lý?</h5>
            <div class="form-group">
                <select name="c9" class="form-control">
                    <option value="Có">Có</option>
                    <option value="Không">Không</option>
                </select>
            </div>
            <h5>10. Quý khách có góp ý gì thêm để chương trình tri ân nâng cấp và ý nghĩa hơn?</h5>
            <input type="text" name="c10" class="form-control" placeholder="Góp ý của quý khách">
            <br/>
            <div class="form-group">
                <input type="submit" id="guiKhaoSat" class="btn btn-primary btn-sm" value="GỬI KHẢO SÁT VÀ NHẬN QUÀ">
            </div>
        </form>
    </div>
</main>
<script src="plugins/jquery/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $(document).one('click','#guiKhaoSat', function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#khaoSatForm").one("submit", submitFormFunction);
            function submitFormFunction(e) {
                e.preventDefault();   
                var formData = new FormData(this);
                $.ajax({
                    type:'POST',
                    url: "{{ route('postkhaosatforghephinh') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: (response) => {
                        if (response.code == 200) {
                            $("#khaoSatForm").hide();
                            setTimeout(() => {
                                $("#showEnd").show();
                            }, 500);
                        }
                    },
                        error: function(response){
                    }
                });
            }
        });
    });
</script>
</body>
</html>
