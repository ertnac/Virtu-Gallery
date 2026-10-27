<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>VirtuGallery Admin Dashboard - Order Management</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
    .sidebar-link.active {
      background-color: #202b1d;
      color: white;
    }
    .shadow-soft {
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .metric-card {
      transition: transform 0.2s ease-in-out;
    }
    .metric-card:hover {
      transform: translateY(-2px);
    }
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.5);
      z-index: 1000;
    }
    .modal-content {
      background-color: white;
      margin: 5% auto;
      padding: 20px;
      width: 90%;
      max-width: 600px;
      border-radius: 8px;
    }
  </style>
</head>
<body class="bg-gray-100">
  <div class="flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-white p-6 h-screen shadow-soft sticky top-0">
      <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#202b1d] mb-2">VirtuGallery</h1>
        <p class="text-gray-500 text-sm">Admin Dashboard</p>
      </div>
      <nav class="space-y-2 mb-8">
        <a href="index.php" class="sidebar-link flex items-center p-3 rounded-lg text-gray-600 hover:bg-[#202b1d] hover:text-white">
          <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
          </svg>
          Dashboard
        </a>
        <a href="artists.php" class="sidebar-link flex items-center p-3 rounded-lg text-gray-600 hover:bg-[#202b1d] hover:text-white">
          <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
          </svg>
          Artists
        </a>
        <a href="artworks.php" class="sidebar-link flex items-center p-3 rounded-lg text-gray-600 hover:bg-[#202b1d] hover:text-white">
          <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
          </svg>
          Artworks
        </a>
        <a href="orders.php" class="sidebar-link flex items-center p-3 rounded-lg text-gray-600 hover:bg-[#202b1d] hover:text-white active">
          <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" clip-rule="evenodd"></path>
          </svg>
          Orders
        </a>
        <a href="posts.php" class="sidebar-link flex items-center p-3 rounded-lg text-gray-600 hover:bg-[#202b1d] hover:text-white">
          <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M7 4a3 3 0 016 0v4a3 3 0 11-6 0V4zm4 10.267A7.1 7.1 0 0110 18c-3.86 0-7-3.14-7-7h2a5 5 0 005 5c1.11 0 2.14-.362 2.971-1.067L11 14.267z" clip-rule="evenodd"></path>
          </svg>
          Posts
        </a>
        <a href="followers.php" class="sidebar-link flex items-center p-3 rounded-lg text-gray-600 hover:bg-[#202b1d] hover:text-white">
          <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
          </svg>
          Followers
        </a>
        <a href="notifications.php" class="sidebar-link flex items-center p-3 rounded-lg text-gray-600 hover:bg-[#202b1d] hover:text-white">
          <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
          </svg>
          Notifications
        </a>
      </nav>
      <div class="mt-auto">
        <div class="bg-gray-100 p-4 rounded-lg">
          <div class="w-full bg-gray-300 rounded-full h-2">
            <div class="bg-[#202b1d] h-2 rounded-full" style="width: 65%"></div>
          </div>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">
      <!-- Header -->
      <h1 class="text-2xl font-bold text-gray-800 mb-6">Order Management</h1>

      <!-- Metric Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="metric-card bg-white p-6 rounded-xl shadow-soft border-l-4 border-[#202b1d]">
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Total Revenue</p>
            <p class="text-3xl font-bold mt-2" id="totalRevenue">$0</p>
            <p class="text-xs text-gray-500 mt-4"><span class="text-green-500" id="revenueChange">↑ 0%</span> from last month</p>
          </div>
        </div>
        <div class="metric-card bg-white p-6 rounded-xl shadow-soft border-l-4 border-[#4b5e40]">
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Quantity Sold</p>
            <p class="text-3xl font-bold mt-2" id="quantitySold">0</p>
            <p class="text-xs text-gray-500 mt-4"><span class="text-green-500" id="quantityChange">↑ 0%</span> from last month</p>
          </div>
        </div>
      </div>

      <!-- Order Management Section -->
      <div class="bg-white p-6 rounded-xl shadow-soft mb-8">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-lg font-semibold text-gray-800">Customer Orders</h2>
        </div>

        <!-- Filters -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
          <input type="date" id="dateFilter" class="w-full md:w-1/4 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#202b1d]">
          <input type="text" id="artworkFilter" placeholder="Search by artwork..." class="w-full md:w-1/4 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#202b1d]">
          <input type="text" id="artistFilter" placeholder="Search by artist..." class="w-full md:w-1/4 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#202b1d]">
          <select id="statusFilter" class="w-full md:w-1/4 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#202b1d]">
            <option value="all">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="shipped">Shipped</option>
            <option value="delivered">Delivered</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>

        <!-- Orders Table -->
        <div class="overflow-x-auto">
          <table class="w-full text-left">
            <thead class="bg-gray-100">
              <tr class="text-gray-600 text-sm">
                <th class="p-3 font-semibold">Order ID</th>
                <th class="p-3">Customer</th>
                <th class="p-3">Artwork</th>
                <th class="p-3">Artist</th>
                <th class="p-3">Date</th>
                <th class="p-3">Total</th>
                <th class="p-3">Status</th>
                <th class="p-3 text-right">Actions</th>
              </tr>
            </thead>
            <tbody id="ordersTableBody" class="divide-y divide-gray-200">
              <!-- Orders will be dynamically populated -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- Order Details Modal -->
      <div id="orderModal" class="modal">
        <div class="modal-content">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800" id="modalTitle">Order Details</h2>
            <button id="closeModal" class="text-gray-500 hover:text-gray-700">×</button>
          </div>
          <div class="space-y-4">
            <input type="hidden" id="orderId">
            <div>
              <label class="block text-sm font-medium text-gray-700">Customer Name</label>
              <p id="orderCustomer" class="mt-1 text-sm text-gray-600"></p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Artwork</label>
              <p id="orderArtwork" class="mt-1 text-sm text-gray-600"></p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Artist</label>
              <p id="orderArtist" class="mt-1 text-sm text-gray-600"></p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Order Date</label>
              <p id="orderDate" class="mt-1 text-sm text-gray-600"></p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Total</label>
              <p id="orderTotal" class="mt-1 text-sm text-gray-600"></p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Payment Method</label>
              <p id="orderPaymentMethod" class="mt-1 text-sm text-gray-600"></p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Shipping Address</label>
              <p id="orderAddress" class="mt-1 text-sm text-gray-600"></p>
            </div>
            <div>
              <label for="orderStatus" class="block text-sm font-medium text-gray-700">Status</label>
              <select id="orderStatus" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#202b1d]">
                <option value="pending">Pending</option>
                <option value="shipped">Shipped</option>
                <option value="delivered">Delivered</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </div>
            <div class="flex justify-end gap-2">
              <button id="cancelOrderBtn" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm">Cancel</button>
              <button id="saveOrderBtn" class="bg-[#202b1d] text-white px-4 py-2 rounded-lg text-sm">Save</button>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script>
    let orders = [];

    // DOM elements
    const ordersTableBody = document.getElementById('ordersTableBody');
    const dateFilter = document.getElementById('dateFilter');
    const artworkFilter = document.getElementById('artworkFilter');
    const artistFilter = document.getElementById('artistFilter');
    const statusFilter = document.getElementById('statusFilter');
    const orderModal = document.getElementById('orderModal');
    const closeModal = document.getElementById('closeModal');
    const cancelOrderBtn = document.getElementById('cancelOrderBtn');
    const saveOrderBtn = document.getElementById('saveOrderBtn');
    const modalTitle = document.getElementById('modalTitle');
    const orderIdInput = document.getElementById('orderId');
    const orderCustomer = document.getElementById('orderCustomer');
    const orderArtwork = document.getElementById('orderArtwork');
    const orderArtist = document.getElementById('orderArtist');
    const orderDate = document.getElementById('orderDate');
    const orderTotal = document.getElementById('orderTotal');
    const orderPaymentMethod = document.getElementById('orderPaymentMethod');
    const orderAddress = document.getElementById('orderAddress');
    const orderStatusInput = document.getElementById('orderStatus');
    const totalRevenue = document.getElementById('totalRevenue');
    const revenueChange = document.getElementById('revenueChange');
    const quantitySold = document.getElementById('quantitySold');
    const quantityChange = document.getElementById('quantityChange');

    // Fetch orders from server
    function fetchOrdersFromServer() {
      fetch('fetch_orders.php')
        .then(res => {
          console.log('Fetch orders response status:', res.status);
          return res.json();
        })
        .then(data => {
          console.log('Fetched orders:', data);
          orders = data.orders || [];
          updateMetrics(data.metrics || { totalRevenue: 0, revenueChange: 0, quantitySold: 0, quantityChange: 0 });
          renderOrders(orders);
        })
        .catch(err => console.error('Error fetching orders:', err));
    }

    // Update metric cards
    function updateMetrics(metrics) {
      totalRevenue.textContent = `$${metrics.totalRevenue.toFixed(2)}`;
      revenueChange.textContent = `${metrics.revenueChange >= 0 ? '↑' : '↓'} ${Math.abs(metrics.revenueChange)}%`;
      quantitySold.textContent = metrics.quantitySold;
      quantityChange.textContent = `${metrics.quantityChange >= 0 ? '↑' : '↓'} ${Math.abs(metrics.quantityChange)}%`;
    }

    // Render orders table
    function renderOrders(ordersToDisplay) {
      console.log('Rendering orders:', ordersToDisplay);
      ordersTableBody.innerHTML = '';
      ordersToDisplay.forEach(order => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td class="p-3 text-sm text-gray-600">${order.id}</td>
          <td class="p-3 text-sm text-gray-600">${order.customer}</td>
          <td class="p-3 text-sm text-gray-600">${order.artwork}</td>
          <td class="p-3 text-sm text-gray-600">${order.artist}</td>
          <td class="p-3 text-sm text-gray-600">${order.date}</td>
          <td class="p-3 text-sm text-gray-600">$${order.total.toFixed(2)}</td>
          <td class="p-3 text-center">
            <span class="px-2 py-1 text-xs font-semibold rounded-full ${
              order.status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
              order.status === 'shipped' ? 'bg-blue-100 text-blue-700' :
              order.status === 'delivered' ? 'bg-green-100 text-green-700' :
              'bg-red-100 text-red-700'
            }">${order.status.charAt(0).toUpperCase() + order.status.slice(1)}</span>
          </td>
          <td class="p-3 text-right">
            <button class="view-btn text-gray-500 hover:text-blue-600 mr-2" data-id="${order.id}">View</button>
          </td>
        `;
        ordersTableBody.appendChild(row);
      });

      // Add event listeners to view buttons
      document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          console.log('View button clicked for ID:', btn.dataset.id);
          viewOrder(btn.dataset.id);
        });
      });
    }

    // Filter orders
    function filterOrders() {
      const dateValue = dateFilter.value;
      const artworkValue = artworkFilter.value.toLowerCase();
      const artistValue = artistFilter.value.toLowerCase();
      const statusValue = statusFilter.value;

      const filteredOrders = orders.filter(order => {
        const matchesDate = dateValue ? order.date === dateValue : true;
        const matchesArtwork = order.artwork.toLowerCase().includes(artworkValue);
        const matchesArtist = order.artist.toLowerCase().includes(artistValue);
        const matchesStatus = statusValue === 'all' || order.status === statusValue;
        return matchesDate && matchesArtwork && matchesArtist && matchesStatus;
      });

      renderOrders(filteredOrders);
    }

    // View order details
    function viewOrder(id) {
      console.log('Opening order modal for ID:', id);
      const order = orders.find(o => o.id == id); // Loose comparison for type coercion
      if (order) {
        console.log('Found order:', order);
        modalTitle.textContent = `Order #${order.id} Details`;
        orderIdInput.value = order.id;
        orderCustomer.textContent = order.customer;
        orderArtwork.textContent = order.artwork;
        orderArtist.textContent = order.artist;
        orderDate.textContent = order.date;
        orderTotal.textContent = `$${order.total.toFixed(2)}`;
        orderPaymentMethod.textContent = order.payment_method;
        orderAddress.textContent = order.address;
        orderStatusInput.value = order.status;
        orderModal.style.display = 'block';
      } else {
        console.error('Order not found for ID:', id);
        alert('Order not found');
      }
    }

    // Save order updates
    function saveOrder() {
      const orderData = {
        id: orderIdInput.value,
        status: orderStatusInput.value
      };
      console.log('Saving order:', orderData);

      if (!orderData.id || !orderData.status) {
        console.warn('Validation failed: Missing required fields');
        alert('Please ensure all required fields are filled');
        return;
      }

      fetch('save_order.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(orderData)
      })
        .then(res => {
          console.log('Save order response status:', res.status);
          return res.json();
        })
        .then(data => {
          console.log('Save order response data:', data);
          if (data.success) {
            const orderIndex = orders.findIndex(o => o.id == orderData.id);
            if (orderIndex !== -1) {
              orders[orderIndex].status = orderData.status;
            } else {
              console.warn('Order ID not found in array:', orderData.id);
            }
            fetchOrdersFromServer(); // Refresh data to update metrics
            orderModal.style.display = 'none';
          } else {
            console.error('Save failed:', data.message);
            alert('Error saving order: ' + data.message);
          }
        })
        .catch(err => {
          console.error('Error saving order:', err);
          alert('Error saving order');
        });
    }

    // Close modal
    function closeModalFunc() {
      console.log('Closing modal');
      orderModal.style.display = 'none';
    }

    // Event listeners
    dateFilter.addEventListener('change', filterOrders);
    artworkFilter.addEventListener('input', filterOrders);
    artistFilter.addEventListener('input', filterOrders);
    statusFilter.addEventListener('change', filterOrders);
    closeModal.addEventListener('click', closeModalFunc);
    cancelOrderBtn.addEventListener('click', closeModalFunc);
    saveOrderBtn.addEventListener('click', saveOrder);

    // Sidebar navigation active state
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    sidebarLinks.forEach(link => {
      link.addEventListener('click', () => {
        sidebarLinks.forEach(l => l.classList.remove('active'));
        link.classList.add('active');
      });
    });

    // Initial render
    document.addEventListener('DOMContentLoaded', () => {
      fetchOrdersFromServer();
      document.querySelector('a[href="orders.php"]').classList.add('active');
    });
  </script>
</body>
</html>