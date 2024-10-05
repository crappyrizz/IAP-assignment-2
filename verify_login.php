<?php
// Database connection
$pdo = new PDO('mysql:host=127.0.0.1:3307;dbname=user_data', 'root', '');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $code = $_POST['confirmation_code'];

    // Check if the code matches
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND confirmation_code = ?");
    $stmt->execute([$email, $code]);
    $user = $stmt->fetch();

    if ($user) {
        // Successful login, clear the confirmation code
        $stmt = $pdo->prepare("UPDATE users SET confirmation_code = NULL WHERE email = ?");
        $stmt->execute([$email]);

        // Redirect to the users list page
        header("Location: User.php");
        exit();
    } else {
        // Invalid code
        echo 'Invalid confirmation code.';
    }
}
?>
