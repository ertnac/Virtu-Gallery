<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>VirtuGallery Admin Dashboard - Follower Insights</title>
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
        <a href="followers.php" class="sidebar-link flex items-center p-3 rounded-lg text-gray-600 hover:bg-[#202b1d] hover:text-white active">
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
      <h1 class="text-2xl font-bold text-gray-800 mb-6">Follower Insights</h1>

      <!-- Follower Insights Section -->
      <div class="bg-white p-6 rounded-xl shadow-soft mb-8">
        <div class="bg-white p-10 rounded-xl shadow-soft mb-8">
          <h2 class="text-lg font-semibold text-gray-800 mb-4">Follow Relationships</h2>
        </div>

        <!-- Filters -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
          <input type="date" id="startDateFilter" class="w-full md:w-1/4 p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-[#202b1d]">
          <input type="text" id="followerFilter" placeholder="Search by follower..." class="w-full md:w-1/4 p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-[#202b1d]">
          <input type="text" id="artistFilter" placeholder="Search by followed artist..." class="w-full md:w-1/4 p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-[#202b1d]">
        </div>

        <!-- Followers Table -->
        <div class="overflow-x-auto">
          <table class="w-full text-left">
            <thead class="bg-gray-100">
              <tr class="text-gray-600 text-sm">
                <th class="p-3 font-semibold">Follower</th>
                <th class="p-3">Followed Artist</th>
                <th class="p-3">Follow Date</th>
                <th class="p-3">Status</th>
                <th class="p-3 text-right">Actions</th>
              </tr>
            </thead>
            <tbody id="followersTableBody" class="divide-y divide-gray-200">
              <!-- Followers will be dynamically populated -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- Follower Details Modal -->
      <div id="followerModal" class="modal">
        <div class="modal-content">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800" id="modalTitle">Follower Activity Details</h2>
            <button id="closeModal" class="text-gray-500 hover:text-gray-700">×</button>
          </div>
          <div class="space-y-4">
            <input type="hidden" id="followerId">
            <div>
              <label class="block text-sm font-medium text-gray-700">Follower</label>
              <p id="followerName" class="mt-1 text-sm text-gray-600"></p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Followed Artist</label>
              <p id="followedArtist" class="mt-1 text-sm text-gray-600"></p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Follow Date</label>
              <p id="followDate" class="mt-1 text-sm text-gray-600"></p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Activity Notes</label>
              <p id="activityNotes" class="mt-1 text-sm text-gray-600"></p>
            </div>
            <div class="flex justify-end gap-2">
              <button id="cancelFollowerBtn" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm">Cancel</button>
              <button id="flagActivityBtn" class="bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">Flag for Review</button>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script>
    let followersData = [];

    // DOM elements
    const followersTableBody = document.getElementById('followersTableBody');
    const startDateFilter = document.getElementById('startDateFilter');
    const followerFilter = document.getElementById('followerFilter');
    const artistFilter = document.getElementById('artistFilter');
    const followerModal = document.getElementById('followerModal');
    const closeModal = document.getElementById('closeModal');
    const cancelFollowerBtn = document.getElementById('cancelFollowerBtn');
    const flagActivityBtn = document.getElementById('flagActivityBtn');
    const modalTitle = document.getElementById('modalTitle');
    const followerIdInput = document.getElementById('followerId');
    const followerName = document.getElementById('followerName');
    const followedArtist = document.getElementById('followedArtist');
    const followDate = document.getElementById('followDate');
    const activityNotes = document.getElementById('activityNotes');

    // Fetch followers from server
    function fetchFollowersFromServer() {
      fetch('fetch_followers.php')
        .then(res => {
          console.log('Fetch followers response status:', res.status);
          return res.json();
        })
        .then(data => {
          console.log('Fetched followers:', data);
          if (data.success) {
            followersData = data.followers || [];
            renderFollowers(followersData);
          } else {
            console.error('Fetch failed:', data.error);
            alert('Error fetching followers: ' + data.error);
          }
        })
        .catch(err => {
          console.error('Error fetching followers:', err);
          alert('Error fetching followers');
        });
    }

    // Detect suspicious follow activity (heuristic: multiple follows from same user in short time)
    function detectSuspiciousActivity(follower) {
      const followCount = follower.follow_count || 1;
      if (followCount > 10) { // More than 10 follows is suspicious
        return true;
      }
      return false;
    }

    // Render followers table
    function renderFollowers(followersToRender) {
      console.log('Rendering followers:', followersToRender);
      followersTableBody.innerHTML = '';
      followersToRender.forEach(follower => {
        const isSuspicious = detectSuspiciousActivity(follower);
        const row = document.createElement('tr');
        row.innerHTML = `
          <td class="p-3 text-sm text-gray-600">${follower.follower}</td>
          <td class="p-3 text-sm text-gray-600">${follower.followed_artist}</td>
          <td class="p-3 text-sm text-gray-600">${follower.follow_date}</td>
          <td class="p-3 text-center">
            <span class="px-2 py-1 text-xs font-semibold rounded-full ${
              follower.flagged ? 'bg-red-100 text-red-700' :
              isSuspicious ? 'bg-yellow-100 text-yellow-700' :
              'bg-green-100 text-green-700'
            }">${follower.flagged ? 'Flagged' : isSuspicious ? 'Suspicious' : 'Normal'}</span>
          </td>
          <td class="p-3 text-right">
            <button class="view-btn text-gray-500 hover:text-blue-600" data-id="${follower.id}">View</button>
          </td>
        `;
        followersTableBody.appendChild(row);
      });

      // Add event listeners to view buttons
      document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          console.log('View button clicked for ID:', btn.dataset.id);
          viewFollower(btn.dataset.id);
        });
      });
    }

    // Filter followers
    function filterFollowers() {
      const startDate = startDateFilter.value;
      const followerValue = followerFilter.value.toLowerCase();
      const artistValue = artistFilter.value.toLowerCase();

      const filteredFollowers = followersData.filter(follower => {
        const matchesDate = (!startDate || follower.follow_date >= startDate);
        const matchesFollower = follower.follower.toLowerCase().includes(followerValue);
        const matchesArtist = follower.followed_artist.toLowerCase().includes(artistValue);
        return matchesDate && matchesFollower && matchesArtist;
      });

      renderFollowers(filteredFollowers);
    }

    // View follower details
    function viewFollower(id) {
      console.log('Opening follower modal for ID:', id);
      const follower = followersData.find(f => f.id == id); // Loose comparison for type coercion
      if (follower) {
        console.log('Found follower:', follower);
        modalTitle.textContent = `Follower Activity for ${follower.follower}`;
        followerIdInput.value = follower.id;
        followerName.textContent = follower.follower;
        followedArtist.textContent = follower.followed_artist;
        followDate.textContent = follower.follow_date;
        activityNotes.textContent = detectSuspiciousActivity(follower)
          ? 'High follow activity detected, possible bot or spam activity.'
          : 'No suspicious activity detected.';
        followerModal.style.display = 'block';
      } else {
        console.error('Follower not found for ID:', id);
        alert('Follower not found');
      }
    }

    // Flag follower activity
    function flagFollower() {
      const followerId = followerIdInput.value;
      console.log('Flagging follower with ID:', followerId);

      fetch('save_followers.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: followerId, flagged: true })
      })
        .then(res => {
          console.log('Flag follower response status:', res.status);
          return res.json();
        })
        .then(data => {
          console.log('Flag follower response data:', data);
          if (data.success) {
            const followerIndex = followersData.findIndex(f => f.id == followerId);
            if (followerIndex !== -1) {
              followersData[followerIndex].flagged = true;
            } else {
              console.warn('Follower ID not found in array:', followerId);
            }
            renderFollowers(followersData);
            followerModal.style.display = 'none';
          } else {
            console.error('Flag failed:', data.error);
            alert('Error flagging follower: ' + data.error);
          }
        })
        .catch(err => {
          console.error('Error flagging follower:', err);
          alert('Error flagging follower');
        });
    }

    // Close modal
    function closeModalFunc() {
      console.log('Closing modal');
      followerModal.style.display = 'none';
    }

    // Event listeners
    startDateFilter.addEventListener('change', filterFollowers);
    followerFilter.addEventListener('input', filterFollowers);
    artistFilter.addEventListener('input', filterFollowers);
    closeModal.addEventListener('click', closeModalFunc);
    cancelFollowerBtn.addEventListener('click', closeModalFunc);
    flagActivityBtn.addEventListener('click', flagFollower);

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
      fetchFollowersFromServer();
      document.querySelector('a[href="followers.php"]').classList.add('active');
    });
  </script>
</body>
</html>