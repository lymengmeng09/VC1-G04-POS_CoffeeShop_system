 <!-- Order History Content -->
 <div class="order-history-content mt-4">
                    <h2 class="mb-4">Order History</h2>
                    
                    <!-- Tabs -->
                    <ul class="nav nav-tabs mb-4">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">All Order</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Summary</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Completed</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Cancelled</a>
                        </li>
                    </ul>
                    
                    <!-- Date Filter -->
                    <div class="date-filter d-flex justify-content-end mb-4">
                        <div class="date-from me-2">
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-calendar"></i>
                                </span>
                                <input type="text" class="form-control" value="11-01-2023">
                            </div>
                        </div>
                        
                        <div class="date-to">
                            <div class="input-group">
                                <span class="input-group-text">To</span>
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-calendar"></i>
                                </span>
                                <input type="text" class="form-control" value="01-03-2023">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Orders Table -->
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="d-flex align-items-center">
                                            <span>ID</span>
                                            <i class="bi bi-chevron-down ms-1"></i>
                                        </div>
                                    </th>
                                    <th>Name</th>
                                    <th>Payment</th>
                                    <th>
                                        <div class="d-flex align-items-center">
                                            <span>Time remaining</span>
                                            <i class="bi bi-chevron-down ms-1"></i>
                                        </div>
                                    </th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $index => $order): ?>
                                <tr>
                                    <td><?php echo $order['id']; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?php echo $order['avatar']; ?>" alt="User" class="rounded-circle me-2" width="32" height="32">
                                            <span><?php echo $order['name']; ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo $order['payment']; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-clock me-1"></i>
                                            <span><?php echo $order['time']; ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo $order['type']; ?></td>
                                    <td>
                                        <?php if ($order['status'] == 'Delivered'): ?>
                                            <span class="status-badge delivered">Delivered</span>
                                        <?php elseif ($order['status'] == 'Collected'): ?>
                                            <span class="status-badge collected">Collected</span>
                                        <?php elseif ($order['status'] == 'Cancelled'): ?>
                                            <span class="status-badge cancelled">Cancelled</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $order['total']; ?></td>
                                    <td>
                                        <div class="dropdown action-dropdown">
                                            <button class="btn btn-sm action-btn" type="button" id="dropdownMenuButton<?php echo $index; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton<?php echo $index; ?>">
                                                <li><a class="dropdown-item" href="#"><i class="bi bi-arrow-counterclockwise me-2"></i>Refund</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="bi bi-chat-left me-2"></i>Message</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>