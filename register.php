<?php
// Include the header
include 'includes/header.php';
// Include database connection
include 'includes/db_connection.php';

// Initialize error and success messages
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form inputs
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validate inputs
    if (!empty($name) && !empty($email) && !empty($password) && !empty($role)) {
        // Check if the email is already registered
        $check_query = "SELECT email FROM users WHERE email = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $message = "This email is already registered.";
        } else {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert user data into the database
            $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

            if ($stmt->execute()) {
                // Redirect to login page after successful registration
                header("Location: login.php");
                exit;
            } else {
                $message = "Error: " . $stmt->error;
            }

            $stmt->close();
        }

        $check_stmt->close();
    } else {
        $message = "Please fill in all fields.";
    }
}
?>

<head>
    <link rel="stylesheet" href="assets/css/register.css">
</head>
<div class="main-content">
    <h1>Register for an Account</h1>
    <form action="register.php" method="POST">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <label for="role">Role:</label><br>
        <select id="role" name="role" required>
            <option value="">Select a role</option>
            <option value="organizer">Organizer</option>
            <option value="user">User</option>
        </select><br><br>

        <button type="submit" class="button">Register</button>
    </form>
    <p><?php echo $message; ?></p>
</div>

<?php include 'includes/footer.php'; ?>
