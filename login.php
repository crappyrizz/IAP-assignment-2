<?php
$pdo = new PDO('mysql:host=127.0.0.1:3306;dbname=user_data', 'root', '');

// Load Composer's autoloader
require 'vendor/autoload.php'; 
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user exists and verify the password
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Password is correct, send confirmation code via email
        $code = random_int(100000, 999999);

        // Save the confirmation code in the database temporarily
        $stmt = $pdo->prepare("UPDATE users SET confirmation_code = ? WHERE email = ?");
        $stmt->execute([$code, $email]);

        // Send the confirmation code to the user's email
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'edgar.ndonga@strathmore.edu';  
            $mail->Password = 'mplj uvur zzfw phpv';  
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
            $mail->setFrom('edgar.ndonga@strathmore.edu', 'Confirmation Code');
            $mail->addAddress($email);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Login Confirmation Code';
            $mail->Body = "Your login confirmation code is: <strong>$code</strong>";

            $mail->send();

            // Redirect to the code confirmation page
            header("Location: confirm_login_code.php?email=$email");
            exit();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        // Invalid credentials
        echo 'Invalid email or password.';
    }
}
?>
