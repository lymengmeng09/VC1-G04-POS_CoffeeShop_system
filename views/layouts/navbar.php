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
                     </li>
                     <li class="sidebar-item">
                         <a href="/viewStock" class='sidebar-link'>
                             <i class="bi bi-cart-fill"></i>
                             <span>Stock</span>
                         </a>
                     </li>

                     <li class="sidebar-item  ">
                         <a href="f " class='sidebar-link'>
                             <i class="bi bi-file-earmark-medical-fill"></i>
                             <span>Analytics</span>
                         </a>
                     </li>
                     <li class="sidebar-item  ">
                         <a href="/list-users" class='sidebar-link'>
                             <i class="bi bi-grid-1x2-fill"></i>
                             <span>Users</span>
                         </a>
                     </li>

                     <li class="sidebar-item  ">
                         <?php if (AccessControl::hasPermission('access_settings')): ?>
                             <a href="/setting" class='sidebar-link'>
                                 <i class="bi bi-gear-fill"></i>
                                 <span>Setting</span>
                             </a>
                         <?php endif; ?>

                     </li>

                     <li class="sidebar-item  ">
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
         <header class="mb-3">
             <a href="#" class="burger-btn d-block d-xl-none">
                 <i class="bi bi-justify fs-3"></i>
             </a>
         </header>
         <script>
             document.addEventListener("DOMContentLoaded", function() {
                 const sidebarItems = document.querySelectorAll(".sidebar-item");

                 sidebarItems.forEach((item) => {
                     item.addEventListener("click", function() {
                         // Remove "active" class from all sidebar items
                         sidebarItems.forEach((el) => el.classList.remove("active"));

                         // Add "active" class to the clicked item
                         this.classList.add("active");
                     });
                 });
             });
         </script>