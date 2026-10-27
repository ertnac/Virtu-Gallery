<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ArtVibe | Discover Art That Speaks to You</title>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&family=Roboto:wght@400;500;700&display=swap"
    rel="stylesheet" />
  <style>
    /* Existing CSS remains unchanged */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    /* Default state */
    .logout-button {
      color: darkgreen;
      background: none;
      border: none;
      cursor: pointer;
      font-family: inherit;
      font-size: inherit;
      padding: 8px 15px;
      margin-left: 10px;
      font-weight: 500;
    }

    /* Scrolled state */
    header.scrolled .logout-button {
      color: white !important;
      background: transparent;
      /* Optional: Remove background */
    }



    /* Default styles */
    .user-email,
    .logout-button {
      color: initial;
      /* or your default color */
    }

    /* Scrolled styles */
    .scrolled .user-email,
    .scrolled .logout-button {
      color: white !important;
    }

    body {
      font-family: "Montserrat", sans-serif;
      color: #333;
      line-height: 1.6;
      overflow-x: hidden;
      background-color: #f9f9f9;
      padding-top: 70px;
    }

    h1,
    h2,
    h3 {
      font-family: "Playfair Display", serif;
      font-weight: 700;
    }

    a {
      text-decoration: none;
      color: inherit;
    }

    nav {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      background-color: white;
      padding: 15px 5%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      z-index: 1000;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      border-bottom: 1px solid #eee;
      transition: background-color 0.3s ease, padding 0.3s ease;
      height: 90px;
    }

    .nav-left,
    .nav-right {
      display: flex;
      align-items: center;
    }

    nav.scrolled {
      background-color: rgba(32, 43, 29, 1);
      padding: 10px 5%;
    }

    nav.scrolled .logo,
    nav.scrolled .nav-links a,
    nav.scrolled .login-btn {
      color: white;
    }

    nav.scrolled .nav-links a:hover {
      color: #ecd5a6;
    }

    nav.scrolled .search-container i {
      color: #aaa;
    }

    nav.scrolled .search-container input {
      border-color: #444;
      background-color: rgba(255, 255, 255, 0.1);
      color: white;
    }

    nav.scrolled .search-container input::placeholder {
      color: #aaa;
    }

    .logo {
      font-family: "Playfair Display", serif;
      font-size: 1.8rem;
      color: #202b1d;
      font-weight: bold;
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      transition: color 0.3s ease;
    }

    .nav-links {
      display: flex;
      list-style: none;
    }

    .nav-links li {
      margin-left: 20px;
      position: relative;
    }

    .nav-links a {
      color: #333;
      font-weight: 500;
      transition: color 0.3s;
      position: relative;
    }

    .nav-links a:hover {
      color: #202b1d;
    }

    .nav-links a::after {
      content: "";
      position: absolute;
      bottom: -5px;
      left: 0;
      width: 0;
      height: 2px;
      background-color: #000000;
      transition: width 0.3s;
    }

    .nav-links a:hover::after {
      width: 100%;
    }

    nav.scrolled .nav-links a::after {
      background-color: #ecd5a6;
    }

    .search-container {
      position: relative;
      margin-right: 20px;
    }

    .search-container i {
      position: absolute;
      left: 10px;
      top: 50%;
      transform: translateY(-50%);
      color: #777;
      transition: color 0.3s;
    }

    .search-container input {
      padding: 8px 15px 8px 35px;
      border: 1px solid #ddd;
      border-radius: 4px;
      width: 200px;
      transition: all 0.3s;
    }

    .search-container input:focus {
      outline: none;
      border-color: #202b1d;
      width: 250px;
    }

    .auth-buttons {
      display: flex;
      align-items: center;
    }

    .login-btn,
    .signup-btn {
      padding: 8px 15px;
      margin-left: 10px;
      font-weight: 500;
      transition: all 0.3s;
      cursor: pointer;
    }

    .login-btn {
      color: #333;
    }

    .signup-btn {
      background-color: #202b1d;
      color: white;
      border-radius: 4px;
    }

    .signup-btn:hover {
      background-color: #1a2318;
    }

    .mobile-menu-btn {
      display: none;
      background: none;
      border: none;
      color: #333;
      font-size: 1.5rem;
      cursor: pointer;
      transition: color 0.3s;
    }

    nav.scrolled .mobile-menu-btn {
      color: white;
    }

    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.6);
      z-index: 2000;
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      width: 800px;
      height: 600px;
      background-color: #ffffff;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
      position: relative;
      overflow: hidden;
      border-radius: 12px;
      padding: 0;
    }

    .image-panel {
      width: 50%;
      height: 100%;
      background: url("https://images.unsplash.com/photo-1531913764164-f85c52e6e654?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80") no-repeat center center;
      background-size: cover;
      position: absolute;
      top: 0;
      left: 0;
      transition: transform 0.7s ease-in-out;
      z-index: 2;
    }

    .image-panel.signup {
      transform: translateX(100%);
    }

    .form-container {
      width: 100%;
      height: 100%;
      position: relative;
      z-index: 1;
    }

    .login-form,
    .signup-form {
      width: 50%;
      height: 100%;
      padding: 20px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      background-color: #ffffff;
      position: absolute;
      top: 0;
      margin: 0;
      transition: opacity 0.7s ease-in-out;
      box-sizing: border-box;
      overflow-y: auto;
      font-family: "Roboto", sans-serif;
    }

    .login-form {
      right: 0;
      opacity: 1;
    }

    .signup-form {
      left: 0;
      opacity: 0;
      pointer-events: none;
    }

    .login-form.hidden {
      opacity: 0;
      pointer-events: none;
    }

    .signup-form.hidden {
      opacity: 0;
      pointer-events: none;
    }

    .login-form.visible {
      opacity: 1;
      pointer-events: auto;
    }

    .signup-form.visible {
      opacity: 1;
      pointer-events: auto;
    }

    .modal-close {
      position: absolute;
      top: 10px;
      right: 10px;
      background: none;
      border: none;
      font-size: 1.5rem;
      color: #333;
      cursor: pointer;
      z-index: 3;
    }

    h2 {
      font-size: 1.8rem;
      font-weight: 700;
      color: #333;
      margin-bottom: 15px;
      font-family: "Playfair Display", serif;
    }

    .form-group {
      width: 90%;
      margin: 6px 0;
    }

    .form-group.row {
      display: flex;
      gap: 8px;
    }

    .form-group.row input {
      flex: 1;
    }

    input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 1rem;
      box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
      transition: border-color 0.3s ease;
      box-sizing: border-box;
    }

    input:focus {
      outline: none;
      border-color: #202b1d;
      box-shadow: 0 0 5px rgba(32, 43, 29, 0.3);
    }

    button[type="submit"] {
      width: 90%;
      padding: 12px;
      background: linear-gradient(135deg, #202b1d, #1a2318);
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 500;
      cursor: pointer;
      transition: transform 0.2s ease, background 0.3s ease;
      margin-top: 10px;
    }

    button[type="submit"]:hover {
      background: linear-gradient(135deg, #1a2318, #141a12);
      transform: translateY(-2px);
    }

    .toggle-link,
    .forgot-password {
      margin-top: 12px;
      color: #202b1d;
      font-size: 0.95rem;
      cursor: pointer;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .toggle-link:hover,
    .forgot-password:hover {
      color: #ecd5a6;
      text-decoration: underline;
    }

    .checkbox-group {
      width: 90%;
      display: flex;
      align-items: center;
      margin: 8px 0;
    }

    .checkbox-group input {
      width: auto;
      margin-right: 10px;
    }

    .checkbox-group label {
      font-size: 0.95rem;
      color: #333;
    }

    section {
      min-height: 100vh;
      padding: 80px 5%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }

    .section-title {
      font-size: 2.5rem;
      margin-bottom: 2rem;
      text-align: center;
      position: relative;
      opacity: 0;
      transform: translateY(20px);
      transition: opacity 0.8s, transform 0.8s;
    }

    .section-title.visible {
      opacity: 1;
      transform: translateY(0);
    }

    .section-title::after {
      content: "";
      display: block;
      width: 80px;
      height: 3px;
      background: #ecd5a6;
      margin: 15px auto;
    }

    #hero {
      background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
        url("https://i.pinimg.com/736x/47/f9/78/47f9784cb6d8f4eaf617cb799d43abbc.jpg") no-repeat center center/cover;
      color: white;
      text-align: center;
      justify-content: center;
      align-items: center;
    }

    .hero-content {
      max-width: 800px;
      margin: 0 auto;
      opacity: 0;
      transform: translateY(30px);
      transition: opacity 1s, transform 1s;
    }

    .hero-content.visible {
      opacity: 1;
      transform: translateY(0);
    }

    #hero h1 {
      font-size: 4rem;
      margin-bottom: 1rem;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    #hero p {
      font-size: 1.5rem;
      margin-bottom: 2rem;
      text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
    }

    .btn {
      display: inline-block;
      padding: 12px 30px;
      background: #ecd5a6;
      color: #202b1d;
      border-radius: 4px;
      font-weight: bold;
      transition: all 0.3s;
      border: none;
      cursor: pointer;
    }

    .btn:hover {
      background: #e0c893;
      transform: translateY(-2px);
    }

    .scroll-prompt {
      position: absolute;
      bottom: 30px;
      left: 50%;
      transform: translateX(-50%);
      color: white;
      font-size: 1rem;
      animation: bounce 2s infinite;
      opacity: 0;
      transition: opacity 1s 0.5s;
    }

    .scroll-prompt.visible {
      opacity: 1;
    }

    @keyframes bounce {

      0%,
      20%,
      50%,
      80%,
      100% {
        transform: translateY(0) translateX(-50%);
      }

      40% {
        transform: translateY(-20px) translateX(-50%);
      }

      60% {
        transform: translateY(-10px) translateX(-50%);
      }
    }

    #features {
      background-color: white;
    }

    .features-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
      margin-top: 50px;
    }

    .feature-card {
      flex: 1 1 300px;
      max-width: 350px;
      padding: 30px;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      text-align: center;
      transition: transform 0.3s, box-shadow 0.3s;
      opacity: 0;
      transform: translateY(20px);
    }

    .feature-card.visible {
      opacity: 1;
      transform: translateY(0);
    }

    .feature-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }

    .feature-icon {
      font-size: 3rem;
      color: #ecd5a6;
      margin-bottom: 20px;
    }

    .feature-card h3 {
      margin-bottom: 15px;
      font-size: 1.5rem;
    }

    .carousel-container {
      max-width: 1200px;
      width: 100%;
      margin: 0 auto;
      text-align: center;
    }

    .carousel-container h1 {
      margin-bottom: 30px;
      color: #333;
    }

    .carousel {
      position: relative;
      width: 100%;
      overflow: hidden;
      border-radius: 10px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
      height: 700px;
    }

    .carousel-inner {
      display: flex;
      transition: transform 0.5s ease;
      height: 100%;
    }

    .carousel-card {
      min-width: 100%;
      height: 100%;
      padding: 20px;
      transition: all 0.3s ease;
      display: flex;
      flex-direction: column;
    }

    .carousel-card img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .carousel-control {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background-color: rgba(255, 255, 255, 0.7);
      border: none;
      color: #333;
      font-size: 2rem;
      padding: 10px 15px;
      border-radius: 50%;
      cursor: pointer;
      z-index: 10;
      transition: all 0.3s ease;
    }

    .carousel-control:hover {
      background-color: rgba(255, 255, 255, 0.9);
    }

    .carousel-indicators {
      display: flex;
      justify-content: center;
      margin-top: 20px;
    }

    .indicator {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background-color: #bbb;
      margin: 0 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .indicator.active {
      background-color: #333;
    }

    @media (max-width: 768px) {
      .carousel {
        height: 450px;
      }
    }

    @media (max-width: 480px) {
      .carousel {
        height: 350px;
      }
    }

    /* Gallery Section Styles */
    #gallery {
      background-color: #f8f8f8;
      padding: 80px 5%;
    }

    .gallery-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      flex-wrap: wrap;
    }

    .gallery-controls {
      display: flex;
      gap: 20px;
      align-items: center;
    }

    .filter-dropdown,
    .sort-options {
      position: relative;
    }

    .filter-dropdown select,
    .sort-options select {
      padding: 10px 35px 10px 15px;
      border: 1px solid #ddd;
      border-radius: 6px;
      background-color: white;
      appearance: none;
      font-family: "Montserrat", sans-serif;
      cursor: pointer;
      min-width: 180px;
    }

    .filter-dropdown i {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      pointer-events: none;
      color: #666;
    }

    .sort-options {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .sort-options span {
      color: #666;
      font-size: 0.9rem;
    }

    .artworks-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 30px;
      margin: 0 auto;
    }

    .artwork-card {
      background: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
      position: relative;
    }

    .artwork-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .artwork-image-container {
      position: relative;
      width: 100%;
      height: 250px;
      overflow: hidden;
    }

    .artwork-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
    }

    .artwork-card:hover .artwork-image {
      transform: scale(1.05);
    }

    .quick-view {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      background: rgba(0, 0, 0, 0.7);
      color: white;
      text-align: center;
      padding: 10px;
      transform: translateY(100%);
      transition: transform 0.3s ease;
      cursor: pointer;
    }

    .artwork-card:hover .quick-view {
      transform: translateY(0);
    }

    .artwork-actions {
      position: absolute;
      top: 15px;
      right: 15px;
      display: flex;
      flex-direction: column;
      gap: 10px;
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .artwork-card:hover .artwork-actions {
      opacity: 1;
    }

    .wishlist-btn,
    .zoom-btn {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: white;
      border: none;
      color: #333;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .wishlist-btn:hover,
    .zoom-btn:hover {
      background: #202b1d;
      color: white;
    }

    .artwork-info {
      padding: 20px;
    }

    .artwork-info h3 {
      font-size: 1.2rem;
      margin-bottom: 5px;
      color: #333;
    }

    .artist-name {
      color: #666;
      font-size: 0.9rem;
      margin-bottom: 10px;
    }

    .price-rating {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }

    .price {
      font-weight: 600;
      color: #202b1d;
      font-size: 1.1rem;
    }

    .rating {
      color: #666;
      font-size: 0.9rem;
    }

    .rating i {
      color: #ecd5a6;
      margin-right: 3px;
    }

    .buy-btn {
      width: 100%;
      padding: 10px;
      background: #202b1d;
      color: white;
      border: none;
      border-radius: 6px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .buy-btn:hover {
      background: #1a2318;
    }

    .gallery-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 40px;
      flex-wrap: wrap;
      gap: 20px;
    }

    .load-more-btn {
      padding: 10px 25px;
      background: white;
      border: 1px solid #ddd;
      border-radius: 6px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .load-more-btn:hover {
      background: #f0f0f0;
    }

    .pagination {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .page-btn,
    .next-btn {
      width: 40px;
      height: 40px;
      border-radius: 6px;
      border: 1px solid #ddd;
      background: white;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .page-btn.active {
      background: #202b1d;
      color: white;
      border-color: #202b1d;
    }

    .page-btn:hover:not(.active),
    .next-btn:hover {
      border-color: #202b1d;
      color: #202b1d;
    }

    .next-btn {
      width: auto;
      padding: 0 15px;
      gap: 5px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .gallery-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
      }

      .gallery-controls {
        width: 100%;
        flex-direction: column;
        gap: 10px;
      }

      .filter-dropdown,
      .sort-options {
        width: 100%;
      }

      .filter-dropdown select,
      .sort-options select {
        width: 100%;
      }

      .gallery-footer {
        justify-content: center;
      }

      .pagination {
        margin-top: 20px;
      }
    }

    #testimonials {
      background: linear-gradient(rgba(255, 255, 255, 0.9),
          rgba(255, 255, 255, 0.9)),
        url("https://images.unsplash.com/photo-1531685250784-7569952593d2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1974&q=80") no-repeat center center/cover;
      background-blend-mode: overlay;
    }

    .testimonials-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
      margin-top: 50px;
    }

    .testimonial-card {
      flex: 1 1 300px;
      max-width: 350px;
      padding: 30px;
      background: white;
      border-radius: 8px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      opacity: 0;
      transform: translateY(20px);
      transition: opacity 1s, transform 1s;
    }

    .testimonial-card.visible {
      opacity: 1;
      transform: translateY(0);
    }

    .testimonial-header {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
    }

    .testimonial-avatar {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 15px;
    }

    .testimonial-author h4 {
      font-size: 1.2rem;
      margin-bottom: 5px;
    }

    .stars {
      color: #ecd5a6;
      font-size: 0.9rem;
      margin-bottom: 5px;
    }

    .testimonial-text {
      font-style: italic;
      color: #555;
    }

    footer {
      background: #202b1d;
      color: white;
      padding: 50px 5% 30px;
      text-align: center;
    }

    .footer-content {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
      max-width: 1200px;
      margin: 0 auto;
      gap: 30px;
    }

    .footer-section {
      flex: 1 1 300px;
      text-align: left;
      margin-bottom: 30px;
    }

    .footer-section h3 {
      font-size: 1.5rem;
      margin-bottom: 20px;
      color: #ecd5a6;
    }

    .social-icons {
      display: flex;
      gap: 15px;
      margin-top: 20px;
    }

    .social-icons a {
      display: inline-block;
      width: 40px;
      height: 40px;
      background: #333;
      border-radius: 50%;
      text-align: center;
      line-height: 40px;
      transition: background 0.3s;
    }

    .social-icons a:hover {
      background: #ecd5a6;
      color: #202b1d;
    }

    .newsletter-form {
      display: flex;
      margin-top: 20px;
    }

    .newsletter-form input {
      flex: 1;
      padding: 10px;
      border: none;
      border-radius: 4px 0 0 4px;
    }

    .newsletter-form button {
      padding: 10px 15px;
      background: #ecd5a6;
      color: #202b1d;
      border: none;
      border-radius: 0 4px 4px 0;
      cursor: pointer;
      transition: background 0.3s;
    }

    .newsletter-form button:hover {
      background: #e0c893;
    }

    .back-to-top {
      position: fixed;
      bottom: 30px;
      right: 30px;
      width: 50px;
      height: 50px;
      background: #ecd5a6;
      color: #202b1d;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s;
      z-index: 100;
    }

    .back-to-top.visible {
      opacity: 1;
      visibility: visible;
    }

    .back-to-top:hover {
      background: #e0c893;
    }

    .copyright {
      margin-top: 50px;
      padding-top: 20px;
      border-top: 1px solid #444;
      font-size: 0.9rem;
      color: #aaa;
    }

    @media (max-width: 992px) {
      .modal-content {
        width: 90%;
        height: auto;
        min-height: 500px;
      }

      .search-container input {
        width: 150px;
      }

      .search-container input:focus {
        width: 180px;
      }
    }

    @media (max-width: 768px) {
      body {
        padding-top: 60px;
      }

      nav {
        padding: 10px 5%;
      }

      .logo {
        font-size: 1.5rem;
      }

      .nav-links {
        position: fixed;
        top: 60px;
        left: 0;
        width: 100%;
        background-color: #202b1d;
        flex-direction: column;
        align-items: center;
        padding: 20px 0;
        transform: translateY(-100%);
        opacity: 0;
        transition: all 0.3s;
        pointer-events: none;
      }

      .nav-links.active {
        transform: translateY(0);
        opacity: 1;
        pointer-events: all;
      }

      .nav-links li {
        margin: 15px 0;
      }

      .nav-links a {
        color: white;
      }

      .nav-links a:hover {
        color: #ecd5a6;
      }

      .mobile-menu-btn {
        display: block;
      }

      #hero h1 {
        font-size: 2.5rem;
      }

      #hero p {
        font-size: 1.2rem;
      }

      .section-title {
        font-size: 2rem;
      }

      .feature-card,
      .testimonial-card {
        flex: 1 1 100%;
      }

      /* Add this to your existing styles */
      .artworks-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
        margin: 0 auto;
        width: 100%;
        overflow: hidden;
        /* Prevent horizontal overflow */
      }

      /* Ensure artwork cards don't exceed their container */
      .artwork-card {
        width: 100%;
        max-width: 100%;
      }

      .login-form,
      .signup-form {
        padding: 15px;
      }

      .form-group,
      input,
      button {
        width: 95%;
      }

      .form-group.row {
        flex-direction: column;
        gap: 6px;
      }
    }

    @media (max-width: 480px) {
      #hero h1 {
        font-size: 2rem;
      }

      .section-title {
        font-size: 1.8rem;
      }

      .footer-section {
        text-align: center;
      }

      .newsletter-form {
        flex-direction: column;
      }

      .newsletter-form input {
        border-radius: 4px;
        margin-bottom: 10px;
      }

      .newsletter-form button {
        border-radius: 4px;
      }
    }
  </style>
</head>

<body>
  <nav id="navbar">
    <div class="nav-left">
      <ul class="nav-links">
        <li><a href="#hero">Home</a></li>
        <li><a href="#features">Features</a></li>
        <li><a href="#gallery">Gallery</a></li>
        <li><a href="Pages/Marketplace.php" class="marketplace-link">Marketplace</a></li>
        <li><a href="#about">About Us</a></li>
      </ul>
    </div>
    <div class="logo">VirtuGallery</div>
    <div class="nav-right">
      <div class="search-container">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search..." />
      </div>
      <div class="auth-buttons">
        <a class="login-btn" onclick="showModal('login')">Login</a>
        <a class="signup-btn" onclick="showModal('signup')">Sign up</a>
      </div>
      <button class="mobile-menu-btn">
        <i class="fas fa-bars"></i>
      </button>
    </div>
  </nav>

  <!-- Modal for Login/Signup -->
  <div class="modal" id="auth-modal">
    <div class="modal-content">
      <button class="modal-close" onclick="closeModal()">×</button>
      <div class="image-panel"></div>
      <div class="form-container">
        <!-- Login Form -->
        <div class="login-form visible">
          <h2>Login</h2>
          <form onsubmit="return false;">
            <div class="form-group">
              <input type="text" placeholder="Email or Username" required />
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" required />
            </div>
            <div class="checkbox-group">
              <input type="checkbox" id="remember-me" />
              <label for="remember-me">Remember Me</label>
            </div>
            <button type="submit">Login</button>
          </form>
          <a href="#" class="forgot-password">Forgot Password?</a>
          <span class="toggle-link" onclick="showSignup()">Don't have an account? Sign Up</span>
        </div>

        <!-- Signup Form -->
        <div class="signup-form hidden">
          <h2>Sign Up</h2>
          <form onsubmit="return false;">
            <div class="form-group row">
              <input type="text" placeholder="First Name" required />
              <input type="text" placeholder="Middle Name" />
            </div>
            <div class="form-group row">
              <input type="text" placeholder="Last Name" required />
              <input type="text" placeholder="Suffix" />
            </div>
            <div class="form-group">
              <input type="email" placeholder="Email Address" required />
            </div>
            <div class="form-group">
              <input
                type="password"
                placeholder="Password"
                required
                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                title="Must contain at least one number, one uppercase and lowercase letter, and at least 8 characters" />
            </div>
            <div class="form-group">
              <input
                type="password"
                placeholder="Confirm Password"
                required />
            </div>
            <div class="form-group">
              <input type="text" placeholder="Username (Optional)" />
            </div>
            <div class="form-group row">
              <input type="text" placeholder="Municipality" required />
              <input type="text" placeholder="Province" required />
            </div>
            <button type="submit">Sign Up</button>
          </form>
          <span class="toggle-link" onclick="showLogin()">Already have an account? Login</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Hero Section -->
  <section id="hero">
    <div class="hero-content">
      <h1>Discover Art That Speaks to You</h1>
      <p>Explore, Collect, and Own Masterpieces</p>
      <a href="Pages/home.php" class="btn">Begin Your Journey</a>
    </div>
    <div class="scroll-prompt">
      <span>Scroll Down</span>
      <i class="fas fa-chevron-down"></i>
    </div>
  </section>

  <!-- Features Section -->
  <section id="features">
    <h2 class="section-title">Why Choose Our Gallery?</h2>
    <div class="features-container">
      <div class="feature-card">
        <div class="feature-icon">
          <i class="fas fa-star"></i>
        </div>
        <h3>Curated Collections</h3>
        <p>
          Our expert curators handpick each artwork to ensure only the highest
          quality pieces make it to our gallery.
        </p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">
          <i class="fas fa-lock"></i>
        </div>
        <h3>Secure Purchases</h3>
        <p>
          Your transactions are protected with bank-level security for
          complete peace of mind.
        </p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">
          <i class="fas fa-globe"></i>
        </div>
        <h3>Local Shipping</h3>
        <p>
          We deliver artworks nationwide with specialized packaging to ensure
          they arrive in perfect condition.
        </p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">
          <i class="fas fa-paint-brush"></i>
        </div>
        <h3>Artist Spotlights</h3>
        <p>
          Discover emerging talents and established masters through our
          featured artist program.
        </p>
      </div>
    </div>
    <div class="carousel-container">
    <br>
    <br>
    <br>
    <br>
    <br>
      <div class="carousel">
        <div class="carousel-inner"></div>
        <div class="carousel-indicators"></div>
      </div>
    </div>
  </section>

  <!-- Gallery Section -->
  <section id="gallery">
    <div class="gallery-header">
      <h2 class="section-title">Trending Masterpieces</h2>
      <div class="gallery-controls">
        <div class="filter-dropdown">
          <select id="category-filter">
            <option value="all">All Categories</option>
            <option value="painting">Paintings</option>
            <option value="sculpture">Sculptures</option>
            <option value="photography">Photography</option>
          </select>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="sort-options">
          <span>Sort by:</span>
          <select id="sort-by">
            <option value="popular">Most Popular</option>
            <option value="newest">Newest</option>
            <option value="price-low">Price: Low to High</option>
            <option value="price-high">Price: High to Low</option>
          </select>
        </div>
      </div>
    </div>

    <div class="artworks-grid">
      <div class="artwork-card" data-category="painting" data-price="2400">
        <div class="artwork-image-container">
          <img
            src="https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1945&q=80"
            alt="Abstract painting"
            class="artwork-image" />
          <div class="quick-view">Quick View</div>
          <div class="artwork-actions">
            <button class="wishlist-btn" title="Add to wishlist"><i class="far fa-heart"></i></button>
            <button class="zoom-btn" title="Zoom"><i class="fas fa-search-plus"></i></button>
          </div>
        </div>
        <div class="artwork-info">
          <h3>Whispers of Color</h3>
          <p class="artist-name">by Elena Rodriguez</p>
          <div class="price-rating">
            <span class="price">$2,400</span>
            
          </div>
          <button class="buy-btn">Add to Cart</button>
        </div>
      </div>

      <div class="artwork-card" data-category="sculpture" data-price="3800">
        <div class="artwork-image-container">
          <img
            src="https://images.unsplash.com/photo-1578926375605-eaf7559b1458?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1986&q=80"
            alt="Modern sculpture"
            class="artwork-image"=/>
          <div class="quick-view">Quick View</div>
          <div class="artwork-actions">
            <button class="wishlist-btn" title="Add to wishlist"><i class="far fa-heart"></i></button>
            <button class="zoom-btn" title="Zoom"><i class="fas fa-search-plus"></i></button>
          </div>
        </div>
        <div class="artwork-info">
          <h3>Eternal Forms</h3>
          <p class="artist-name">by Marcus Chen</p>
          <div class="price-rating">
            <span class="price">$3,800</span>
            
          </div>
          <button class="buy-btn">Add to Cart</button>
        </div>
      </div>

      <div class="artwork-card" data-category="painting" data-price="1950">
        <div class="artwork-image-container">
          <img
            src="https://images.unsplash.com/photo-1531913764164-f85c52e6e654?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1932&q=80"
            alt="Portrait painting"
            class="artwork-image" />
          <div class="quick-view">Quick View</div>
          <div class="artwork-actions">
            <button class="wishlist-btn" title="Add to wishlist"><i class="far fa-heart"></i></button>
            <button class="zoom-btn" title="Zoom"><i class="fas fa-search-plus"></i></button>
          </div>
        </div>
        <div class="artwork-info">
          <h3>Silent Gaze</h3>
          <p class="artist-name">by Aisha Johnson</p>
          <div class="price-rating">
            <span class="price">$1,950</span>
            
          </div>
          <button class="buy-btn">Add to Cart</button>
        </div>
      </div>

      <div class="artwork-card" data-category="photography" data-price="2800">
        <div class="artwork-image-container">
          <img
            src="https://images.unsplash.com/photo-1558403194-611308249627?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
            alt="Landscape painting"
            class="artwork-image" />
          <div class="quick-view">Quick View</div>
          <div class="artwork-actions">
            <button class="wishlist-btn" title="Add to wishlist"><i class="far fa-heart"></i></button>
            <button class="zoom-btn" title="Zoom"><i class="fas fa-search-plus"></i></button>
          </div>
        </div>
        <div class="artwork-info">
          <h3>Mountain Dreams</h3>
          <p class="artist-name">by Thomas Wright</p>
          <div class="price-rating">
            <span class="price">$2,800</span>
            
          </div>
          <button class="buy-btn">Add to Cart</button>
        </div>
      </div>

      <div class="artwork-card" data-category="digital" data-price="1250">
        <div class="artwork-image-container">
          <img
            src="https://images.unsplash.com/photo-1626785774573-4b799315345d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2071&q=80"
            alt="Digital art"
            class="artwork-image" />
          <div class="quick-view">Quick View</div>
          <div class="artwork-actions">
            <button class="wishlist-btn" title="Add to wishlist"><i class="far fa-heart"></i></button>
            <button class="zoom-btn" title="Zoom"><i class="fas fa-search-plus"></i></button>
          </div>
        </div>
        <div class="artwork-info">
          <h3>Digital Dreams</h3>
          <p class="artist-name">by Sam Wilson</p>
          <div class="price-rating">
            <span class="price">$1,250</span>
            
          </div>
          <button class="buy-btn">Add to Cart</button>
        </div>
      </div>

      <div class="artwork-card" data-category="painting" data-price="3200">
        <div class="artwork-image-container">
          <img
            src="https://images.unsplash.com/photo-1579547945413-497e1b99dac0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
            alt="Abstract painting"
            class="artwork-image" />
          <div class="quick-view">Quick View</div>
          <div class="artwork-actions">
            <button class="wishlist-btn" title="Add to wishlist"><i class="far fa-heart"></i></button>
            <button class="zoom-btn" title="Zoom"><i class="fas fa-search-plus"></i></button>
          </div>
        </div>
        <div class="artwork-info">
          <h3>Color Symphony</h3>
          <p class="artist-name">by Maria Gonzalez</p>
          <div class="price-rating">
            <span class="price">$3,200</span>
          </div>
          <button class="buy-btn">Add to Cart</button>
        </div>
      </div>
    </div>

    <div class="gallery-footer">
      <button class="load-more-btn">Load More</button>
      <div class="pagination">
        <button class="page-btn active">1</button>
        <button class="page-btn">2</button>
        <button class="page-btn">3</button>
        <span>...</span>
        <button class="page-btn">5</button>
        <button class="next-btn">Next <i class="fas fa-chevron-right"></i></button>
      </div>
    </div>
  </section>

  <!-- Testimonials Section -->
  <section id="testimonials">
    <h2 class="section-title">What Collectors Say</h2>
    <div class="testimonials-container">
      <div class="testimonial-card">
        <div class="testimonial-header">
          <img
            src="https://randomuser.me/api/portraits/men/45.jpg"
            alt="Sarah Johnson"
            class="testimonial-avatar" />
          <div class="testimonial-author">
            <h4>Sir Kevin Daryl Calica</h4>
            <div class="stars">★★★★</div>
          </div>
        </div>
        <p class="testimonial-text">
          "I think for me yes, it helps a lot. thank you..."
        </p>
      </div>
      <div class="testimonial-card">
        <div class="testimonial-header">
          <img
            src="https://randomuser.me/api/portraits/women/32.jpg"
            alt="Michael Chen"
            class="testimonial-avatar" />
          <div class="testimonial-author">
            <h4>Shanaya Concibido</h4>
            <div class="stars">★★★★★</div>
          </div>
        </div>
        <p class="testimonial-text">
          "I've discovered so many incredible emerging artists through this
          platform. The quality of artworks is consistently outstanding."
        </p>
      </div>
      <div class="testimonial-card">
        <div class="testimonial-header">
          <img
            src="https://randomuser.me/api/portraits/women/68.jpg"
            alt="Priya Patel"
            class="testimonial-avatar" />
          <div class="testimonial-author">
            <h4>Topher Hilairon</h4>
            <div class="stars">★★★★★</div>
          </div>
        </div>
        <p class="testimonial-text">
          "The local shipping was incredibly careful and professional. My artwork
          arrived in perfect condition, beautifully packaged. Will definitely
          buy again!"
        </p>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="footer-content">
      <div class="footer-section">
        <h3>VirtuGallery</h3>
        <p>
          Discover and collect exceptional artworks from around the world. Our
          mission is to connect art lovers with talented artists.
        </p>
        <div class="social-icons">
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-pinterest"></i></a>
        </div>
      </div>
      <div class="footer-section">
        <h3>Quick Links</h3>
        <ul>
          <li><a href="#hero">Home</a></li>
          <li><a href="#features">Features</a></li>
          <li><a href="#gallery">Gallery</a></li>
          <li><a href="#testimonials">Testimonials</a></li>
          <li><a href="#">About Us</a></li>
        </ul>
      </div>
      <div class="footer-section">
        <h3>Newsletter</h3>
        <p>
          Subscribe to receive updates on new arrivals, exclusive offers, and
          artist spotlights.
        </p>
        <form class="newsletter-form">
          <input type="email" placeholder="Your email address" />
          <button type="submit">Subscribe</button>
        </form>
      </div>
    </div>
    <div class="copyright">
      <p>© 2025 VirtuGallery | Designed by Creative Minds</p>
    </div>
  </footer>

  <a href="#hero" class="back-to-top">
    <i class="fas fa-arrow-up"></i>
  </a>

  <script>
    document.addEventListener("DOMContentLoaded", function() {

      window.addEventListener('scroll', function() {
        const header = document.querySelector('header');
        header.classList.toggle('scrolled', window.scrollY > 50);
      });


      const galleryContainer = document.querySelector('.artworks-grid');
      if (galleryContainer) {
        galleryContainer.style.display = 'grid';
        galleryContainer.style.gridTemplateColumns = 'repeat(auto-fill, minmax(280px, 1fr))';
        galleryContainer.style.gap = '30px';
        galleryContainer.style.width = '100%';
      }
      // Check aut  hentication status on page load
      fetch("check_auth.php")
        .then((response) => response.json())
        .then((data) => {
          isLoggedIn = data.isLoggedIn;
          if (isLoggedIn) {
            updateNavForLoggedIn(data.email);
          }
        });

      function handleSignup() {
        const formData = {
          first_name: document.querySelector('.signup-form input[placeholder="First Name"]').value,
          middle_name: document.querySelector('.signup-form input[placeholder="Middle Name"]').value,
          last_name: document.querySelector('.signup-form input[placeholder="Last Name"]').value,
          suffix: document.querySelector('.signup-form input[placeholder="Suffix"]').value,
          email: document.querySelector('.signup-form input[placeholder="Email Address"]').value,
          password: document.querySelector('.signup-form input[placeholder="Password"]').value,
          confirm_password: document.querySelector('.signup-form input[placeholder="Confirm Password"]').value,
          username: document.querySelector('.signup-form input[placeholder="Username (Optional)"]').value,
          municipality: document.querySelector('.signup-form input[placeholder="Municipality"]').value,
          province: document.querySelector('.signup-form input[placeholder="Province"]').value
        };

        fetch('register.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
          })
          .then(response => {
            // First check if the response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
              return response.text().then(text => {
                throw new Error(`Expected JSON but got: ${text}`);
              });
            }
            return response.json();
          })
          .then(data => {
            if (data.success) {
              // Handle success
            } else {
              alert(data.errors.join('\n'));
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Registration failed. Please check the console for details.');
          });
      }

      // Navigation bar scroll effect
      const navbar = document.getElementById("navbar");
      window.addEventListener("scroll", function() {
        if (window.scrollY > 50) {
          navbar.classList.add("scrolled");
        } else {
          navbar.classList.remove("scrolled");
        }
      });

      // Mobile menu toggle
      const mobileMenuBtn = document.querySelector(".mobile-menu-btn");
      const navLinks = document.querySelector(".nav-links");
      mobileMenuBtn.addEventListener("click", function() {
        navLinks.classList.toggle("active");
        const icon = mobileMenuBtn.querySelector("i");
        if (navLinks.classList.contains("active")) {
          icon.classList.remove("fa-bars");
          icon.classList.add("fa-times");
        } else {
          icon.classList.remove("fa-times");
          icon.classList.add("fa-bars");
        }
      });

      // Close mobile menu when clicking a link
      document.querySelectorAll(".nav-links a").forEach((link) => {
        link.addEventListener("click", function() {
          if (navLinks.classList.contains("active")) {
            navLinks.classList.remove("active");
            const icon = mobileMenuBtn.querySelector("i");
            icon.classList.remove("fa-times");
            icon.classList.add("fa-bars");
          }
        });
      });

      // Modal functions
      function showModal(formType) {
        const modal = document.getElementById("auth-modal");
        modal.style.display = "flex";
        if (formType === "signup") {
          showSignup();
        } else {
          showLogin();
        }
      }

      function closeModal() {
        const modal = document.getElementById("auth-modal");
        modal.style.display = "none";
        redirectAfterLogin = null; // Reset redirect
      }

      function showSignup() {
        document.querySelector(".image-panel").classList.add("signup");
        document.querySelector(".login-form").classList.remove("visible");
        document.querySelector(".login-form").classList.add("hidden");
        document.querySelector(".signup-form").classList.remove("hidden");
        document.querySelector(".signup-form").classList.add("visible");
      }

      function showLogin() {
        document.querySelector(".image-panel").classList.remove("signup");
        document.querySelector(".signup-form").classList.remove("visible");
        document.querySelector(".signup-form").classList.add("hidden");
        document.querySelector(".login-form").classList.remove("hidden");
        document.querySelector(".login-form").classList.add("visible");
      }

      // Handle login
      function handleLogin() {
        const emailOrUsername = document.querySelector(
          '.login-form input[type="text"]'
        ).value;
        const password = document.querySelector(
          '.login-form input[type="password"]'
        ).value;
        const rememberMe = document.querySelector("#remember-me").checked;

        fetch("login.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/json"
            },
            body: JSON.stringify({
              email_or_username: emailOrUsername,
              password: password,
              remember_me: rememberMe,
            }),
          })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              isLoggedIn = true;
              updateNavForLoggedIn(data.email);
              closeModal();
              if (redirectAfterLogin) {
                window.location.href = redirectAfterLogin;
              } else {
                window.location.href = "Pages/home.php";
              }
            } else {
              alert(data.errors.join("\n"));
            }
          })
          .catch((error) => {
            alert("An error occurred during login: " + error.message);
          });
      }

      // Handle signup
      function handleSignup() {
        const formData = {
          first_name: document.querySelector(
            '.signup-form input[placeholder="First Name"]'
          ).value,
          middle_name: document.querySelector(
            '.signup-form input[placeholder="Middle Name"]'
          ).value,
          last_name: document.querySelector(
            '.signup-form input[placeholder="Last Name"]'
          ).value,
          suffix: document.querySelector(
            '.signup-form input[placeholder="Suffix"]'
          ).value,
          email: document.querySelector(
            '.signup-form input[placeholder="Email Address"]'
          ).value,
          password: document.querySelector(
            '.signup-form input[placeholder="Password"]'
          ).value,
          confirm_password: document.querySelector(
            '.signup-form input[placeholder="Confirm Password"]'
          ).value,
          username: document.querySelector(
            '.signup-form input[placeholder="Username (Optional)"]'
          ).value,
          municipality: document.querySelector(
            '.signup-form input[placeholder="Municipality"]'
          ).value,
          province: document.querySelector(
            '.signup-form input[placeholder="Province"]'
          ).value,
        };

        fetch("register.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/json"
            },
            body: JSON.stringify(formData),
          })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              isLoggedIn = true;
              updateNavForLoggedIn(formData.email);
              closeModal();
              if (redirectAfterLogin) {
                window.location.href = redirectAfterLogin;
              } else {
                window.location.href = "Pages/home.php";
              }
            } else {
              alert(data.errors.join("\n"));
            }
          })
          .catch((error) => {
            alert("An error occurred during signup: " + error.message);
          });
      }

      // Update navigation bar for logged-in user
      function updateNavForLoggedIn(email) {
        const authButtons = document.querySelector(".auth-buttons");
        authButtons.innerHTML = `
        <span class="user-email">${email}</span>
        <button class="logout-button" onclick="handleLogout()">Logout</button>
    `;

        // Add styles to the logout button to match your design
        const logoutBtn = document.querySelector(".logout-button");
        logoutBtn.style.padding = "8px 15px";
        logoutBtn.style.marginLeft = "10px";
        logoutBtn.style.color = "#333";
        logoutBtn.style.fontWeight = "500";
        logoutBtn.style.cursor = "pointer";
        logoutBtn.style.background = "none";
        logoutBtn.style.border = "none";
        logoutBtn.style.fontFamily = "inherit";
        logoutBtn.style.fontSize = "inherit";
      }

      // Handle logout
      // Update the handleLogout function in your script
      function handleLogout() {
        fetch("logout.php")
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              // Redirect to index.php after successful logout
              window.location.href = "index.php";
            } else {
              alert("Logout failed. Please try again.");
            }
          })
          .catch((error) => {
            console.error("Error during logout:", error);
            alert("An error occurred during logout.");
          });
      }

      // Expose functions to global scope for onclick
      window.showModal = showModal;
      window.closeModal = closeModal;
      window.showSignup = showSignup;
      window.showLogin = showLogin;
      window.handleLogin = handleLogin;
      window.handleSignup = handleSignup;
      window.handleLogout = handleLogout;

      // Handle Marketplace link click
      let redirectAfterLogin = null;
      let isLoggedIn = false;
      const marketplaceLink = document.querySelector(".marketplace-link");
      marketplaceLink.addEventListener("click", function(e) {
        e.preventDefault();
        if (isLoggedIn) {
          window.location.href = "Pages/Marketplace.php";
        } else {
          redirectAfterLogin = "Pages/Marketplace.php";
          showModal("login");
        }
      });

      // Scroll reveal animation
      const animateOnScroll = function() {
        const elements = document.querySelectorAll(
          ".section-title, .hero-content, .scroll-prompt, .feature-card, .artwork-card, .testimonial-card, .view-all"
        );
        elements.forEach((element) => {
          const elementPosition = element.getBoundingClientRect().top;
          const screenPosition = window.innerHeight / 1.2;
          if (elementPosition < screenPosition) {
            element.classList.add("visible");
          }
        });
      };

      window.addEventListener("scroll", animateOnScroll);
      animateOnScroll();

      // Back to top button
      const backToTopButton = document.querySelector(".back-to-top");
      window.addEventListener("scroll", function() {
        if (window.pageYOffset > 300) {
          backToTopButton.classList.add("visible");
        } else {
          backToTopButton.classList.remove("visible");
        }
      });

      backToTopButton.addEventListener("click", function(e) {
        e.preventDefault();
        window.scrollTo({
          top: 0,
          behavior: "smooth"
        });
      });

      // Smooth scrolling for anchor links (excluding marketplace)
      document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        if (!anchor.classList.contains("marketplace-link")) {
          anchor.addEventListener("click", function(e) {
            e.preventDefault();
            const targetId = this.getAttribute("href");
            if (targetId === "#") return;
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
              targetElement.scrollIntoView({
                behavior: "smooth"
              });
            }
          });
        }
      });

      // Update form submit handlers
      document
        .querySelector('.login-form button[type="submit"]')
        .addEventListener("click", handleLogin);
      document
        .querySelector('.signup-form button[type="submit"]')
        .addEventListener("click", handleSignup);

      // Carousel logic (unchanged from original)
      const images = [{
          url: "https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80",
        },
        {
          url: "https://images.unsplash.com/photo-1578926375605-eaf7559b1458?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80",
        },
        {
          url: "https://images.unsplash.com/photo-1531913764164-f85c52e6e654?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80",
        },
        {
          url: "https://images.unsplash.com/photo-1558403194-611308249627?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80",
        },
        {
          url: "https://images.unsplash.com/photo-1564501049412-61c2a3083791?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80",
        },
      ];

      const carouselInner = document.querySelector(".carousel-inner");
      const indicatorsContainer = document.querySelector(
        ".carousel-indicators"
      );
      let currentIndex = 0;
      let autoSlideInterval;
      const slideIntervalTime = 2000;

      images.forEach((image, index) => {
        const card = document.createElement("div");
        card.className = "carousel-card";
        card.innerHTML = `<img src="${image.url}">`;
        carouselInner.appendChild(card);

        const indicator = document.createElement("div");
        indicator.className = "indicator";
        if (index === 0) indicator.classList.add("active");
        indicator.addEventListener("click", () => goToSlide(index));
        indicatorsContainer.appendChild(indicator);
      });

      function updateCarousel() {
        carouselInner.style.transform = `translateX(-${currentIndex * 100}%)`;
        document
          .querySelectorAll(".indicator")
          .forEach((indicator, index) => {
            indicator.classList.toggle("active", index === currentIndex);
          });
      }

      function nextSlide() {
        currentIndex = (currentIndex + 1) % images.length;
        updateCarousel();
        resetAutoSlide();
      }

      function prevSlide() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        updateCarousel();
        resetAutoSlide();
      }

      function goToSlide(index) {
        currentIndex = index;
        updateCarousel();
        resetAutoSlide();
      }

      function startAutoSlide() {
        autoSlideInterval = setInterval(nextSlide, slideIntervalTime);
      }

      function resetAutoSlide() {
        clearInterval(autoSlideInterval);
        startAutoSlide();
      }

      startAutoSlide();

      carouselInner.addEventListener("mouseenter", () =>
        clearInterval(autoSlideInterval)
      );
      carouselInner.addEventListener("mouseleave", () => startAutoSlide());
    });

    document.getElementById('signup-form').addEventListener('submit', async (e) => {
      e.preventDefault();
      const formData = {
        first_name: document.getElementById('first_name').value,
        middle_name: document.getElementById('middle_name').value,
        last_name: document.getElementById('last_name').value,
        suffix: document.getElementById('suffix').value,
        email: document.getElementById('email').value,
        password: document.getElementById('password').value,
        confirm_password: document.getElementById('confirm_password').value,
        username: document.getElementById('username').value,
        municipality: document.getElementById('municipality').value,
        province: document.getElementById('province').value,
      };

      try {
        const response = await fetch('register.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(formData),
        });
        const data = await response.json();
        if (data.success) {
          alert(data.message);
        } else {
          alert('Signup failed: ' + data.errors.join(', ')); // Error: data.errors is undefined
        }
      } catch (error) {
        console.error('Error during signup:', error);
        alert('An error occurred during signup: ' + error.message);
      }
    });

    // Gallery functionality
    document.addEventListener('DOMContentLoaded', function() {
      // Filter functionality
      const categoryFilter = document.getElementById('category-filter');
      const artworkCards = document.querySelectorAll('.artwork-card');

      categoryFilter.addEventListener('change', function() {
        const selectedCategory = this.value;

        artworkCards.forEach(card => {
          if (selectedCategory === 'all' || card.dataset.category === selectedCategory) {
            card.style.display = 'block';
          } else {
            card.style.display = 'none';
          }
        });
      });

      // Sort functionality
      const sortBy = document.getElementById('sort-by');

      sortBy.addEventListener('change', function() {
        const sortValue = this.value;
        const grid = document.querySelector('.artworks-grid');
        const cards = Array.from(artworkCards);

        cards.sort((a, b) => {
          const priceA = parseFloat(a.dataset.price);
          const priceB = parseFloat(b.dataset.price);

          switch (sortValue) {
            case 'price-low':
              return priceA - priceB;
            case 'price-high':
              return priceB - priceA;
            case 'newest':
              // Assuming newer items have higher prices for this demo
              return priceB - priceA;
            case 'popular':
              // Assuming more popular items have higher ratings
              const ratingA = parseFloat(a.querySelector('.rating').textContent.split(' ')[1]);
              const ratingB = parseFloat(b.querySelector('.rating').textContent.split(' ')[1]);
              return ratingB - ratingA;
            default:
              return 0;
          }
        });

        // Re-append sorted cards
        cards.forEach(card => grid.appendChild(card));
      });

      // Quick view functionality
      const quickViewButtons = document.querySelectorAll('.quick-view');
      quickViewButtons.forEach(button => {
        button.addEventListener('click', function() {
          // In a real implementation, this would show a modal with more details
          alert('Quick view feature would show more details about this artwork');
        });
      });

      // Wishlist functionality
      const wishlistButtons = document.querySelectorAll('.wishlist-btn');
      wishlistButtons.forEach(button => {
        button.addEventListener('click', function(e) {
          e.stopPropagation();
          const icon = this.querySelector('i');
          icon.classList.toggle('far');
          icon.classList.toggle('fas');
          icon.classList.toggle('active');

          if (icon.classList.contains('active')) {
            this.setAttribute('title', 'Remove from wishlist');
          } else {
            this.setAttribute('title', 'Add to wishlist');
          }
        });
      });

      // Zoom functionality
      const zoomButtons = document.querySelectorAll('.zoom-btn');
      zoomButtons.forEach(button => {
        button.addEventListener('click', function(e) {
          e.stopPropagation();
          const imageUrl = this.closest('.artwork-card').querySelector('img').src;
          // In a real implementation, this would show a lightbox with the zoomed image
          alert('Zoom feature would show a larger version of the image');
        });
      });

      // Pagination
      const pageButtons = document.querySelectorAll('.page-btn');
      pageButtons.forEach(button => {
        button.addEventListener('click', function() {
          pageButtons.forEach(btn => btn.classList.remove('active'));
          this.classList.add('active');
          // In a real implementation, this would load the corresponding page
        });
      });

      // Load more button
      const loadMoreBtn = document.querySelector('.load-more-btn');
      loadMoreBtn.addEventListener('click', function() {
        // In a real implementation, this would load more artworks
        alert('Load more functionality would fetch and display additional artworks');
      });
    });
  </script>
</body>

</html>