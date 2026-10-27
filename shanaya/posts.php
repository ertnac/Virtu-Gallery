<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>VirtuGallery - Post Management</title>
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
      max-width: 800px;
      border-radius: 8px;
    }
    .tab-button.active {
      background-color: #202b1d;
      color: white;
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
            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.594A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
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
      <!-- Post Management -->
      <div class="bg-white p-6 rounded-xl shadow-soft">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-lg font-semibold text-gray-800">Post Management</h2>
        </div>
        
        <!-- Search and Filter -->
        <div class="flex flex-col md:flex-row gap-4 mb-4">
          <input type="text" id="searchInput" placeholder="Search artworks..." class="w-full md:w-1/3 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]">
          <select id="artistFilter" class="w-full md:w-1/4 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]">
            <option value="all">All Artists</option>
            <option value="john_doe">John Doe</option>
            <option value="jane_smith">Jane Smith</option>
          </select>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left">
            <thead class="bg-gray-50">
              <tr class="text-gray-600 text-sm">
                <th class="p-3 font-medium">Artwork</th>
                <th class="p-3 font-medium">Artist</th>
                <th class="p-3 font-medium">Total Likes</th>
                <th class="p-3 font-medium">Total Comments</th>
                <th class="p-3 font-medium">Total Reviews</th>
                <th class="p-3 font-medium text-right">Actions</th>
              </tr>
            </thead>
            <tbody id="postTableBody" class="divide-y divide-gray-200">
              <!-- Post rows will be dynamically populated -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- Post Details Modal -->
      <div id="postModal" class="modal">
        <div class="modal-content">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800" id="modalTitle">Post Details</h2>
            <button id="closeModal" class="text-gray-500 hover:text-gray-700">×</button>
          </div>
          <div class="flex space-x-4 mb-4">
            <button id="likesTab" class="tab-button px-4 py-2 rounded-lg bg-gray-200 text-gray-600 hover:bg-[#202b1d] hover:text-white active">Likes</button>
            <button id="commentsTab" class="tab-button px-4 py-2 rounded-lg bg-gray-200 text-gray-600 hover:bg-[#202b1d] hover:text-white">Comments</button>
            <button id="reviewsTab" class="tab-button px-4 py-2 rounded-lg bg-gray-200 text-gray-600 hover:bg-[#202b1d] hover:text-white">Reviews</button>
          </div>
          <div id="likesContent" class="tab-content">
            <table class="w-full text-left">
              <thead class="bg-gray-50">
                <tr class="text-gray-600 text-sm">
                  <th class="p-3 font-medium">User</th>
                  <th class="p-3 font-medium">Date Liked</th>
                  <th class="p-3 font-medium text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="likesTableBody" class="divide-y divide-gray-200"></tbody>
            </table>
          </div>
          <div id="commentsContent" class="tab-content hidden">
            <table class="w-full text-left">
              <thead class="bg-gray-50">
                <tr class="text-gray-600 text-sm">
                  <th class="p-3 font-medium">Commenter</th>
                  <th class="p-3 font-medium">Comment</th>
                  <th class="p-3 font-medium">Date</th>
                  <th class="p-3 font-medium">Status</th>
                  <th class="p-3 font-medium text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="commentsTableBody" class="divide-y divide-gray-200"></tbody>
            </table>
          </div>
          <div id="reviewsContent" class="tab-content hidden">
            <table class="w-full text-left">
              <thead class="bg-gray-50">
                <tr class="text-gray-600 text-sm">
                  <th class="p-3 font-medium">Reviewer</th>
                  <th class="p-3 font-medium">Rating</th>
                  <th class="p-3 font-medium">Comment</th>
                  <th class="p-3 font-medium">Date</th>
                  <th class="p-3 font-medium">Status</th>
                  <th class="p-3 font-medium text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="reviewsTableBody" class="divide-y divide-gray-200"></tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Comment Moderation Modal -->
      <div id="commentModal" class="modal">
        <div class="modal-content">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Moderate Comment</h2>
            <button id="closeCommentModal" class="text-gray-500 hover:text-gray-700">×</button>
          </div>
          <div class="space-y-4">
            <input type="hidden" id="commentId">
            <div>
              <label class="block text-sm font-medium text-gray-700">Commenter</label>
              <input type="text" id="commentCommenter" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]" disabled>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Artwork</label>
              <input type="text" id="commentArtwork" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]" disabled>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Artist</label>
              <input type="text" id="commentArtist" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]" disabled>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Comment</label>
              <textarea id="commentText" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]" rows="4"></textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Date</label>
              <input type="text" id="commentDate" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]" disabled>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Status</label>
              <select id="commentStatus" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]">
                <option value="approved">Approved</option>
                <option value="flagged">Flagged</option>
                <option value="deleted">Deleted</option>
              </select>
            </div>
            <div class="flex justify-end gap-2">
              <button id="saveCommentBtn" class="bg-[#202b1d] text-white px-4 py-2 rounded-lg text-sm">Save</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Review Moderation Modal -->
      <div id="reviewModal" class="modal">
        <div class="modal-content">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Moderate Review</h2>
            <button id="closeReviewModal" class="text-gray-500 hover:text-gray-700">×</button>
          </div>
          <div class="space-y-4">
            <input type="hidden" id="reviewId">
            <div>
              <label class="block text-sm font-medium text-gray-700">Artwork</label>
              <input type="text" id="reviewArtwork" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]" disabled>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Reviewer</label>
              <input type="text" id="reviewReviewer" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]" disabled>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Rating</label>
              <input type="number" id="reviewRating" min="1" max="5" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Comment</label>
              <textarea id="reviewComment" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring[#202b1d]" rows="4"></textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Date</label>
              <input type="text" id="reviewDate" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]" disabled>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Status</label>
              <select id="reviewStatus" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#202b1d]">
                <option value="approved">Approved</option>
                <option value="flagged">Flagged</option>
                <option value="deleted">Deleted</option>
              </select>
            </div>
            <div class="flex justify-end gap-2">
              <button id="saveReviewBtn" class="bg-[#202b1d] text-white px-4 py-2 rounded-lg text-sm">Save</button>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script>
    // Sample data (in a real application, this would come from a backend)
    const posts = [
      { id: 1, artwork: 'Sunset Over Mountains', artist: 'John Doe', likes: 150, comments: 45, reviews: 20 },
      { id: 2, artwork: 'Abstract Waves', artist: 'Jane Smith', likes: 200, comments: 60, reviews: 25 },
      { id: 3, artwork: 'City Lights', artist: 'John Doe', likes: 100, comments: 30, reviews: 15 }
    ];

    const likes = [
      { id: 1, artworkId: 1, user: 'Alice Brown', date: '2025-06-04' },
      { id: 2, artworkId: 1, user: 'Bob Green', date: '2025-06-03' },
      { id: 3, artworkId: 2, user: 'Carol White', date: '2025-06-04' }
    ];

    const comments = [
      { id: 1, artworkId: 1, commenter: 'Alice Brown', comment: 'Beautiful artwork!', date: '2025-06-04', status: 'approved', artist: 'John Doe' },
      { id: 2, artworkId: 1, commenter: 'Bob Green', comment: 'Love the colors!', date: '2025-06-03', status: 'flagged', artist: 'John Doe' },
      { id: 3, artworkId: 2, commenter: 'Carol White', comment: 'Very creative!', date: '2025-06-04', status: 'approved', artist: 'Jane Smith' }
    ];

    const reviews = [
      { id: 1, artworkId: 1, reviewer: 'Alice Brown', rating: 5, comment: 'Stunning piece!', date: '2025-06-04', status: 'approved' },
      { id: 2, artworkId: 1, reviewer: 'Bob Green', rating: 4, comment: 'Great work!', date: '2025-06-03', status: 'approved' },
      { id: 3, artworkId: 2, reviewer: 'Carol White', rating: 5, comment: 'Amazing!', date: '2025-06-04', status: 'flagged' }
    ];

    // DOM elements
    const postTableBody = document.getElementById('postTableBody');
    const searchInput = document.getElementById('searchInput');
    const artistFilter = document.getElementById('artistFilter');
    const postModal = document.getElementById('postModal');
    const closeModal = document.getElementById('closeModal');
    const modalTitle = document.getElementById('modalTitle');
    const likesTab = document.getElementById('likesTab');
    const commentsTab = document.getElementById('commentsTab');
    const reviewsTab = document.getElementById('reviewsTab');
    const likesContent = document.getElementById('likesContent');
    const commentsContent = document.getElementById('commentsContent');
    const reviewsContent = document.getElementById('reviewsContent');
    const likesTableBody = document.getElementById('likesTableBody');
    const commentsTableBody = document.getElementById('commentsTableBody');
    const reviewsTableBody = document.getElementById('reviewsTableBody');
    const commentModal = document.getElementById('commentModal');
    const closeCommentModal = document.getElementById('closeCommentModal');
    const saveCommentBtn = document.getElementById('saveCommentBtn');
    const commentIdInput = document.getElementById('commentId');
    const commentCommenterInput = document.getElementById('commentCommenter');
    const commentArtworkInput = document.getElementById('commentArtwork');
    const commentArtistInput = document.getElementById('commentArtist');
    const commentTextInput = document.getElementById('commentText');
    const commentDateInput = document.getElementById('commentDate');
    const commentStatusInput = document.getElementById('commentStatus');
    const reviewModal = document.getElementById('reviewModal');
    const closeReviewModal = document.getElementById('closeReviewModal');
    const saveReviewBtn = document.getElementById('saveReviewBtn');
    const reviewIdInput = document.getElementById('reviewId');
    const reviewArtworkInput = document.getElementById('reviewArtwork');
    const reviewReviewerInput = document.getElementById('reviewReviewer');
    const reviewRatingInput = document.getElementById('reviewRating');
    const reviewCommentInput = document.getElementById('reviewComment');
    const reviewDateInput = document.getElementById('reviewDate');
    const reviewStatusInput = document.getElementById('reviewStatus');

    // Render posts table
    function renderPosts(postsToDisplay) {
      postTableBody.innerHTML = '';
      postsToDisplay.forEach(post => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td class="p-3">
            <div class="flex items-center">
              <div class="w-8 h-8 rounded-full bg-gray-300 mr-3"></div>
              <span>${post.artwork}</span>
            </div>
          </td>
          <td class="p-3 text-sm text-gray-600">${post.artist}</td>
          <td class="p-3 text-sm text-gray-600">${post.likes}</td>
          <td class="p-3 text-sm text-gray-600">${post.comments}</td>
          <td class="p-3 text-sm text-gray-600">${post.reviews}</td>
          <td class="p-3 text-right">
            <button class="view-btn text-gray-400 hover:text-gray-600" data-id="${post.id}">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
              </svg>
            </button>
          </td>
        `;
        postTableBody.appendChild(row);
      });

      // Add event listeners to view buttons
      document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', () => viewPost(btn.dataset.id));
      });
    }

    // Search and filter posts
    function filterPosts() {
      const searchTerm = searchInput.value.toLowerCase();
      const artist = artistFilter.value;

      const filteredPosts = posts.filter(post => {
        const matchesSearch = post.artwork.toLowerCase().includes(searchTerm) || 
                             post.artist.toLowerCase().includes(searchTerm);
        const matchesArtist = artist === 'all' || post.artist.toLowerCase().replace(' ', '_') === artist;
        return matchesSearch && matchesArtist;
      });

      renderPosts(filteredPosts);
    }

    // Render likes for selected post
    function renderLikes(artworkId) {
      likesTableBody.innerHTML = '';
      const postLikes = likes.filter(like => like.artworkId === parseInt(artworkId));
      postLikes.forEach(like => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td class="p-3">${like.user}</td>
          <td class="p-3 text-sm text-gray-600">${like.date}</td>
          <td class="p-3 text-right">
            <button class="remove-like-btn text-gray-400 hover:text-gray-600" data-id="${like.id}">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
              </svg>
            </button>
          </td>
        `;
        likesTableBody.appendChild(row);
      });

      // Add event listeners to remove like buttons
      document.querySelectorAll('.remove-like-btn').forEach(btn => {
        btn.addEventListener('click', () => removeLike(btn.dataset.id));
      });
    }

    // Render comments for selected post
    function renderComments(artworkId) {
      commentsTableBody.innerHTML = '';
      const postComments = comments.filter(comment => comment.artworkId === parseInt(artworkId));
      postComments.forEach(comment => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td class="p-3">${comment.commenter}</td>
          <td class="p-3 text-sm text-gray-600">${comment.comment}</td>
          <td class="p-3 text-sm text-gray-600">${comment.date}</td>
          <td class="p-3">
            <span class="px-2 py-1 text-xs rounded-full ${
              comment.status === 'approved' ? 'bg-green-100 text-green-800' :
              comment.status === 'flagged' ? 'bg-yellow-100 text-yellow-800' :
              'bg-red-100 text-red-800'
            }">${comment.status.charAt(0).toUpperCase() + comment.status.slice(1)}</span>
          </td>
          <td class="p-3 text-right">
            <button class="edit-comment-btn text-gray-400 hover:text-gray-600 mr-2" data-id="${comment.id}">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
              </svg>
            </button>
            <button class="delete-comment-btn text-gray-400 hover:text-gray-600" data-id="${comment.id}">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
              </svg>
            </button>
          </td>
        `;
        commentsTableBody.appendChild(row);
      });

      // Add event listeners to edit and delete comment buttons
      document.querySelectorAll('.edit-comment-btn').forEach(btn => {
        btn.addEventListener('click', () => editComment(btn.dataset.id));
      });
      document.querySelectorAll('.delete-comment-btn').forEach(btn => {
        btn.addEventListener('click', () => deleteComment(btn.dataset.id));
      });
    }

    // Render reviews for selected post
    function renderReviews(artworkId) {
      reviewsTableBody.innerHTML = '';
      const postReviews = reviews.filter(review => review.artworkId === parseInt(artworkId));
      postReviews.forEach(review => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td class="p-3">${review.reviewer}</td>
          <td class="p-3 text-sm text-gray-600">${review.rating} Stars</td>
          <td class="p-3 text-sm text-gray-600">${review.comment}</td>
          <td class="p-3 text-sm text-gray-600">${review.date}</td>
          <td class="p-3">
            <span class="px-2 py-1 text-xs rounded-full ${
              review.status === 'approved' ? 'bg-green-100 text-green-800' :
              review.status === 'flagged' ? 'bg-yellow-100 text-yellow-800' :
              'bg-red-100 text-red-800'
            }">${review.status.charAt(0).toUpperCase() + review.status.slice(1)}</span>
          </td>
          <td class="p-3 text-right">
            <button class="edit-review-btn text-gray-400 hover:text-gray-600 mr-2" data-id="${review.id}">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
              </svg>
            </button>
            <button class="delete-review-btn text-gray-400 hover:text-gray-600" data-id="${review.id}">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
              </svg>
            </button>
          </td>
        `;
        reviewsTableBody.appendChild(row);
      });

      // Add event listeners to edit and delete review buttons
      document.querySelectorAll('.edit-review-btn').forEach(btn => {
        btn.addEventListener('click', () => editReview(btn.dataset.id));
      });
      document.querySelectorAll('.delete-review-btn').forEach(btn => {
        btn.addEventListener('click', () => deleteReview(btn.dataset.id));
      });
    }

    // View post details
    function viewPost(id) {
      const post = posts.find(p => p.id === parseInt(id));
      if (post) {
        modalTitle.textContent = `Details for ${post.artwork}`;
        renderLikes(id);
        renderComments(id);
        renderReviews(id);
        likesTab.classList.add('active');
        commentsTab.classList.remove('active');
        reviewsTab.classList.remove('active');
        likesContent.classList.remove('hidden');
        commentsContent.classList.add('hidden');
        reviewsContent.classList.add('hidden');
        postModal.style.display = 'block';
      }
    }

    // Remove like
    function removeLike(id) {
      if (confirm('Are you sure you want to remove this like?')) {
        const like = likes.find(l => l.id === parseInt(id));
        likes.splice(likes.indexOf(like), 1);
        const post = posts.find(p => p.id === like.artworkId);
        post.likes--;
        renderLikes(like.artworkId);
        renderPosts(posts);
      }
    }

    // Edit comment
    function editComment(id) {
      const comment = comments.find(c => c.id === parseInt(id));
      if (comment) {
        const post = posts.find(p => p.id === comment.artworkId);
        commentIdInput.value = comment.id;
        commentCommenterInput.value = comment.commenter;
        commentArtworkInput.value = post.artwork;
        commentArtistInput.value = comment.artist;
        commentTextInput.value = comment.comment;
        commentDateInput.value = comment.date;
        commentStatusInput.value = comment.status;
        commentModal.style.display = 'block';
      }
    }

    // Delete comment
    function deleteComment(id) {
      if (confirm('Are you sure you want to delete this comment?')) {
        const comment = comments.find(c => c.id === parseInt(id));
        comments.splice(comments.indexOf(comment), 1);
        const post = posts.find(p => p.id === comment.artworkId);
        post.comments--;
        renderComments(comment.artworkId);
        renderPosts(posts);
      }
    }

    // Save comment
    saveCommentBtn.addEventListener('click', () => {
      const commentId = commentIdInput.value;
      const comment = comments.find(c => c.id === parseInt(commentId));
      if (comment) {
        comment.comment = commentTextInput.value;
        comment.status = commentStatusInput.value;
        renderComments(comment.artworkId);
        commentModal.style.display = 'none';
      }
    });

    // Edit review
    function editReview(id) {
      const review = reviews.find(r => r.id === parseInt(id));
      if (review) {
        const post = posts.find(p => p.id === review.artworkId);
        reviewIdInput.value = review.id;
        reviewArtworkInput.value = post.artwork;
        reviewReviewerInput.value = review.reviewer;
        reviewRatingInput.value = review.rating;
        reviewCommentInput.value = review.comment;
        reviewDateInput.value = review.date;
        reviewStatusInput.value = review.status;
        reviewModal.style.display = 'block';
      }
    }

    // Delete review
    function deleteReview(id) {
      if (confirm('Are you sure you want to delete this review?')) {
        const review = reviews.find(r => r.id === parseInt(id));
        reviews.splice(reviews.indexOf(review), 1);
        const post = posts.find(p => p.id === review.artworkId);
        post.reviews--;
        renderReviews(review.artworkId);
        renderPosts(posts);
      }
    }

    // Save review
    saveReviewBtn.addEventListener('click', () => {
      const reviewId = reviewIdInput.value;
      const review = reviews.find(r => r.id === parseInt(reviewId));
      if (review) {
        review.rating = parseInt(reviewRatingInput.value);
        review.comment = reviewCommentInput.value;
        review.status = reviewStatusInput.value;
        renderReviews(review.artworkId);
        reviewModal.style.display = 'none';
      }
    });

    // Tab switching
    likesTab.addEventListener('click', () => {
      likesTab.classList.add('active');
      commentsTab.classList.remove('active');
      reviewsTab.classList.remove('active');
      likesContent.classList.remove('hidden');
      commentsContent.classList.add('hidden');
      reviewsContent.classList.add('hidden');
    });

    commentsTab.addEventListener('click', () => {
      likesTab.classList.remove('active');
      commentsTab.classList.add('active');
      reviewsTab.classList.remove('active');
      likesContent.classList.add('hidden');
      commentsContent.classList.remove('hidden');
      reviewsContent.classList.add('hidden');
    });

    reviewsTab.addEventListener('click', () => {
      likesTab.classList.remove('active');
      commentsTab.classList.remove('active');
      reviewsTab.classList.add('active');
      likesContent.classList.add('hidden');
      commentsContent.classList.add('hidden');
      reviewsContent.classList.remove('hidden');
    });

    // Close modals
    closeModal.addEventListener('click', () => {
      postModal.style.display = 'none';
    });

    closeCommentModal.addEventListener('click', () => {
      commentModal.style.display = 'none';
    });

    closeReviewModal.addEventListener('click', () => {
      reviewModal.style.display = 'none';
    });

    // Event listeners for search and filter
    searchInput.addEventListener('input', filterPosts);
    artistFilter.addEventListener('change', filterPosts);

    // Initial render
    renderPosts(posts);

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