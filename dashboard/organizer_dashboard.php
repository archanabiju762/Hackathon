<?php
session_start();
include_once '../includes/db_connection.php'; // Include your database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Fetch organizer's name from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT name FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($organizer_name);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizer Dashboard</title>
    <link rel="stylesheet" href="../assets/css/organizer_dashboard.css?v=1.0">

    
</head>
<body>
    <?php include_once '../includes/orgheader.php'; ?>

    <div class="sidebar">
        <h2>Organizer Dashboard</h2>
        <ul>
            <li><a href="#introduction">Introduction</a></li>
            <li><a href="#upcoming">Upcoming Events</a></li>
            <li><a href="add_event.php">Add Event</a></li>
            <li><a href="manage.php">Manage Events</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <!-- Introduction Section -->
        <section id="introduction">
            <h3>Welcome to Life Track 360, <?php echo htmlspecialchars($organizer_name); ?>!</h3>
            <p>
                Life Track 360 is your ultimate solution for organizing and managing health camps and events. 
                As an organizer, you play a vital role in ensuring the community has access to essential health services. 
                Our platform provides you with the tools to seamlessly add events, manage participants, and monitor upcoming activities.
            </p>
            <p>
                Whether you're planning a single-day event or a recurring health camp, Life Track 360 is designed to simplify your tasks and maximize your impact. 
                Explore the features below to get started!
            </p>
        </section>

        <!-- Features Section -->
        <section id="features">
            <h3>Features for Organizers</h3>
            <ul>
                <li><strong>Dashboard:</strong> A centralized hub to monitor all your activities.</li>
                <li><strong>Add Events:</strong> Quickly create new events with detailed information.</li>
                <li><strong>Manage Events:</strong> View, update, or delete your existing events.</li>
            </ul>
        </section>

        <!-- Welcome Message -->
        <section id="welcome">
            <h3>Your Journey Starts Here</h3>
            <p>
                We're excited to have you onboard. At Life Track 360, we value your contribution towards 
                creating a healthier community. Use this dashboard to organize meaningful events, 
                reach a broader audience, and make a lasting impact.
            </p>
            <img src="../assets/images/6.jpg" alt="Welcome Organizer" style="max-width:100%; height:auto;">
        <img src="../assets/images/2.jpg" alt="Image 2" class="welcome-img">
        <img src="../assets/images/5.jpg" alt="Image 3" class="welcome-img">
        <img src="../assets/images/logo.jpg" alt="Image 4" class="welcome-img">
    </div>
        </section>

        <!-- Upcoming Events Section
        <section id="upcoming">
            <h3>Upcoming Events</h3>
            <p>Check back soon to see the events you’ve planned or add a new one!</p>
        </section> -->
    </div>

    <?php include_once '../includes/orgfooter.php'; ?>
</body>
</html>
