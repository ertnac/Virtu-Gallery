<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>VirtuGallery Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        <a href="index.php" class="sidebar-link flex items-center p-3 rounded-lg text-gray-600 hover:bg-[#202b1d] hover:text-white active">
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
      <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Overview</h1>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Artists -->
        <div class="metric-card bg-white p-6 rounded-xl shadow-soft border-l-4 border-[#202b1d]">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Total Artists</p>
              <p class="text-3xl font-bold mt-2" id="totalArtists">0</p>
            </div>
            <div class="p-3 rounded-full bg-[#202b1d]/10 text-[#202b1d]">
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
              </svg>
            </div>
          </div>
          <p class="text-xs text-gray-500 mt-4"><span class="text-green-500">↑ 12%</span> from last month</p>
        </div>
        <!-- Artworks -->
        <div class="metric-card bg-white p-6 rounded-xl shadow-soft border-l-4 border-[#4b5e40]">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Artworks</p>
              <p class="text-3xl font-bold mt-2" id="artworks">0</p>
            </div>
            <div class="p-3 rounded-full bg-[#4b5e40]/10 text-[#4b5e40]">
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
              </svg>
            </div>
          </div>
          <p class="text-xs text-gray-500 mt-4"><span class="text-green-500">↑ 8%</span> from last month</p>
        </div>
        <!-- Orders -->
        <div class="metric-card bg-white p-6 rounded-xl shadow-soft border-l-4 border-[#6b7c5e]">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Orders</p>
              <p class="text-3xl font-bold mt-2" id="orders">0</p>
            </div>
            <div class="p-3 rounded-full bg-[#6b7c5e]/10 text-[#6b7c5e]">
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
              </svg>
            </div>
          </div>
          <p class="text-xs text-gray-500 mt-4"><span class="text-green-500">↑ 15%</span> from last month</p>
        </div>
        <!-- Likes -->
        <div class="metric-card bg-white p-6 rounded-xl shadow-soft border-l-4 border-[#202b1d]">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Likes</p>
              <p class="text-3xl font-bold mt-2" id="likes">0</p>
            </div>
            <div class="p-3 rounded-full bg-[#202b1d]/10 text-[#202b1d]">
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"></path>
              </svg>
            </div>
          </div>
          <p class="text-xs text-gray-500 mt-4"><span class="text-green-500">↑ 22%</span> from last month</p>
        </div>
        <!-- Comments -->
        <div class="metric-card bg-white p-6 rounded-xl shadow-soft border-l-4 border-[#4b5e40]">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Comments</p>
              <p class="text-3xl font-bold mt-2" id="comments">0</p>
            </div>
            <div class="p-3 rounded-full bg-[#4b5e40]/10 text-[#4b5e40]">
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
              </svg>
            </div>
          </div>
          <p class="text-xs text-gray-500 mt-4"><span class="text-green-500">↑ 9%</span> from last month</p>
        </div>
        <!-- Followers -->
        <div class="metric-card bg-white p-6 rounded-xl shadow-soft border-l-4 border-[#6b7c5e]">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Followers</p>
              <p class="text-3xl font-bold mt-2" id="followers">0</p>
            </div>
            <div class="p-3 rounded-full bg-[#6b7c5e]/10 text-[#6b7c5e]">
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1h8v-1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-1a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v1h-3zM4.75 12.094A5.973 5.973 0 004 15v1H1v-1a3 3 0 013.75-2.906z"></path>
              </svg>
            </div>
          </div>
          <p class="text-xs text-gray-500 mt-4"><span class="text-green-500">↑ 18%</span> from last month</p>
        </div>
        <!-- Total Users -->
        <div class="metric-card bg-white p-6 rounded-xl shadow-soft border-l-4 border-[#8b9c7d]">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Total Users</p>
              <p class="text-3xl font-bold mt-2" id="users">0</p>
            </div>
            <div class="p-3 rounded-full bg-[#8b9c7d]/10 text-[#8b9c7d]">
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
              </svg>
            </div>
          </div>
          <p class="text-xs text-gray-500 mt-4"><span class="text-green-500">↑ 5%</span> from last month</p>
        </div>

      </div>
    </main>
  </div>

  <script>
    // Fetch and update dashboard metrics
    async function fetchDashboardMetrics() {
      try {
        const response = await fetch('fetch_metrics.php');
        const data = await response.json();
        document.getElementById('totalArtists').textContent = data.totalArtists.toLocaleString();
        document.getElementById('artworks').textContent = data.artworks.toLocaleString();
        document.getElementById('orders').textContent = data.orders.toLocaleString();
        document.getElementById('likes').textContent = data.likes.toLocaleString();
        document.getElementById('comments').textContent = data.comments.toLocaleString();
        document.getElementById('followers').textContent = data.followers.toLocaleString();
        document.getElementById('users').textContent = data.users.toLocaleString();

      } catch (error) {
        console.error('Error fetching dashboard metrics:', error);
      }
    }

    // Initial render
    fetchDashboardMetrics();
  </script>
</body>

</html>