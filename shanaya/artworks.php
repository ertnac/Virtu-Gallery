<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>VirtuGallery - Artwork Management</title>
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
        <a href="artworks.php" class="sidebar-link flex items-center p-3 rounded-lg text-gray-600 hover:bg-[#202b1d] hover:text-white active">
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
      <!-- Artwork Management -->
      <div class="bg-white p-6 rounded-xl shadow-soft">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-lg font-semibold text-gray-800">Artwork Management</h2>
          <button id="addArtworkBtn" class="bg-[#202b1d] text-white px-4 py-2 rounded-lg text-sm">Add New Artwork</button>
        </div>
        
        <!-- Search and Filter -->
        <div class="flex flex-col md:flex-row gap-4 mb-4">
          <input type="text" id="searchInput" placeholder="Search artworks..." class="w-full md:w-1/3 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]">
          <select id="categoryFilter" class="w-full md:w-1/4 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b11d]">
            <option value="all">All Categories</option>
            <option value="paintings">Paintings</option>
            <option value="sculpture">Sculpture</option>
            <option value="photography">Photography</option>
            <option value="digital">Digital</option>
          </select>
          <select id="artistFilter" class="w-full md:w-1/4 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]">
            <option value="all">All Artists</option>
            <option value="John Doe">John Doe</option>
            <option value="Jane Smith">Jane Smith</option>
            <option value="Michael Chen">Michael Chen</option>
          </select>
          <select id="priceFilter" class="w-full md:w-1/4 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]">
            <option value="all">All Prices</option>
            <option value="0-100">$0 - $100</option>
            <option value="101-500">$101 - $500</option>
            <option value="501-1000">$501 - $1000</option>
            <option value="1001+">$1001+</option>
          </select>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left">
            <thead class="bg-gray-50">
              <tr class="text-gray-600 text-sm">
                <th class="p-3 font-medium">Title</th>
                <th class="p-3 font-medium">Artist</th>
                <th class="p-3 font-medium">Category</th>
                <th class="p-3 font-medium">Price</th>
                <th class="p-3 font-medium">Likes</th>
                <th class="p-3 font-medium">Comments</th>
                <th class="p-3 font-medium">Reviews</th>
                <th class="p-3 font-medium text-right">Actions</th>
              </tr>
            </thead>
            <tbody id="artworkTableBody" class="divide-y divide-gray-200">
              <!-- Artwork rows will be dynamically populated -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- Add/Edit Artwork Modal -->
      <div id="artworkModal" class="modal">
        <div class="modal-content">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800" id="modalTitle">Add New Artwork</h2>
            <button id="closeModal" class="text-gray-500 hover:text-gray-700">×</button>
          </div>
          <div class="space-y-4">
            <input type="hidden" id="artworkId">
            <div>
              <label class="block text-sm font-medium text-gray-700">Title</label>
              <input type="text" id="artworkTitle" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Artist</label>
              <select id="artworkArtist" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]">
                <option value="John Doe">John Doe</option>
                <option value="Jane Smith">Jane Smith</option>
                <option value="Michael Chen">Michael Chen</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Category</label>
              <select id="artworkCategory" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]">
                <option value="paintings">Paintings</option>
                <option value="sculpture">Sculpture</option>
                <option value="photography">Photography</option>
                <option value="digital">Digital</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Price ($)</label>
              <input type="number" id="artworkPrice" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]">
            </div>
            < bool >  <label class="block text-sm font-medium text-gray-700">Description</label>
              <textarea id="artworkDescription" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]"></textarea>
            </div>
            <div class="flex justify-end gap-2">
              <button id="cancelArtworkBtn" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm">Cancel</button>
              <button id="saveArtworkBtn" class="bg-[#202b1d] text-white px-4 py-2 rounded-lg text-sm">Save</button>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script>
let artworks = [];

const artworkTableBody = document.getElementById('artworkTableBody');
const searchInput = document.getElementById('searchInput');
const categoryFilter = document.getElementById('categoryFilter');
const artistFilter = document.getElementById('artistFilter');
const priceFilter = document.getElementById('priceFilter');
const artworkModal = document.getElementById('artworkModal');
const modalTitle = document.getElementById('modalTitle');
const addArtworkBtn = document.getElementById('addArtworkBtn');
const closeModal = document.getElementById('closeModal');
const cancelArtworkBtn = document.getElementById('cancelArtworkBtn');
const saveArtworkBtn = document.getElementById('saveArtworkBtn');
const artworkIdInput = document.getElementById('artworkId');
const artworkTitle = document.getElementById('artworkTitle');
const artworkArtist = document.getElementById('artworkArtist');
const artworkCategory = document.getElementById('artworkCategory');
const artworkPrice = document.getElementById('artworkPrice');
const artworkDescription = document.getElementById('artworkDescription');

function renderArtworks(artworksToDisplay) {
  console.log('Rendering artworks:', artworksToDisplay);
  artworkTableBody.innerHTML = '';
  artworksToDisplay.forEach(artwork => {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td class="p-3">
        <div class="flex items-center">
          <div class="w-8 h-8 rounded-full bg-gray-300 mr-3"></div>
          <span>${artwork.title}</span>
        </div>
      </td>
      <td class="p-3 text-sm text-gray-600">${artwork.artist}</td>
      <td class="p-3 text-sm text-gray-600">${artwork.category.charAt(0).toUpperCase() + artwork.category.slice(1)}</td>
      <td class="p-3 text-sm text-gray-600">$${artwork.price}</td>
      <td class="p-3 text-sm text-gray-600">${artwork.likes}</td>
      <td class="p-3 text-sm text-gray-600">${artwork.comments}</td>
      <td class="p-3 text-sm text-gray-600">${artwork.reviews}</td>
      <td class="p-3 text-right">
        <button class="edit-btn text-gray-400 hover:text-gray-600 mr-2" data-id="${artwork.id}">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
        </button>
        <button class="delete-btn text-gray-400 hover:text-gray-600" data-id="${artwork.id}">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
        </button>
      </td>
    `;
    artworkTableBody.appendChild(row);
  });

  document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      console.log('Edit button clicked for ID:', btn.dataset.id);
      openEditModal(btn.dataset.id);
    });
  });
  document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      console.log('Delete button clicked for ID:', btn.dataset.id);
      deleteArtwork(btn.dataset.id);
    });
  });
}

function filterArtworks() {
  const searchTerm = searchInput.value.toLowerCase();
  const category = categoryFilter.value;
  const artist = artistFilter.value;
  const priceRange = priceFilter.value;

  const filteredArtworks = artworks.filter(artwork => {
    const matchesSearch = artwork.title.toLowerCase().includes(searchTerm) || 
                          artwork.artist.toLowerCase().includes(searchTerm);
    const matchesCategory = category === 'all' || artwork.category === category;
    const matchesArtist = artist === 'all' || artwork.artist === artist;
    let matchesPrice = true;
    if (priceRange !== 'all') {
      const [min, max] = priceRange.split('-').map(Number);
      matchesPrice = max ? artwork.price >= min && artwork.price <= max : artwork.price >= min;
    }
    return matchesSearch && matchesCategory && matchesArtist && matchesPrice;
  });

  renderArtworks(filteredArtworks);
}

function fetchArtworksFromServer() {
  fetch('fetch_artworks.php')
    .then(res => {
      console.log('Fetch artworks response status:', res.status);
      return res.json();
    })
    .then(data => {
      console.log('Fetched artworks:', data);
      artworks = data;
      updateArtistFilterOptions(data);
      renderArtworks(artworks);
    })
    .catch(err => console.error('Error fetching artworks:', err));
}

function updateArtistFilterOptions(data) {
  const artistSelect = document.getElementById('artistFilter');
  const artistSet = new Set(data.map(item => item.artist));
  artistSelect.innerHTML = '<option value="all">All Artists</option>';
  artistSet.forEach(artist => {
    const opt = document.createElement('option');
    opt.value = artist;
    opt.textContent = artist;
    artistSelect.appendChild(opt);
  });
}

function openAddModal() {
  console.log('Opening add modal');
  modalTitle.textContent = 'Add New Artwork';
  artworkIdInput.value = '';
  artworkTitle.value = '';
  artworkArtist.value = '';
  artworkCategory.value = 'paintings';
  artworkPrice.value = '';
  artworkDescription.value = '';
  artworkModal.style.display = 'block';
}

function openEditModal(id) {
  console.log('Opening edit modal for ID:', id);
  const artwork = artworks.find(a => a.id == id); // Loose comparison for type coercion
  if (artwork) {
    console.log('Found artwork:', artwork);
    modalTitle.textContent = 'Edit Artwork';
    artworkIdInput.value = artwork.id;
    artworkTitle.value = artwork.title;
    artworkArtist.value = artwork.artist;
    artworkCategory.value = artwork.category;
    artworkPrice.value = artwork.price;
    artworkDescription.value = artwork.description || '';
    artworkModal.style.display = 'block';
  } else {
    console.error('Artwork not found for ID:', id);
    alert('Artwork not found');
  }
}

function closeModalFunc() {
  console.log('Closing modal');
  artworkModal.style.display = 'none';
}

function saveArtwork() {
  const artworkData = {
    id: artworkIdInput.value,
    title: artworkTitle.value.trim(),
    artist: artworkArtist.value,
    category: artworkCategory.value,
    price: parseFloat(artworkPrice.value),
    description: artworkDescription.value.trim(),
    likes: artworkIdInput.value ? artworks.find(a => a.id == artworkIdInput.value)?.likes || 0 : 0,
    comments: artworkIdInput.value ? artworks.find(a => a.id == artworkIdInput.value)?.comments || 0 : 0,
    reviews: artworkIdInput.value ? artworks.find(a => a.id == artworkIdInput.value)?.reviews || 0 : 0
  };
  console.log('Saving artwork:', artworkData);

  if (!artworkData.title || !artworkData.artist || !artworkData.category || isNaN(artworkData.price) || artworkData.price < 0) {
    console.warn('Validation failed: Missing or invalid required fields');
    alert('Please fill in all required fields with valid values');
    return;
  }

  fetch('save_artwork.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(artworkData)
  })
    .then(res => {
      console.log('Save artwork response status:', res.status);
      return res.json();
    })
    .then(data => {
      console.log('Save artwork response data:', data);
      if (data.success) {
        if (artworkData.id) {
          const index = artworks.findIndex(a => a.id == artworkData.id);
          if (index !== -1) {
            artworks[index] = artworkData;
          } else {
            console.warn('Artwork ID not found in array:', artworkData.id);
          }
        } else {
          artworkData.id = data.id; // Assuming server returns new ID
          artworks.push(artworkData);
        }
        updateArtistFilterOptions(artworks);
        filterArtworks();
        closeModalFunc();
      } else {
        console.error('Save failed:', data.message);
        alert('Error saving artwork: ' + data.message);
      }
    })
    .catch(err => {
      console.error('Error saving artwork:', err);
      alert('Error saving artwork');
    });
}

function deleteArtwork(id) {
  if (!confirm('Are you sure you want to delete this artwork?')) return;
  console.log('Deleting artwork with ID:', id);

  fetch('delete_artwork.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ id })
  })
    .then(res => {
      console.log('Delete artwork response status:', res.status);
      return res.json();
    })
    .then(data => {
      console.log('Delete artwork response data:', data);
      if (data.success) {
        artworks = artworks.filter(a => a.id != id);
        updateArtistFilterOptions(artworks);
        filterArtworks();
      } else {
        console.error('Delete failed:', data.message);
        alert('Error deleting artwork: ' + data.message);
      }
    })
    .catch(err => {
      console.error('Error deleting artwork:', err);
      alert('Error deleting artwork');
    });
}

searchInput.addEventListener('input', filterArtworks);
categoryFilter.addEventListener('change', filterArtworks);
artistFilter.addEventListener('change', filterArtworks);
priceFilter.addEventListener('change', filterArtworks);
addArtworkBtn.addEventListener('click', openAddModal);
closeModal.addEventListener('click', closeModalFunc);
cancelArtworkBtn.addEventListener('click', closeModalFunc);
saveArtworkBtn.addEventListener('click', saveArtwork);

document.addEventListener('DOMContentLoaded', () => {
  fetchArtworksFromServer();
  document.querySelector('a[href="artworks.php"]').classList.add('active');
});
</script>

</body>
</html>