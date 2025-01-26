<?php
session_start();
include 'includes/db_connection.php'; // Database connection file

// Initialize error and success messages
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate inputs
    if (!empty($email) && !empty($password)) {
        // Prepare the SQL statement to fetch the user by email
        $sql = "SELECT id, name, password, role FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the user exists
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify the entered password against the hashed password in the database
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on role
                if ($user['role'] === 'organizer') {
                    header("Location: dashboard/organizer_dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit;
            } else {
                $message = "Invalid password. Please try again.";
            }
        } else {
            $message = "No account found with this email.";
        }

        $stmt->close();
    } else {
        $message = "Please fill in all fields.";
    }
}
?>

<?php include 'includes/header.php'; ?>


<head>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<div class="main-content">
    <h1>Login to Your Account</h1>
    <form action="login.php" method="POST">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit" class="button">Login</button>
    </form>
    <p><?php echo $message; ?></p>
</div>

<?php include 'includes/footer.php'; ?>
