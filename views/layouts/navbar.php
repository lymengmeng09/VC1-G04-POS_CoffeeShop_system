<?php
// Start the session if it hasn't been started already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize $user with default values if not set
$user = $_SESSION['user'] ?? ['profile' => 'views/assets/images/profile.png'];

// Ensure profile path is properly formatted
$profilePath = !empty($user['profile']) ? '/' . ltrim($user['profile'], '/') : '/views/assets/images/profile.png';

// Get current language
$currentLang = LanguageHelper::getCurrentLang();
?>
<nav class="navbar bg-light py-2 shadow-sm bg-white">

    <div class="d-flex justify-content-between align-items-center w-100">
        <!-- Left Side: Logo -->
        <div class="d-flex align-items-center">
            <img src="/views/assets/images/logo.png" alt="Logo" class="navbar-brand">
        </div>
        <div class="d-flex justify-content-end align-items-center">
            <!-- Language Switcher Button -->
                <div class="dropdown me-3">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-globe"></i>
                        <?php echo LanguageHelper::getLanguageName($currentLang); ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <?php foreach (LanguageHelper::getSupportedLangs() as $lang): ?>
                            <li>
                                <a class="dropdown-item <?php echo $lang === $currentLang ? 'active' : ''; ?>" 
                                   href="?lang=<?php echo $lang; ?>">
                                    <?php if ($lang === 'en'): ?>
                                        <i class="bi bi-flag"></i>
                                    <?php else: ?>
                                        <i class="bi bi-flag-fill"></i>
                                    <?php endif; ?>
                                    <?php echo LanguageHelper::getLanguageName($lang); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

            
            <div class="shop me-3" style="position: relative;">
                <a href="javascript:void(0)" id="cart-icon">
                    <i class="fas bi-cart-fill" style="font-size: 24px;"></i>
                    <span class="count_cart" id="cart-count">0</span>
                </a>
            </div>

            <!-- Avatar Dropdown -->
            <div class="profile">
                <div class="dropstart">
                    <button class="btn btn-link p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?= htmlspecialchars($profilePath) ?>" alt="Profile picture"
                            class="rounded-circle border avatar-img"
                            onerror="this.src='/views/assets/images/profile.png'" style="width: 40px; height: 40px;" />
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="/view-profile" class="drop">
                            <i class="bi bi-person ms-3"></i><span> <?php echo __('my_profile'); ?></span></a>
                        </li>
                        <li>
                            <a href="/login/logout" class="drop"><i class="bi bi-box-arrow-in-left ms-3"></i>
                            <span><?php echo __('logout'); ?></span></a>
                        </li>
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
                        <a href="index.html"><img src="/views/assets/images/logo.png" alt="Logo"
                                style="width: 150px; height: auto; margin-left: 45px;" srcset=""></a>
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
                            <span><?php echo __('dashboard'); ?></span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="/products" class='sidebar-link'>
                            <i class="bi bi-cart-fill"></i>
                            <span><?php echo __('order'); ?></span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="/order-history" class="sidebar-link" aria-label="View Order History">
                            <i class="bi bi-cart-fill"></i>
                            <span>History</span>
                        </a>
                        <li class="sidebar-item">
                            <a href="/viewStock" class='sidebar-link'>
                                <i class="bi bi-shop"></i>
                                <span><?php echo __('stock'); ?></span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="/purchase-history" class='sidebar-link'>
                                <i class="bi bi-file-earmark-medical-fill"></i>
                                <span><?php echo __('report'); ?></span>
                            </a>
                        </li>
                    <li class="sidebar-item">
                        <a href="/list-users" class='sidebar-link'>
                            <i class="bi bi-person-circle"></i>
                            <span><?php echo __('users'); ?></span>
                        </a>
                    </li>
                </ul>
            </div>
            <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
        </div>
    </div>
    <div id="main">

