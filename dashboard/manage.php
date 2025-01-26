<?php
session_start();
include_once '../includes/db_connection.php'; // Include your database connection file
include_once '../includes/orgheader.php'; // Include the header

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Fetch organizer's events from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT id, name, type, location, date, time FROM events WHERE organizer_id = ? ORDER BY date ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Handle the delete event
if (isset($_GET['delete'])) {
    $event_id = $_GET['delete'];
    $delete_query = "DELETE FROM events WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("i", $event_id);
    $delete_stmt->execute();
    $delete_stmt->close();
    header("Location: manage.php"); // Redirect back to manage.php after deletion
    exit();
}

// Handle the update event (Redirect to update page)
if (isset($_GET['update'])) {
    $event_id = $_GET['update'];
    header("Location: update_event.php?id=$event_id"); // Redirect to update page
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- Link to the shared style.css -->
    <link rel="stylesheet" href="../assets/css/manage.css"> <!-- Additional page-specific styling -->
</head>
<body>
    <div class="manage-events-container">
        <h2>Manage Your Events</h2>

        <!-- Table for displaying events -->
        <?php if ($result->num_rows > 0) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Event Type</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Organizer</th>
                        <th>Update</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['name']) . "</td>
                                <td>" . htmlspecialchars($row['type']) . "</td>
                                <td>" . htmlspecialchars($row['location']) . "</td>
                                <td>" . htmlspecialchars($row['date']) . "</td>
                                <td>" . htmlspecialchars($row['time']) . "</td>
                                <td>Organizer</td>
                                <td><a href='?update=" . $row['id'] . "'>Update</a></td>
                                <td><a href='?delete=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to delete this event?\")'>Delete</a></td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No events to manage at the moment.</p>
        <?php } ?>

    </div>

    <?php include_once '../includes/orgfooter.php'; // Include the footer ?>
</body>
</html>

<?php
$stmt->close(); // Close the statement
$conn->close(); // Close the database connection
?>
