<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
     <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="{{route('login')}}" class="d-block">Administrator</a>
          <a href="./out">Logout</a>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">          
          <li class="nav-item">
            <a href="{{route('guest.panel')}}" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
               Guest
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('khaosat.panel')}}" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
               Khảo sát
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('nhomanh.panel')}}" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
               Buzzle Game
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('quayso.panel')}}" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
               Quay số
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  <!-- Main Sidebar Container -->