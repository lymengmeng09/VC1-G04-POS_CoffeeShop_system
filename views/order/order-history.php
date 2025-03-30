<!-- Order History Content -->
<div class="order-history-content mt-4 p-3 bg-white rounded shadow-sm">
    <h2 class="mb-4">Order History</h2>
    
    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link active" href="#">All Orders</a>
        </li>
    </ul>
    <!-- Date Filter -->
    <div class="date-filter d-flex justify-content-end mb-4 gap-2">
        <div class="date-input">
            <div class="input-group">
                <span class="input-group-text bg-white">
                    <i class="bi bi-calendar"></i>
                </span>
                <input type="text" class="form-control" placeholder="From" value="11-01-2023">
            </div>
        </div>
        
        <div class="date-input">
            <div class="input-group">
                <span class="input-group-text">To</span>
                <span class="input-group-text bg-white">
                    <i class="bi bi-calendar"></i>
                </span>
                <input type="text" class="form-control" placeholder="To" value="01-03-2023">
            </div>
        </div>
    </div>
    
    <!-- Orders Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle border rounded">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Payment</th>
                    <th>Time Remaining</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $index => $order): ?>
                <tr>
                    <td>#<?php echo htmlspecialchars($order['id']); ?></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="<?php echo htmlspecialchars($order['avatar']); ?>" alt="User" class="rounded-circle me-2" width="32" height="32">
                            <span><?php echo htmlspecialchars($order['name']); ?></span>
                        </div>
                    </td>
                    <td><?php echo htmlspecialchars($order['payment']); ?></td>
                    <td><?php echo htmlspecialchars($order['time_remaining']); ?></td>
                    <td><span class="badge bg-primary"> <?php echo htmlspecialchars($order['type']); ?></span></td>
                    <td>
                        <span class="badge <?php echo $order['status'] == 'Completed' ? 'bg-success' : ($order['status'] == 'Canceled' ? 'bg-danger' : 'bg-warning'); ?>">
                            <?php echo htmlspecialchars($order['status']); ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($order['total']); ?></td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light border" type="button" id="dropdownMenuButton<?php echo $index; ?>" data-bs-toggle="dropdown" aria-expanded="false">
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
</div>
