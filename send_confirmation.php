<?php
// Load Composer's autoloader
require 'vendor/autoload.php'; // Ensure this line is correct
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/SMTP.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// Database connection (update credentials accordingly)
$pdo = new PDO('mysql:host=127.0.0.1:3307;dbname=user_data', 'root', '');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $token = bin2hex(random_bytes(16));

    // Save user temporarily
    $stmt = $pdo->prepare("INSERT INTO users_temp (username, email, password, token) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $email, $password, $token]);

    // Send confirmation email
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'glynn.tanui@strathmore.edu';  // Your email address
        $mail->Password = 'mplj uvur zzfw phpv';  // Your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('glynn.tanui@strathmore.edu', 'Your App');
        $mail->addAddress($email, $username);
        // Generate a unique confirmation code
        $code = random_int(100000, 999999);

        // Save user temporarily with the code
        $stmt = $pdo->prepare("INSERT INTO users_temp (username, email, password, confirmation_code) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $password, $code]);


        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Email Confirmation';
        $mail->Body = "Your confirmation code is: <strong>$code</strong>";

        $mail->send();
        echo 'Confirmation email has been sent!';

        header("Location: confirmation_form.php?email=$email");
        exit();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
