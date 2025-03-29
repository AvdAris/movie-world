<?php
session_start();
$isLoggedIn = isset($_SESSION['username']);
$user = $isLoggedIn ? $_SESSION['username'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Movie World</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/style.css">
  <script src="/assets/js/auth.js" defer></script>
  <script src="/assets/js/login.js" defer></script>
  <script src="/assets/js/movie.js" defer></script>
  <script>
    const loggedInUser = <?= json_encode($_SESSION['user_id'] ?? null) ?>;
  </script>
</head>
<body>
<header>
  <h1>Movie World</h1>
  <div class="auth-buttons" id="auth-buttons">
    <?php if ($isLoggedIn): ?>
      <span>Welcome, <strong style="color: #009dff;"><?= htmlspecialchars($user) ?></strong></span>
      <button id="newMovieBtn">New Movie</button>
      <button id="logoutBtn">Log out</button>
    <?php else: ?>
      <button id="loginBtn">Log in</button>
      <button id="signupBtn" class="signup">Sign Up</button>
    <?php endif; ?>
  </div>
</header>

<div class="main-content">
  <div class="left-column">
    <div class="page-header">
      <div class="movie-count" id="movie-count">Loading movies...</div>
    </div>
    <div id="movie-list"></div>
  </div>

  <div class="right-column">
    <div class="sort-box">
      <strong>Sort by:</strong>
      <button class="sort-btn" data-sort="likes" data-direction="desc">Likes</button>
      <button class="sort-btn" data-sort="hates" data-direction="desc">Hates</button>
      <button class="sort-btn" data-sort="date" data-direction="desc">Date</button>

    </div>
  </div>
</div>

<template id="movie-template">
  <div class="movie-card">
    <div class="movie-title">
      <span class="title"></span>
      <span> Posted <span class="date"></span>
    </div>
    <div class="movie-description"></div>
    <div class="movie-footer">
      <div class="vote-info">
        <span class="likes"></span>
        <div class="vote-buttons">
          <button class="like-btn">Like</button>
          <button class="hate-btn">Hate</button>
        </div>
      </div>
      <span class="poster">Posted by <span class="poster-link"></span></span>
    </div>
  </div>
</template>

<div class="popup-overlay" id="popup">
  <div class="popup">
    <h3 id="popup-title">Login</h3>
    <form id="authForm" onsubmit="return false;">
      <input type="text" id="username" placeholder="Username" />
      <input type="password" id="password" placeholder="Password" />
  <button id="submitBtn">Submit</button>
</form>

  </div>
</div>

<div class="popup-overlay" id="newMoviePopup">
  <div class="popup">
    <h3>New Movie</h3>
    <input type="text" id="movieTitle" placeholder="Title">
    <textarea id="movieDescription" placeholder="Description"></textarea>
    <button id="postMovieBtn">Post Movie</button>
  </div>
</div>

</body>
</html>