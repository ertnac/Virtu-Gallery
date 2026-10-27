<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>VirtuGallery - Notification Management</title>
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
        <a href="orders.php" class="sidebar-link flex items-center p-3 rounded-lg text-gray-600 hover:bg-[#202b1d] hover:text-white">
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
      <!-- Notification Management -->
      <div class="bg-white p-6 rounded-xl shadow-soft">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-lg font-semibold text-gray-800">Notification Management</h2>
          <button id="addNotificationBtn" class="bg-[#202b1d] text-white px-4 py-2 rounded-lg text-sm">Create New Notification</button>
        </div>
        
        <!-- Search and Filter -->
        <div class="flex flex-col md:flex-row gap-4 mb-4">
          <input type="text" id="searchInput" placeholder="Search notifications..." class="w-full md:w-1/3 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]">
          <select id="statusFilter" class="w-full md:w-1/4 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]">
            <option value="all">All Statuses</option>
            <option value="read">Read</option>
            <option value="unread">Unread</option>
          </select>
          <select id="typeFilter" class="w-full md:w-1/4 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]">
            <option value="all">All Types</option>
            <option value="global">Global</option>
            <option value="artist-specific">Artist-Specific</option>
          </select>
          <select id="dateFilter" class="w-full md:w-1/4 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]">
            <option value="all">All Dates</option>
            <option value="7days">Last 7 days</option>
            <option value="30days">Last 30 days</option>
            <option value="90days">Last 90 days</option>
          </select>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left">
            <thead class="bg-gray-50">
              <tr class="text-gray-600 text-sm">
                <th class="p-3 font-medium">Message</th>
                <th class="p-3 font-medium">Recipient</th>
                <th class="p-3 font-medium">Type</th>
                <th class="p-3 font-medium">Status</th>
                <th class="p-3 font-medium">Date</th>
                <th class="p-3 font-medium text-right">Actions</th>
              </tr>
            </thead>
            <tbody id="notificationTableBody" class="divide-y divide-gray-200">
              <!-- Notification rows will be dynamically populated -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- Add/Edit Notification Modal -->
      <div id="notificationModal" class="modal">
        <div class="modal-content">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800" id="modalTitle">Create New Notification</h2>
            <button id="closeModal" class="text-gray-500 hover:text-gray-700">×</button>
          </div>
          <div class="space-y-4">
            <input type="hidden" id="notificationId">
            <div>
              <label class="block text-sm font-medium text-gray-700">Notification Type</label>
              <select id="notificationType" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]">
                <option value="global">Global</option>
                <option value="artist-specific">Artist-Specific</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Recipient</label>
              <input type="text" id="notificationRecipient" placeholder="Enter artist email or select" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Message</label>
              <textarea id="notificationMessage" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]" rows="4"></textarea>
            </div>
            <div class="flex justify-end gap-2">
              <button id="saveNotificationBtn" class="bg-[#202b1d] text-white px-4 py-2 rounded-lg text-sm">Send</button>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script>
    // Sample notification data (in a real application, this would come from a backend)
    let notifications = [
      { id: 1, message: 'New artwork approval needed', recipient: 'sarah.johnson@example.com', type: 'artist-specific', status: 'unread', date: '2025-06-04' },
      { id: 2, message: 'System maintenance scheduled', recipient: 'All Artists', type: 'global', status: 'read', date: '2025-06-03' },
      { id: 3, message: 'Order #4567 update', recipient: 'michael.chen@example.com', type: 'artist-specific', status: 'unread', date: '2025-06-02' }
    ];

    // DOM elements
    const notificationTableBody = document.getElementById('notificationTableBody');
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const typeFilter = document.getElementById('typeFilter');
    const dateFilter = document.getElementById('dateFilter');
    const addNotificationBtn = document.getElementById('addNotificationBtn');
    const notificationModal = document.getElementById('notificationModal');
    const closeModal = document.getElementById('closeModal');
    const saveNotificationBtn = document.getElementById('saveNotificationBtn');
    const modalTitle = document.getElementById('modalTitle');
    const notificationIdInput = document.getElementById('notificationId');
    const notificationTypeInput = document.getElementById('notificationType');
    const notificationRecipientInput = document.getElementById('notificationRecipient');
    const notificationMessageInput = document.getElementById('notificationMessage');

    // Render notifications table
    function renderNotifications(notificationsToDisplay) {
      notificationTableBody.innerHTML = '';
      notificationsToDisplay.forEach(notification => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td class="p-3">
            <div class="flex items-center">
              <div class="w-8 h-8 rounded-full bg-gray-300 mr-3"></div>
              <span>${notification.message}</span>
            </div>
          </td>
          <td class="p-3 text-sm text-gray-600">${notification.recipient}</td>
          <td class="p-3 text-sm text-gray-600">${notification.type.charAt(0).toUpperCase() + notification.type.slice(1)}</td>
          <td class="p-3">
            <span class="px-2 py-1 text-xs rounded-full ${
              notification.status === 'read' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
            }">${notification.status.charAt(0).toUpperCase() + notification.status.slice(1)}</span>
          </td>
          <td class="p-3 text-sm text-gray-600">${notification.date}</td>
          <td class="p-3 text-right">
            <button class="edit-btn text-gray-400 hover:text-gray-600 mr-2" data-id="${notification.id}">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
              </svg>
            </button>
            <button class="delete-btn text-gray-400 hover:text-gray-600" data-id="${notification.id}">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
              </svg>
            </button>
          </td>
        `;
        notificationTableBody.appendChild(row);
      });

      // Add event listeners to edit and delete buttons
      document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => editNotification(btn.dataset.id));
      });
      document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => deleteNotification(btn.dataset.id));
      });
    }

    // Search and filter notifications
    function filterNotifications() {
      const searchTerm = searchInput.value.toLowerCase();
      const status = statusFilter.value;
      const type = typeFilter.value;
      const date = dateFilter.value;

      const filteredNotifications = notifications.filter(notification => {
        const matchesSearch = notification.message.toLowerCase().includes(searchTerm) || 
                             notification.recipient.toLowerCase().includes(searchTerm);
        const matchesStatus = status === 'all' || notification.status === status;
        const matchesType = type === 'all' || notification.type === type;
        const matchesDate = date === 'all' || 
                            (date === '7days' && new Date(notification.date) >= new Date(Date.now() - 7 * 24 * 60 * 60 * 1000)) ||
                            (date === '30days' && new Date(notification.date) >= new Date(Date.now() - 30 * 24 * 60 * 60 * 1000)) ||
                            (date === '90days' && new Date(notification.date) >= new Date(Date.now() - 90 * 24 * 60 * 60 * 1000));
        return matchesSearch && matchesStatus && matchesType && matchesDate;
      });

      renderNotifications(filteredNotifications);
    }

    // Open modal for adding new notification
    addNotificationBtn.addEventListener('click', () => {
      modalTitle.textContent = 'Create New Notification';
      notificationIdInput.value = '';
      notificationTypeInput.value = 'global';
      notificationRecipientInput.value = '';
      notificationMessageInput.value = '';
      notificationModal.style.display = 'block';
    });

    // Close modal
    closeModal.addEventListener('click', () => {
      notificationModal.style.display = 'none';
    });

    // Save notification (add or edit)
    saveNotificationBtn.addEventListener('click', () => {
      const notificationId = notificationIdInput.value;
      const newNotification = {
        id: notificationId ? parseInt(notificationId) : Date.now(),
        message: notificationMessageInput.value,
        recipient: notificationTypeInput.value === 'global' ? 'All Artists' : notificationRecipientInput.value,
        type: notificationTypeInput.value,
        status: 'unread',
        date: new Date().toISOString().split('T')[0]
      };

      if (!newNotification.message || (!newNotification.recipient && newNotification.type === 'artist-specific')) {
        alert('Message and recipient (for artist-specific) are required');
        return;
      }

      if (notificationId) {
        // Update existing notification
        const index = notifications.findIndex(n => n.id === parseInt(notificationId));
        notifications[index] = newNotification;
      } else {
        // Add new notification
        notifications.push(newNotification);
      }

      renderNotifications(notifications);
      notificationModal.style.display = 'none';
    });

    // Edit notification
    function editNotification(id) {
      const notification = notifications.find(n => n.id === parseInt(id));
      if (notification) {
        modalTitle.textContent = 'Edit Notification';
        notificationIdInput.value = notification.id;
        notificationTypeInput.value = notification.type;
        notificationRecipientInput.value = notification.recipient === 'All Artists' ? '' : notification.recipient;
        notificationMessageInput.value = notification.message;
        notificationModal.style.display = 'block';
      }
    }

    // Delete notification
    function deleteNotification(id) {
      if (confirm('Are you sure you want to delete this notification?')) {
        notifications = notifications.filter(n => n.id !== parseInt(id));
        renderNotifications(notifications);
      }
    }

    // Event listeners for search and filter
    searchInput.addEventListener('input', filterNotifications);
    statusFilter.addEventListener('change', filterNotifications);
    typeFilter.addEventListener('change', filterNotifications);
    dateFilter.addEventListener('change', filterNotifications);

    // Initial render
    renderNotifications(notifications);

    // Sidebar link activation
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    sidebarLinks.forEach(link => {
      link.addEventListener('click', (e) => {
        sidebarLinks.forEach(l => l.classList.remove('active'));
        link.classList.add('active');
      });
    });
  </script>
</body>
</html>