<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php'); // Redirect to login page if not authenticated
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/svg+xml" href="../assets/images/logo-icon.svg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload New Post | VirtuGallery</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #202b1d;
            --secondary-color: #1a2318;
            --accent-color: #ecd5a6;
            --text-color: #333;
            --light-bg: #f9f9f9;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            color: var(--text-color);
            background-color: var(--light-bg);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            position: relative;
        }

        .background-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(249, 249, 249, 0.9);
            z-index: -1;
        }

        .new-post-container {
            width: 100%;
            max-width: 650px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 40px;
            position: relative;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        h2 {
            font-family: 'Playfair Display', serif;
            color: var(--primary-color);
            font-size: 2rem;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
            padding-bottom: 15px;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: var(--accent-color);
        }

        label {
            display: block;
            margin: 20px 0 8px;
            font-weight: 500;
            color: var(--primary-color);
            font-size: 0.95rem;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: white;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(32, 43, 29, 0.2);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px;
        }

        .preview-area {
            margin: 20px 0;
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            background-color: rgba(255, 255, 255, 0.5);
            position: relative;
            overflow: hidden;
        }

        .preview-area:hover {
            border-color: var(--primary-color);
        }

        .preview-area img {
            max-width: 100%;
            max-height: 300px;
            display: none;
            margin: 0 auto;
            border-radius: 4px;
        }

        .preview-text {
            color: #777;
            font-size: 0.9rem;
            margin-top: 10px;
        }

        .upload-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 6px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        button:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(32, 43, 29, 0.2);
        }

        button:active {
            transform: translateY(0);
        }

        /* New circular back button styles */
        .back-button-circle {
            position: fixed;
            top: 30px;
            left: 30px;
            width: 50px;
            height: 50px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
            border: none;
            color: var(--primary-color);
        }

        .back-button-circle:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateX(-3px);
        }

        .back-button-circle i {
            font-size: 1.2rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .price-field {
            display: none;
            margin-top: 20px;
            position: relative;
        }

        .price-field.active {
            display: block;
        }

        .peso-symbol {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-weight: 500;
            color: var(--primary-color);
        }

        .price-input {
            padding-left: 30px !important;
        }

        /* Success Modal */
        .success-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .success-modal.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .modal-content i {
            font-size: 3rem;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .modal-content h3 {
            font-family: 'Playfair Display', serif;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .modal-content p {
            margin-bottom: 20px;
            color: #555;
        }

        .modal-button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .modal-button:hover {
            background-color: var(--secondary-color);
        }

        @media (max-width: 768px) {
            .new-post-container {
                padding: 30px 20px;
            }

            h2 {
                font-size: 1.6rem;
                margin-bottom: 25px;
            }

            .back-button-circle {
                top: 20px;
                left: 20px;
                width: 45px;
                height: 45px;
            }
        }
    </style>
</head>

<body>
    <div class="background-overlay"></div>

    <!-- Circular back button -->
    <button class="back-button-circle" onclick="window.history.back()">
        <i class="fas fa-arrow-left"></i>
    </button>

    <div class="new-post-container">
        <h2>Upload New Artwork</h2>

        <form id="uploadForm" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="post-title">Artwork Title</label>
                <input type="text" id="post-title" name="title" placeholder="Enter your artwork title" required>
            </div>

            <div class="form-group">
                <label for="post-description">Description</label>
                <textarea id="post-description" name="description" placeholder="Tell us about your artwork... its inspiration, techniques used, or special meaning" required></textarea>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <option value="" disabled selected>Select a category</option>
                    <option value="paintings">Paintings</option>
                    <option value="pottery">Pottery</option>
                    <option value="sculpture">Sculpture</option>
                    <option value="photography">Photography</option>
                </select>
            </div>

            <div class="form-group">
                <label for="sale-option">Post Type</label>
                <select id="sale-option" name="post_type">
                    <option value="content">For Display Only</option>
                    <option value="product">For Sale</option>
                </select>
            </div>

            <div class="form-group price-field" id="price-field">
                <label for="price">Price</label>
                <div style="position: relative;">
                    <span class="peso-symbol">₱</span>
                    <input type="number" id="price" name="price" placeholder="Enter price" min="0" step="1" class="price-input">
                </div>
            </div>

            <div class="form-group">
                <label for="tags">Tags</label>
                <input type="text" id="tags" name="tags" placeholder="modern, abstract, landscape (separate with commas)">
            </div>

            <div class="form-group">
                <label for="post-image">Upload Image</label>
                <input type="file" id="post-image" name="image" accept="image/*" required style="display: none;">
                <div class="preview-area" id="preview-area">
                    <div class="upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <p>Click to upload artwork image</p>
                    <p class="preview-text">JPEG, PNG (Max 5MB)</p>
                    <img id="image-preview" src="" alt="Preview">
                </div>
            </div>

            <button type="submit">
                <i class="fas fa-upload"></i> Publish Artwork
            </button>
        </form>
    </div>

    <div class="success-modal" id="successModal">
        <div class="modal-content">
            <i class="fas fa-check-circle"></i>
            <h3>Artwork Published!</h3>
            <p>Your artwork has been successfully uploaded to VirtuGallery.</p>
            <button class="modal-button" id="modalButton">Return to Home</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Image preview functionality
            const fileInput = document.getElementById('post-image');
            const previewArea = document.getElementById('preview-area');
            const imagePreview = document.getElementById('image-preview');

            previewArea.addEventListener('click', function() {
                fileInput.click();
            });

            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    if (file.size > 5 * 1024 * 1024) {
                        alert('File size exceeds 5MB limit');
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                        previewArea.querySelector('p').style.display = 'none';
                        previewArea.querySelector('.upload-icon').style.display = 'none';
                        previewArea.querySelector('.preview-text').style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Show/hide price field
            const saleOption = document.getElementById('sale-option');
            const priceField = document.getElementById('price-field');
            saleOption.addEventListener('change', function() {
                if (this.value === 'product') {
                    priceField.classList.add('active');
                    document.getElementById('price').setAttribute('required', '');
                } else {
                    priceField.classList.remove('active');
                    document.getElementById('price').removeAttribute('required');
                }
            });

            // Form submission with AJAX
            const form = document.getElementById('uploadForm');
            const successModal = document.getElementById('successModal');
            const modalButton = document.getElementById('modalButton');

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                if (saleOption.value === 'product' && !document.getElementById('price').value) {
                    alert('Please enter a price for your artwork');
                    return;
                }
                if (!fileInput.files.length) {
                    alert('Please upload an image of your artwork');
                    return;
                }

                const formData = new FormData(form);
                fetch('../upload.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            if (response.status === 404) {
                                throw new Error('Upload script not found. Ensure upload.php is in the correct directory.');
                            }
                            throw new Error('HTTP error ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            successModal.classList.add('active');
                            setTimeout(function() {
                                window.location.href = 'home.php';
                            }, 3000);
                        } else {
                            alert('Upload failed: ' + (data.errors || ['Unknown error']).join(', '));
                        }
                    })
                    .catch(error => {
                        alert('An error occurred: ' + error.message);
                    });
            });

            modalButton.addEventListener('click', function() {
                window.location.href = 'home.php';
            });
        });
    </script>
</body>

</html>