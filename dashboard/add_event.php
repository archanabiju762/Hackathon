<?php
session_start();
include_once '../includes/db_connection.php'; // Include the database connection file
include_once '../includes/orgheader.php'; // Include the header

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Fetch organizer ID from the users table where the role is 'organizer'
$user_id = $_SESSION['user_id'];
$query = "SELECT id FROM users WHERE id = ? AND role = 'organizer'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    // If user is not an organizer, redirect to login page
    header('Location: ../login.php');
    exit();
}

$stmt->bind_result($organizer_id);
$stmt->fetch();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = $_POST['event_name'];
    $event_type = $_POST['event_type'];
    $location = $_POST['location'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $created_at = date('Y-m-d H:i:s');
    $updated_at = $created_at;

    // Insert event details into the database
    $query = "INSERT INTO events (name, type, location, date, time, organizer_id, created_at, updated_at)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssis", $event_name, $event_type, $location, $date, $time, $organizer_id, $created_at, $updated_at);

    if ($stmt->execute()) {
        header('Location: organizer_dashboard.php'); // Redirect to organizer_dashboard.php after successful submission
        exit();
    } else {
        $error_message = "Error adding event: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>
    <link rel="stylesheet" href="../assets/css/add_event.css"> <!-- Link to add_event.css -->
</head>
<body>
    <div class="main-content">
        <h2>Add New Event</h2>
        <?php if (isset($success_message)) { ?>
            <p class="success"><?php echo htmlspecialchars($success_message); ?></p>
        <?php } elseif (isset($error_message)) { ?>
            <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
        <?php } ?>

        <form action="add_event.php" method="post" class="add-event-form">
            <label for="event_name">Event Name:</label>
            <input type="text" id="event_name" name="event_name" required>
            <br>

            <label for="event_type">Event Type:</label>
            <input type="text" id="event_type" name="event_type" required>
            <br>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" required>
            <br>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>
            <br>

            <label for="time">Time:</label>
            <input type="time" id="time" name="time" required>
            <br>

            <button type="submit">Add Event</button>
        </form>
    </div>

    <?php include_once '../includes/orgfooter.php'; // Include the footer ?>
</body>
</html>