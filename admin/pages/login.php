<?php
session_start();
include('../../includes/config.php');

$error = "";

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE email = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $admin = $result->fetch_assoc();

        if (password_verify($password, $admin['password'])) {

            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];

            // Navigate to the admin dashboard
            header("Location: index.php");
            exit();

        } else {

            $error = "Incorrect password";

        }

    } else {

        $error = "Admin account not found";

    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>

    <div class="container">

        <h2>Admin Login Page</h2>

        <form method="POST">

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <!-- Empty div for error messages -->
            <div class="error">
                <?php echo $error; ?>
            </div>

            <input type="submit" name="login" value="Login">

        </form>

    </div>

</body>

</html>