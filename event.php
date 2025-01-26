<?php
session_start();
include_once 'includes/db_connection.php'; // Include the database connection file

// Check if a search query is provided
$search_query = '';
if (isset($_POST['search'])) {
    $search_query = $_POST['search']; // Get the search value from the form
}

// Modify the query to filter based on search
$query = "SELECT events.id, events.name, events.type, events.location, events.date, events.time, users.name AS organizer_name
          FROM events
          LEFT JOIN users ON events.organizer_id = users.id
          WHERE events.type LIKE ? OR events.location LIKE ?
          ORDER BY events.date ASC";
$stmt = $conn->prepare($query);
$search_param = "%$search_query%"; // Prepare the search value for LIKE comparison
$stmt->bind_param("ss", $search_param, $search_param); // Bind the parameters for type and location search
$stmt->execute();
$result = $stmt->get_result();
?>

<?php include 'includes/header.php'; ?>

<head>
    <link rel="stylesheet" href="assets/css/event.css">
</head>

<div class="main-content">
    <h2>Upcoming Events</h2>

    <!-- Search Bar -->
    <form method="POST" class="search-form">
        <input type="text" name="search" placeholder="Search by type or location" value="<?php echo htmlspecialchars($search_query); ?>">
        <button type="submit">Search</button>
    </form>
    
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
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch and display events
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['name']) . "</td>
                            <td>" . htmlspecialchars($row['type']) . "</td>
                            <td>" . htmlspecialchars($row['location']) . "</td>
                            <td>" . htmlspecialchars($row['date']) . "</td>
                            <td>" . htmlspecialchars($row['time']) . "</td>
                            <td>" . htmlspecialchars($row['organizer_name']) . "</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No events found.</p>
    <?php } ?>
</div>

<?php include 'includes/footer.php'; ?>

<?php
$stmt->close(); // Close the statement
$conn->close(); // Close the database connection
?>
