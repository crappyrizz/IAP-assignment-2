<?php
$pdo = new PDO('mysql:host=127.0.0.1:3307;dbname=user_data', 'root', '');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $enteredCode = $_POST['confirmation_code'];
    $email = $_POST['email'];

    // Verify the code from the database
    $stmt = $pdo->prepare("SELECT * FROM users_temp WHERE email = ? AND confirmation_code = ?");
    $stmt->execute([$email, $enteredCode]);
    $user = $stmt->fetch();

    if ($user) {
        // Move the user to the main users table
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$user['username'], $user['email'], $user['password']]);

        // Delete from users_temp
        $stmt = $pdo->prepare("DELETE FROM users_temp WHERE email = ?");
        $stmt->execute([$email]);

        echo "User successfully registered!";
        echo "Redirecting You To Users Page!";

        header("Refresh: 2; url=User.php");

    } else {
        echo "Invalid confirmation code!";
    }
}
