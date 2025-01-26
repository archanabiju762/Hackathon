<?php
session_start();
include_once '../includes/db_connection.php'; // Include your database connection file
include_once '../includes/orgheader.php'; // Include the header

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Get the event ID from the URL
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];
} else {
    header('Location: manage.php'); // Redirect if no ID is provided
    exit();
}

// Fetch the event details
$query = "SELECT id, name, type, location, date, time FROM events WHERE id = ? AND organizer_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $event_id, $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($event_id, $event_name, $event_type, $event_location, $event_date, $event_time);
$stmt->fetch();
$stmt->close();

// Handle form submission to update event
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = $_POST['event_name'];
    $event_type = $_POST['event_type'];
    $event_location = $_POST['event_location'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];

    $update_query = "UPDATE events SET name = ?, type = ?, location = ?, date = ?, time = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("sssssi", $event_name, $event_type, $event_location, $event_date, $event_time, $event_id);
    $update_stmt->execute();
    $update_stmt->close();

    header("Location: manage.php"); // Redirect back to the manage page after update
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Event</title>
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- Link to the shared style.css -->
    <link rel="stylesheet" href="../assets/css/manage.css"> <!-- Additional page-specific styling -->
</head>
<body>
    <div class="main-content">
        <h2>Update Event</h2>

        <!-- Update Event Form in Table Layout -->
        <form method="POST" action="update_event.php?id=<?php echo $event_id; ?>">
            <table>
                <tr>
                    <td><label for="event_name">Event Name:</label></td>
                    <td><input type="text" id="event_name" name="event_name" value="<?php echo htmlspecialchars($event_name); ?>" required></td>
                </tr>
                <tr>
                    <td><label for="event_type">Event Type:</label></td>
                    <td><input type="text" id="event_type" name="event_type" value="<?php echo htmlspecialchars($event_type); ?>" required></td>
                </tr>
                <tr>
                    <td><label for="event_location">Event Location:</label></td>
                    <td><input type="text" id="event_location" name="event_location" value="<?php echo htmlspecialchars($event_location); ?>" required></td>
                </tr>
                <tr>
                    <td><label for="event_date">Event Date:</label></td>
                    <td><input type="date" id="event_date" name="event_date" value="<?php echo htmlspecialchars($event_date); ?>" required></td>
                </tr>
                <tr>
                    <td><label for="event_time">Event Time:</label></td>
                    <td><input type="time" id="event_time" name="event_time" value="<?php echo htmlspecialchars($event_time); ?>" required></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;">
                        <button type="submit">Update Event</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <?php include_once '../includes/orgfooter.php'; // Include the footer ?>
</body>
</html>
