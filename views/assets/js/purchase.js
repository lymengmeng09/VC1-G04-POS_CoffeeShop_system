document.addEventListener("DOMContentLoaded", () => {
    // Elements
    const searchInput = document.getElementById("search-input")
    const purchasesTable = document.getElementById("purchases-table")
    const refreshBtn = document.getElementById("refresh-btn")
    const exportBtn = document.getElementById("export-btn")
  
    // Store original table data for filtering
    const tableRows = Array.from(purchasesTable.querySelectorAll("tbody tr"))
  
    // Search functionality
    searchInput.addEventListener("input", function () {
      const searchTerm = this.value.toLowerCase()
  
      // Get all rows except the empty message row
      const rows = tableRows.filter((row) => !row.querySelector(".empty-message"))
  
      if (rows.length === 0) return
  
      let hasVisibleRows = false
  
      rows.forEach((row) => {
        const text = row.textContent.toLowerCase()
        if (text.includes(searchTerm)) {
          row.style.display = ""
          hasVisibleRows = true
        } else {
          row.style.display = "none"
        }
      })
  
      // Show or hide empty message
      const tbody = purchasesTable.querySelector("tbody")
      let emptyRow = tbody.querySelector(".empty-message-row")
  
      if (!hasVisibleRows) {
        if (!emptyRow) {
          emptyRow = document.createElement("tr")
          emptyRow.className = "empty-message-row"
          const td = document.createElement("td")
          td.colSpan = 6
          td.className = "empty-message"
          td.textContent = "No matching purchases found"
          emptyRow.appendChild(td)
          tbody.appendChild(emptyRow)
        }
      } else if (emptyRow) {
        tbody.removeChild(emptyRow)
      }
    })
  
    // Refresh data
    refreshBtn.addEventListener("click", () => {
      // Show loading state
      const tbody = purchasesTable.querySelector("tbody")
      tbody.innerHTML = `
              <tr>
                  <td colspan="6" class="loading">
                      <div class="spinner"></div>
                  </td>
              </tr>
          `
  
      // Fetch updated data
      fetch("get_purchases_data.php")
        .then((response) => response.json())
        .then((data) => {
          updateTableWithData(data)
        })
        .catch((error) => {
          console.error("Error fetching data:", error)
          tbody.innerHTML = `
                      <tr>
                          <td colspan="6" class="empty-message">
                              Error loading data. Please try again.
                          </td>
                      </tr>
                  `
        })
    })
  
    // Export to CSV
    exportBtn.addEventListener("click", () => {
      // Get visible rows only
      const visibleRows = Array.from(purchasesTable.querySelectorAll("tbody tr")).filter(
        (row) => row.style.display !== "none" && !row.querySelector(".empty-message"),
      )
  
      if (visibleRows.length === 0) {
        alert("No data to export")
        return
      }
  
      // Get headers
      const headers = Array.from(purchasesTable.querySelectorAll("thead th")).map((th) => th.textContent.trim())
  
      // Get data from visible rows
      const data = visibleRows.map((row) => {
        return Array.from(row.querySelectorAll("td")).map((td) => td.textContent.trim())
      })
  
      // Combine headers and data
      const csvContent = [headers.join(","), ...data.map((row) => row.join(","))].join("\n")
  
      // Create download link
      const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" })
      const url = URL.createObjectURL(blob)
      const link = document.createElement("a")
      link.setAttribute("href", url)
      link.setAttribute("download", "purchase_history.csv")
      link.style.display = "none"
      document.body.appendChild(link)
      link.click()
      document.body.removeChild(link)
    })
  
    // Function to update table with new data
    function updateTableWithData(data) {
      const tbody = purchasesTable.querySelector("tbody")
  
      if (data.length === 0) {
        tbody.innerHTML = `
                  <tr>
                      <td colspan="6" class="empty-message">No purchase records found</td>
                  </tr>
              `
        return
      }
  
      // Calculate totals
      let totalRevenue = 0
      data.forEach((item) => {
        totalRevenue += Number.parseFloat(item.total_cost)
      })
  
      const avgPurchase = data.length > 0 ? totalRevenue / data.length : 0
  
      // Update stats
      document.querySelector(".stats-container .stat-card:nth-child(1) .stat-value").textContent =
        "$" + totalRevenue.toFixed(2)
      document.querySelector(".stats-container .stat-card:nth-child(2) .stat-value").textContent = data.length
      document.querySelector(".stats-container .stat-card:nth-child(3) .stat-value").textContent =
        "$" + avgPurchase.toFixed(2)
  
      // Update table
      tbody.innerHTML = data
        .map(
          (item) => `
              <tr>
                  <td>${item.id}</td>
                  <td>${item.product_name}</td>
                  <td class="text-right">${item.quantity}</td>
                  <td class="text-right">$${Number.parseFloat(item.price).toFixed(2)}</td>
                  <td>${new Date(item.purchase_date).toLocaleDateString("en-US", {
                    year: "numeric",
                    month: "short",
                    day: "numeric",
                  })}</td>
                  <td class="text-right">$${Number.parseFloat(item.total_cost).toFixed(2)}</td>
              </tr>
          `,
        )
        .join("")
  
      // Update stored rows for search
      tableRows.length = 0
      tableRows.push(...Array.from(tbody.querySelectorAll("tr")))
    }
  
    // Set up auto-refresh (every 30 seconds)
    setInterval(() => {
      fetch("get_purchases_data.php")
        .then((response) => response.json())
        .then((data) => {
          updateTableWithData(data)
        })
        .catch((error) => {
          console.error("Error auto-refreshing data:", error)
        })
    }, 30000)
  })
  
  