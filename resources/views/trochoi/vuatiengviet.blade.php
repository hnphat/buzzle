<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Vua Tiếng Việt Hyundai An Giang</title>
  <base href="{{asset('')}}" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body {
      background-image: url("{{asset('images/assets/newnen.jpg')}}");
      background-size: cover;      /* phủ kín */
      background-position: center; /* căn giữa */
      background-repeat: no-repeat;
    }
    
    .title-highlight {
      font-size: 2.5rem;
      font-weight: 900;
      color: #4400ff;
      text-shadow: 
        2px 2px 4px rgba(0, 0, 0, 0.5),
        0 0 10px rgba(255, 215, 0, 0.8),
        0 0 20px rgba(255, 215, 0, 0.6);
      letter-spacing: 2px;
      animation: glow 2s ease-in-out infinite;
    }
    
    @keyframes glow {
      0%, 100% {
        text-shadow: 
          2px 2px 4px rgba(0, 0, 0, 0.5),
          0 0 10px rgba(255, 215, 0, 0.8),
          0 0 20px rgba(255, 215, 0, 0.6);
      }
      50% {
        text-shadow: 
          2px 2px 4px rgba(0, 0, 0, 0.5),
          0 0 15px rgba(255, 215, 0, 1),
          0 0 30px rgba(255, 215, 0, 0.8);
      }
    }
    
    #cauHoi {
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
    
    #ketQua {
      font-size: 4rem;
      font-weight: 900;
      color: #00dd00;
      text-shadow: 
        3px 3px 8px rgba(0, 0, 0, 0.6),
        0 0 15px rgba(0, 255, 100, 0.9),
        0 0 25px rgba(0, 255, 100, 0.7),
        0 0 35px rgba(0, 255, 200, 0.5);
      letter-spacing: 10px;
      animation: vibrant-pulse 1.2s ease-in-out infinite;
      transform: scale(1);
    }
    
    @keyframes vibrant-pulse {
      0% {
        text-shadow: 
          3px 3px 8px rgba(0, 0, 0, 0.6),
          0 0 15px rgba(0, 255, 100, 0.9),
          0 0 25px rgba(0, 255, 100, 0.7),
          0 0 35px rgba(0, 255, 200, 0.5);
        transform: scale(1) rotate(0deg);
      }
      25% {
        text-shadow: 
          3px 3px 8px rgba(0, 0, 0, 0.6),
          0 0 25px rgba(0, 255, 150, 1),
          0 0 40px rgba(0, 255, 150, 0.9),
          0 0 50px rgba(0, 255, 200, 0.7);
        transform: scale(1.08) rotate(1deg);
      }
      50% {
        text-shadow: 
          3px 3px 8px rgba(0, 0, 0, 0.6),
          0 0 30px rgba(255, 255, 0, 1),
          0 0 45px rgba(255, 255, 0, 0.9),
          0 0 60px rgba(0, 255, 200, 0.8);
        transform: scale(1.12) rotate(-1deg);
      }
      75% {
        text-shadow: 
          3px 3px 8px rgba(0, 0, 0, 0.6),
          0 0 25px rgba(0, 255, 150, 1),
          0 0 40px rgba(0, 255, 150, 0.9),
          0 0 50px rgba(0, 255, 200, 0.7);
        transform: scale(1.08) rotate(1deg);
      }
      100% {
        text-shadow: 
          3px 3px 8px rgba(0, 0, 0, 0.6),
          0 0 15px rgba(0, 255, 100, 0.9),
          0 0 25px rgba(0, 255, 100, 0.7),
          0 0 35px rgba(0, 255, 200, 0.5);
        transform: scale(1) rotate(0deg);
      }
    }
  </style>
</head>
<body class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-3">
      <p class="text-center mt-5">
        <h1 class="title-highlight">HYUNDAI<br/> AN GIANG</h1>
        <img src="{{asset('images/assets/vuatiengviet.png')}}" alt="Vua Tiếng Việt" style="width: 100%; max-width: 200px;" />
      </p>
    </div>
    <div class="col-md-9">
        <div class="row text-center mt-5">
          <h1 id="cauHoi">x/n/â/m/ù/u/a</h1>
        </div>
        <div class="row">
          <button id="btnTruoc" class="btn btn-primary mt-3">CÂU TRƯỚC</button>
          &nbsp;&nbsp;&nbsp;
          <button id="btnSau" class="btn btn-primary mt-3">CÂU SAU</button>
          &nbsp;&nbsp;&nbsp;
          <button id="btnDapAn" class="btn btn-primary mt-3">ĐÁP ÁN</button>
        </div>
        <div class="row text-center mt-5">
          <h1 id="ketQua">mùa xuân</h1>
        </div>
    </div>
  </div>
</body>
</html>
