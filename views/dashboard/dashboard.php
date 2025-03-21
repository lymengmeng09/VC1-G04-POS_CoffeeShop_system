<?php
$user = $_SESSION['user'];
?>

<!-- Include Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Include Chart.js Data Labels Plugin for displaying percentages -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>



<div class="page-heading">
    <h3>Target Coffee Dashboard</h3>
</div>
<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-9">
            <div class="row">
                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon purple">
                                        <i class="iconly-boldShow"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">Total Revenues</h6>
                                    <h6 class="font-extrabold mb-0">112,000L.E</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon blue">
                                        <i class="iconly-boldProfile"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">Total Order</h6>
                                    <h6 class="font-extrabold mb-0">183,000Cups</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon green">
                                        <i class="iconly-boldAdd-User"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">Total Customers</h6>
                                    <h6 class="font-extrabold mb-0">8000Cust.</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Sales Report</h4>
                        </div>
                        <div class="card-body">
                            <div>
                                <canvas id="chart-profile-visit"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Top Selling Products</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="top-products-chart" height="300" width="400"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="card">
                    <div class="card-header">
                            <h4>Top Selling Products</h4>
                        </div>
                        <h5 class="mb-0 ms-3">Espresso</h5>
                    <div id="chart-america"></div>
                    <h5 class="mb-0 ms-3">Americano</h5>
                    <div id="chart-europe"></div>
                    <h5 class="mb-0 ms-3">Cappuccino</h5>
                    <div id="chart-indonesia"></div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3">
            <div class="card">
                <div class="card-body py-4 px-5">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-xl">
                            <img src="/views/assets/images/faces/1.jpg" alt="Face 1" onerror="this.src='https://via.placeholder.com/150';">
                        </div>
                        <div class="ms-3 name">
                            <h4 class="font-bold"><?php echo htmlspecialchars($user['name']); ?></h4>
                            <h6 class="text-muted mb-0">@targetcoffee</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Recent Team Members</h4>
                </div>
                <div class="card-content pb-4">
                    <div class="recent-message d-flex px-4 py-3">
                        <div class="avatar avatar-lg">
                            <img src="/views/assets/images/faces/2.jpg" alt="Face 2" onerror="this.src='https://via.placeholder.com/150';">
                        </div>
                        <div class="name ms-4">
                            <h5 class="mb-1">Lymeng Phorng</h5>
                            <h6 class="text-muted mb-0">@mengmeng</h6>
                        </div>
                    </div>
                    <div class="recent-message d-flex px-4 py-3">
                        <div class="avatar avatar-lg">
                            <img src="/views/assets/images/faces/4.jpg" alt="Face 4" onerror="this.src='https://via.placeholder.com/150';">
                        </div>
                        <div class="name ms-4">
                            <h5 class="mb-1">Ahnoch Phengneang</h5>
                            <h6 class="text-muted mb-0">@nochnoch</h6>
                        </div>
                    </div>
                    <div class="recent-message d-flex px-4 py-3">
                        <div class="avatar avatar-lg">
                            <img src="/views/assets/images/faces/3.jpg" alt="Face 3" onerror="this.src='https://via.placeholder.com/150';">
                        </div>
                        <div class="name ms-4">
                            <h5 class="mb-1">Sreypich Rom</h5>
                            <h6 class="text-muted mb-0">@pichpich</h6>
                        </div>
                    </div>
                    <div class="recent-message d-flex px-4 py-3">
                        <div class="avatar avatar-lg">
                            <img src="/views/assets/images/faces/6.jpg" alt="Face 3" onerror="this.src='https://via.placeholder.com/150';">
                        </div>
                        <div class="name ms-4">
                            <h5 class="mb-1">Sina Nak</h5>
                            <h6 class="text-muted mb-0">@sinasina</h6>
                        </div>
                    </div>
                    <div class="recent-message d-flex px-4 py-3">
                        <div class="avatar avatar-lg">
                            <img src="/views/assets/images/faces/5.jpg" alt="Face 3" onerror="this.src='https://via.placeholder.com/150';">
                        </div>
                        <div class="name ms-4">
                            <h5 class="mb-1">That Ven</h5>
                            <h6 class="text-muted mb-0">@thatthat</h6>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </section>
</div>