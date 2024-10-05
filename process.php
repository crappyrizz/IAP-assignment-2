<?php
require_once 'Database.php';
require_once 'User.php';

if (isset($_POST['username'], $_POST['email'], $_POST['password'])) {
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if ($email && $password && strlen($password) > 6) {
        $db = new Database();
        $user = new User($db);
        $user->store($username, $email, $password);
        echo "User successfully registered!";
    } else {
        echo "Invalid input!";
    }
}
