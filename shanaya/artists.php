<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>VirtuGallery - Artist Management</title>
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
      max-height: 80vh;
      overflow-y: auto;
    }
    .delete-modal-content {
      background-color: white;
      margin: 15% auto;
      padding: 20px;
      width: 90%;
      max-width: 400px;
      border-radius: 8px;
    }
    .table-container {
      max-height: 500px;
      overflow-y: auto;
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
        <a href="artists.php" class="sidebar-link flex items-center p-3 rounded-lg text-gray-600 hover:bg-[#202b1d] hover:text-white active">
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
            <path fill-rule="evenodd" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L1-.345 5.312c1-.63-.184-1.707.707-1.707H17m0-2a2 2 0 1 0 0 4 2 2 0 0 0-4-4zm-8 2a2 2 0 1 1-4-4 2 2 0 0 1 4 4z"></path>
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
      <!-- Artist Management -->
      <div class="bg-white p-6 rounded-xl shadow-soft">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-lg font-semibold text-gray-800">Artist Management</h2>
        </div>
        
        <!-- Search and Filter -->
        <div class="flex flex-col md:flex-row gap-4 mb-4">
          <input type="text" id="searchInput" placeholder="Search artists..." class="w-full md:w-1/3 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]">
          <select id="statusFilter" class="w-full md:w-1/4 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]">
            <option value="all">All Verification Statuses</option>
            <option value="verified">Verified</option>
            <option value="unverified">Unverified</option>
          </select>
        </div>

        <div class="table-container overflow-x-auto">
          <table class="w-full text-left">
            <thead class="bg-gray-50">
              <tr class="text-gray-600 text-sm">
                <th class="p-3 font-medium">Name</th>
                <th class="p-3 font-medium">Email</th>
                <th class="p-3 font-medium">Username</th>
                <th class="p-3 font-medium">Address</th>
                <th class="p-3 font-medium">Artworks</th>
                <th class="p-3 font-medium">Followers</th>
                <th class="p-3 font-medium">Following</th>
                <th class="p-3 font-medium">Verification</th>
                <th class="p-3 font-medium">Joined</th>
                <th class="p-3 font-medium text-right">Actions</th>
              </tr>
            </thead>
            <tbody id="artistTableBody" class="divide-y divide-gray-200">
              <!-- Artist rows will be dynamically populated -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- Edit Artist Modal -->
      <div id="artistModal" class="modal">
        <div class="modal-content">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800" id="modalTitle">Edit Artist</h2>
            <button id="closeModal" class="text-gray-500 hover:text-gray-700">×</button>
          </div>
          <div class="space-y-4">
            <input type="hidden" id="artistId">
            <div>
              <label class="block text-sm font-medium text-gray-700">First Name</label>
              <input type="text" id="artistFirstName" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]" placeholder="First Name">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Middle Name</label>
              <input type="text" id="artistMiddleName" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]" placeholder="Middle Name (optional)">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Last Name</label>
              <input type="text" id="artistLastName" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]" placeholder="Last Name">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Suffix</label>
              <input type="text" id="artistSuffix" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]" placeholder="Suffix (optional)">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Email</label>
              <input type="email" id="artistEmail" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]" placeholder="email@example.com">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Username</label>
              <input type="text" id="artistUsername" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]" placeholder="Username (optional)">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Municipality</label>
              <input type="text" id="artistMunicipality" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]" placeholder="Municipality">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Province</label>
              <input type="text" id="artistProvince" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]" placeholder="Province">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Bio</label>
              <textarea id="artistBio" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]" placeholder="Artist bio (optional)"></textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Website</label>
              <input type="url" id="artistWebsite" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]" placeholder="https://example.com (optional)">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Profile Picture URL</label>
              <input type="url" id="artistProfilePicture" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]" placeholder="https://example.com/image.jpg (optional)">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Verification Status</label>
              <select id="artistStatus" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]">
                <option value="1">Verified</option>
                <option value="0">Unverified</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Stage Name</label>
              <input type="text" id="artistStageName" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]" placeholder="Stage Name (optional)">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Art Style</label>
              <input type="text" id="artistArtStyle" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]" placeholder="Art Style (optional)">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Portfolio Link</label>
              <input type="url" id="artistPortfolioLink" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]" placeholder="https://example.com/portfolio (optional)">
            </div>
            <div class="flex justify-end gap-2">
              <button id="resetPasswordBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">Reset Password</button>
              <button id="saveArtistBtn" class="bg-[#202b1d] text-white px-4 py-2 rounded-lg text-sm">Save</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Delete Confirmation Modal -->
      <div id="deleteModal" class="modal">
        <div class="delete-modal-content">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Confirm Delete</h2>
            <button id="closeDeleteModal" class="text-gray-500 hover:text-gray-700">×</button>
          </div>
          <p class="text-sm text-gray-600 mb-4">Are you sure you want to delete this artist? This action cannot be undone.</p>
          <input type="hidden" id="deleteArtistId">
          <div class="flex justify-end gap-2">
            <button id="cancelDeleteBtn" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm">Cancel</button>
            <button id="confirmDeleteBtn" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm">Delete</button>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script>
    // DOM elements
    const artistTableBody = document.getElementById('artistTableBody');
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const artistModal = document.getElementById('artistModal');
    const deleteModal = document.getElementById('deleteModal');
    const closeModal = document.getElementById('closeModal');
    const closeDeleteModal = document.getElementById('closeDeleteModal');
    const saveArtistBtn = document.getElementById('saveArtistBtn');
    const resetPasswordBtn = document.getElementById('resetPasswordBtn');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const modalTitle = document.getElementById('modalTitle');
    const artistIdInput = document.getElementById('artistId');
    const artistFirstNameInput = document.getElementById('artistFirstName');
    const artistMiddleNameInput = document.getElementById('artistMiddleName');
    const artistLastNameInput = document.getElementById('artistLastName');
    const artistSuffixInput = document.getElementById('artistSuffix');
    const artistEmailInput = document.getElementById('artistEmail');
    const artistUsernameInput = document.getElementById('artistUsername');
    const artistMunicipalityInput = document.getElementById('artistMunicipality');
    const artistProvinceInput = document.getElementById('artistProvince');
    const artistBioInput = document.getElementById('artistBio');
    const artistWebsiteInput = document.getElementById('artistWebsite');
    const artistProfilePictureInput = document.getElementById('artistProfilePicture');
    const artistStatusInput = document.getElementById('artistStatus');
    const artistStageNameInput = document.getElementById('artistStageName');
    const artistArtStyleInput = document.getElementById('artistArtStyle');
    const artistPortfolioLinkInput = document.getElementById('artistPortfolioLink');
    const deleteArtistIdInput = document.getElementById('deleteArtistId');

    // Fetch artists
    async function fetchArtists() {
      try {
        const response = await fetch('fetch_artists.php');
        const artists = await response.json();
        if (artists.error) throw new Error(artists.error);
        return artists;
      } catch (error) {
        console.error('Error fetching artists:', error);
        alert('Failed to load artists: ' + error.message);
        return [];
      }
    }

    // Helper function to concatenate artist name
    function getArtistName(artist) {
      return [artist.first_name, artist.middle_name, artist.last_name, artist.suffix]
        .filter(Boolean)
        .join(' ')
        .trim();
    }

    // Render artists table
    function renderArtists(artistsToDisplay) {
      artistTableBody.innerHTML = '';
      artistsToDisplay.forEach(artist => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td class="p-3">
            <div class="flex items-center">
              <img src="${artist.profile_picture || 'https://via.placeholder.com/32'}" class="w-8 h-8 rounded-full mr-3 object-cover" alt="Profile">
              <span>${getArtistName(artist)}</span>
            </div>
          </td>
          <td class="p-3 text-sm text-gray-600">${artist.email}</td>
          <td class="p-3 text-sm text-gray-600">${artist.username || '-'}</td>
          <td class="p-3 text-sm text-gray-600">${artist.municipality}, ${artist.province}</td>
          <td class="p-3 text-sm text-gray-600">${artist.artworks}</td>
          <td class="p-3 text-sm text-gray-600">${artist.followers}</td>
          <td class="p-3 text-sm text-gray-600">${artist.following}</td>
          <td class="p-3">
            <span class="px-2 py-1 text-xs rounded-full ${
              artist.verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
            }">${artist.verified ? 'Verified' : 'Unverified'}</span>
          </td>
          <td class="p-3 text-sm text-gray-600">${new Date(artist.created_at).toLocaleDateString()}</td>
          <td class="p-3 text-right">
            <button class="edit-btn text-gray-400 hover:text-gray-600 mr-2" data-id="${artist.id}">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
              </svg>
            </button>
            <button class="delete-btn text-gray-400 hover:text-gray-600" data-id="${artist.id}">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
              </svg>
            </button>
          </td>
        `;
        artistTableBody.appendChild(row);
      });

      // Add event listeners to edit and delete buttons
      document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => editArtist(btn.dataset.id));
      });
      document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => openDeleteModal(btn.dataset.id));
      });
    }

    // Search and filter artists
    async function filterArtists() {
      const searchTerm = searchInput.value.toLowerCase();
      const status = statusFilter.value;
      let artists = await fetchArtists();

      const filteredArtists = artists.filter(artist => {
        const artistName = getArtistName(artist).toLowerCase();
        const matchesSearch = artistName.includes(searchTerm) || 
                             artist.email.toLowerCase().includes(searchTerm) ||
                             (artist.username && artist.username.toLowerCase().includes(searchTerm));
        const matchesStatus = status === 'all' || 
                             (status === 'verified' && artist.verified) || 
                             (status === 'unverified' && !artist.verified);
        return matchesSearch && matchesStatus;
      });

      renderArtists(filteredArtists);
    }

    // Open edit modal
    async function editArtist(id) {
      const artists = await fetchArtists();
      const artist = artists.find(a => a.id === parseInt(id));
      if (artist) {
        modalTitle.textContent = 'Edit Artist';
        artistIdInput.value = artist.id;
        artistFirstNameInput.value = artist.first_name;
        artistMiddleNameInput.value = artist.middle_name || '';
        artistLastNameInput.value = artist.last_name;
        artistSuffixInput.value = artist.suffix || '';
        artistEmailInput.value = artist.email;
        artistUsernameInput.value = artist.username || '';
        artistMunicipalityInput.value = artist.municipality;
        artistProvinceInput.value = artist.province;
        artistBioInput.value = artist.bio || '';
        artistWebsiteInput.value = artist.website || '';
        artistProfilePictureInput.value = artist.profile_picture || '';
        artistStatusInput.value = artist.verified ? '1' : '0';
        artistStageNameInput.value = artist.stage_name || '';
        artistArtStyleInput.value = artist.art_style || '';
        artistPortfolioLinkInput.value = artist.portfolio_link || '';
        artistModal.style.display = 'block';
      }
    }

    // Open delete confirmation modal
    function openDeleteModal(id) {
      deleteArtistIdInput.value = id;
      deleteModal.style.display = 'block';
    }

    // Close modals
    closeModal.addEventListener('click', () => {
      artistModal.style.display = 'none';
    });

    closeDeleteModal.addEventListener('click', () => {
      deleteModal.style.display = 'none';
    });

    cancelDeleteBtn.addEventListener('click', () => {
      deleteModal.style.display = 'none';
    });

    // Save artist
    saveArtistBtn.addEventListener('click', async () => {
        try {
            const response = await fetch('update_artists.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    artistId: artistIdInput.value,
                    artistFirstName: artistFirstNameInput.value,
                    artistMiddleName: artistMiddleNameInput.value,
                    artistLastName: artistLastNameInput.value,
                    artistSuffix: artistSuffixInput.value,
                    artistEmail: artistEmailInput.value,
                    artistUsername: artistUsernameInput.value,
                    artistMunicipality: artistMunicipalityInput.value,
                    artistProvince: artistProvinceInput.value,
                    artistBio: artistBioInput.value,
                    artistWebsite: artistWebsiteInput.value,
                    artistProfilePicture: artistProfilePictureInput.value,
                    artistStatus: artistStatusInput.value,
                    artistStageName: artistStageNameInput.value,
                    artistArtStyle: artistArtStyleInput.value,
                    artistPortfolioLink: artistPortfolioLinkInput.value
                })
            });

            const result = await response.json();
            if (result.success) {
                alert('Artist updated successfully');
                artistModal.style.display = 'none';
                filterArtists(); // Refresh the table
            } else {
                throw new Error(result.error);
            }
        } catch (error) {
            alert('Failed to update artist: ' + error.message);
        }
    });

    // Delete artist
    confirmDeleteBtn.addEventListener('click', async () => {
      alert('Delete functionality requires delete_artist.php. Please implement this or request it.');
      deleteModal.style.display = 'none';
    });

    // Reset password
    resetPasswordBtn.addEventListener('click', async () => {
      alert('Reset password functionality requires reset_password.php. Please implement this or request it.');
    });

    // Event listeners for search and filter
    searchInput.addEventListener('input', filterArtists);
    statusFilter.addEventListener('change', filterArtists);

    // Initial render
    filterArtists();
  </script>
</body>
</html>