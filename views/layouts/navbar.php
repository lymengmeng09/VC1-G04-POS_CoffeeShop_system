<?php
// Start the session if it hasn't been started already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize $user with default values if not set
$user = $_SESSION['user'] ?? ['profile' => 'views/assets/images/profile.png'];

// Ensure profile path is properly formatted
$profilePath = !empty($user['profile']) ? '/' . ltrim($user['profile'], '/') : '/views/assets/images/profile.png';
?>
<nav class="navbar bg-light py-2 shadow-sm bg-white">
    <div class="container mx-auto px-4">
        <div class="d-flex justify-content-end align-items-center w-100">
            <div class="col-md shop" style="position: relative;">
                <a href="javascript:void(0)" id="cart-icon">
                    <i class="fas fa-shopping-cart" style="font-size: 24px;"></i>
                    <span class="count_cart" id="cart-count">0</span>
                </a>
            </div>
            <!-- Right Side: Notification and Avatar -->
            <div class="d-flex align-items-center">
                <!-- Notification Bell -->
                <div class="position-relative me-3">
                    <button
                        class="btn btn-link p-0 text-dark"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fas fa-bell" style="font-size: 1.6rem;"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            3
                            <span class="visually-hidden">unread notifications</span>
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">New message from John</a></li>
                        <li><a class="dropdown-item" href="#">Friend request</a></li>
                        <li><a class="dropdown-item" href="#">Post liked</a></li>
                    </ul>
                </div>

                <!-- Avatar Dropdown -->
                <div class="dropdown">
                    <button
                        class="btn btn-link p-0"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <img
                            src="<?= htmlspecialchars($profilePath) ?>"
                            alt="Profile picture"
                            class="rounded-circle border avatar-img"
                            onerror="this.src='/views/assets/images/profile.png'"
                            style="width: 40px; height: 40px;" />
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a href="/view-profile" class="drop"><i class="bi bi-person ms-3"></i> <span>My profile</span></a></li>
                        <li><a href="/login/logout" class="drop"><i class="bi bi-box-arrow-in-left ms-3"></i> <span>Logout</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
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
                    <li class="sidebar-item">
                        <a href="/" class='sidebar-link'>
                            <i class="bi bi-grid-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="/products" class='sidebar-link'>
                            <i class="bi bi-shop"></i>
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
                    <li class="sidebar-item">
                        <a href="/list-users" class='sidebar-link'>
                            <i class="bi bi-grid-1x2-fill"></i>
                            <span>Users</span>
                        </a>
                    </li>
                </ul>
            </div>
            <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
        </div>
    </div>
    <div id="main">
        