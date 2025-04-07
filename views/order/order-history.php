<!-- Order History Content -->
<div class="order-history-content mt-4 p-3 bg-white rounded shadow-sm">
    <h2 class="mb-4">Order History</h2>
<!-- Date Filter -->
<div class="date-filter d-flex justify-content-end mb-4 gap-2">
    <div class="date-input">
        <div class="input-group">
            <span class="input-group-text bg-white">
                <i class="bi bi-calendar"></i>
            </span>
            <input type="date" class="form-control" id="fromDate">
        </div>
    </div>

    <div class="date-input">
        <div class="input-group">
            <span class="input-group-text">To</span>
            <span class="input-group-text bg-white">
                <i class="bi bi-calendar"></i>
            </span>
            <input type="date" class="form-control" id="toDate">
        </div>
    </div>
</div>


<!-- Orders Table -->
<div class="history-card">
  <div class="table-responsive">
    <table class="history-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Payment</th>
          <th>Date</th>
          <th>Total</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $index => $order): ?>
        <tr>
          <td>#<?php echo htmlspecialchars($order['id']); ?></td>
          <td>
            <div class="user-info">
              <img src="<?php echo htmlspecialchars($order['avatar']); ?>" alt="User">
              <span><?php echo isset($order['product_id']) ? htmlspecialchars($order['product_id']) : 'N/A'; ?></span>
            </div>
          </td>
          <td><?php echo htmlspecialchars($order['payment']); ?></td>
          <td><?php echo isset($order['order_date']) ? htmlspecialchars($order['order_date']) : 'N/A'; ?></td>
          <td>$<?php echo number_format((float) $order['total'], 2); ?></td> <!-- Added $ symbol with two decimal places -->
          <td>
            <div class="history-dropdown">
              <button type="button" id="dropdownMenuButton<?php echo $index; ?>">
                <i class="bi bi-three-dots-vertical"></i>
              </button>
              <div class="history-dropdown-menu" aria-labelledby="dropdownMenuButton<?php echo $index; ?>">
                <a href="#"><i class="bi bi-arrow-counterclockwise me-2"></i>Refund</a>
                <a href="#"><i class="bi bi-chat-left me-2"></i>Message</a>
              </div>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>


<script>
    function setDefaultDates() {
        let today = new Date();
        let lastMonth = new Date();
        lastMonth.setMonth(today.getMonth() - 1); // Set 'From' date to one month before today

        // Format date to YYYY-MM-DD
        let formatDate = (date) => date.toISOString().split('T')[0];

        document.getElementById("fromDate").value = formatDate(lastMonth);
        document.getElementById("toDate").value = formatDate(today);
    }

    // Set default dates on page load
    window.onload = setDefaultDates;
</script>