<div id="app">
    <div id="sidebar" class="active">
        <div class="sidebar-wrapper active">
            <div class="sidebar-header">
                <div class="d-flex justify-content-between">
                    <div class="logo">
                        <a href="index.html"><img src="/views/assets/images/logo.png" alt="Logo" style="width: 150px; height: auto; margin-left: 45px;" srcset=""></a>
                    </div>
                    <div class="toggler">
                        <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                    </div>
                </div>
            </div>
            <div class="sidebar-menu">
                <ul class="menu">
                    <li class="sidebar-title"></li>

                    <li class="sidebar-item active ">
                        <a href="/" class='sidebar-link'>
                            <i class="bi bi-grid-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="/products" class='sidebar-link '>
                            <i class="bi bi-shop"></i>
                            <span>Products</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-collection-fill"></i>
                            <span>Order</span>
                        </a> 
                    </li>
                    <li class="sidebar-item">
                        <a href="/viewStock" class='sidebar-link'>
                            <i class="bi bi-cart-fill"></i>
                            <span>Stock</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-file-earmark-medical-fill"></i>
                            <span>Report</span>
                        </a>
                    </li>
                    <li class="sidebar-item  ">
                        <a href="/list-users" class='sidebar-link'>
                            <i class="bi bi-grid-1x2-fill"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="/login/logout" class='sidebar-link'>
                            <i class="bi bi-box-arrow-in-left"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
            <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
        </div>
    </div>
    <div id="main">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light bg-white">
            <div class="container-fluid">
                <!-- Burger button for mobile sidebar toggle -->
                <a href="#" class="burger-btn d-block d-xl-none ">
                    <i class="bi bi-justify fs-3"></i>
                </a>
                <!-- Right side of the navbar -->
                <div class="ms-auto d-flex align-items-center">
                    <!-- Notification Bell -->
                    <a href="#" class="nav-link m-3">
                        <i class="bi bi-bell-fill fs-4 text-muted"></i>
                    </a>
                    <!-- User Avatar -->
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center img" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="/views/assets/images/faces/2.jpg" alt="User Avatar" class="rounded-circle" style="width: 50px; height: 50px;">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/login/logout">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

