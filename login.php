<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* General Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #e9f0f5; /* Soft light blue */
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    color: #333;
}

.container {
    background: #ffffff; /* Crisp white for contrast */
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    width: 350px;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 24px;
    color: #4a5568; /* Cool dark blue-gray */
}

input[type="text"], input[type="password"] {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #cbd5e0; /* Neutral light gray border */
    border-radius: 5px;
    font-size: 14px;
    background: #edf2f7; /* Subtle light gray-blue background */
    color: #2d3748; /* Darker gray for input text */
}

input[type="text"]:focus, input[type="password"]:focus {
    border-color: #3182ce; /* Blue focus border */
    outline: none;
    background: #e2e8f0; /* Slightly darker background on focus */
}

input[type="submit"] {
    background: #3182ce; /* Primary button color - soft blue */
    color: white;
    border: none;
    padding: 12px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
    transition: background-color 0.3s;
}

input[type="submit"]:hover {
    background: #2b6cb0; /* Darker blue on hover */
}

.message {
    color: #e53e3e; /* Warm red for errors */
    font-size: 14px;
    margin-top: 10px;
    text-align: center;
}

.toggle-link {
    text-align: center;
    margin-top: 15px;
    display: block;
    color: #3182ce; /* Same as the button color */
    text-decoration: none;
    font-size: 14px;
}

.toggle-link:hover {
    text-decoration: underline;
}

/* Additional Adjustments for Container Alignment */
.form-container {
    display: none; /* Hide both forms by default */
}

.form-container.active {
    display: block; /* Show only the active form */
}

    </style>
</head>
<body>
    <?php
// Start the session
session_start();

// Connect to MySQL Database
$conn = new mysqli('localhost', 'root', '', 'inventory_management');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login logic
if (isset($_POST['login_submit'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Query to fetch user based on username
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Store user data in session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit(); // Always include exit() after header to stop further execution
        } else {
            $login_message = "Invalid password!";
        }
    } else {
        $login_message = "No user found with this username!";
    }
}
?>

    <div class="container">
        <!-- Login Form -->
        <div id="login-form" class="form-container active">
            <h2>Login</h2>
            <form action="login.php" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="submit" name="login_submit" value="Login">
            </form>
            <div class="message"><?php echo isset($login_message) ? $login_message : ''; ?></div>
            <a href="#" class="toggle-link" id="show-register">Don't have an account? Sign up</a>
        </div>

        <!-- Register Form -->
        <div id="register-form" class="form-container">
            <h2>Register</h2>
            <form action="login.php" method="POST">
                <input type="text" name="first_name" placeholder="First Name" required>
                <input type="text" name="last_name" placeholder="Last Name" required>
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <input type="submit" name="register_submit" value="Register">
            </form>
            <div class="message"><?php echo isset($message) ? $message : ''; ?></div>
            <a href="#" class="toggle-link" id="show-login">Already have an account? Login</a>
        </div>
    </div>

    <script>
        // JavaScript to toggle forms
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        const showRegister = document.getElementById('show-register');
        const showLogin = document.getElementById('show-login');

        showRegister.addEventListener('click', (e) => {
            e.preventDefault();
            loginForm.classList.remove('active');
            registerForm.classList.add('active');
        });

        showLogin.addEventListener('click', (e) => {
            e.preventDefault();
            registerForm.classList.remove('active');
            loginForm.classList.add('active');
        });
    </script>
</body>
</html>
