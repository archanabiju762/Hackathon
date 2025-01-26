<?php
// Check if session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<head>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<header class="site-header">
    <div class="header-container">
        <div class="logo-container">
            <img src="assets/images/logo.jpg" alt="Life Track 360 Logo" class="logo">
        </div>
        <h1 class="header-title">Life Track 360</h1> <!-- Centered Title -->
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <?php if (isset($_SESSION['user_id'])) { ?>
                    <li><a href="event.php">Events</a></li> <!-- Show Events if logged in -->
                <?php } ?>
                <?php if (!isset($_SESSION['user_id'])) { ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php } else { ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php } ?>
            </ul>
        </nav>
    </div>
</header>
