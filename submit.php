<?php
// reCAPTCHA secret key
$recaptchaSecret = '6LcoDE8rAAAAAKyrw77fZ0b9MyYAUDSj9OZifQOA';
$recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

// Verify reCAPTCHA
$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecret&response=$recaptchaResponse");
$responseData = json_decode($verify);

if (!$responseData->success) {
    http_response_code(403);
    die("reCAPTCHA verification failed.");
}

// Sanitize and validate inputs
$name = htmlspecialchars(trim($_POST['name'] ?? ''));
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$message = htmlspecialchars(trim($_POST['message'] ?? ''));

if (!$name || !$email || !$message) {
    http_response_code(400);
    die("Invalid input.");
}

// Prepare log entry
$entry = "Name: $name\nEmail: $email\nMessage:\n$message\nDate: " . date("Y-m-d H:i:s") . "\n-------------------------\n";

// Append to contacts.txt
file_put_contents("contacts.txt", $entry, FILE_APPEND);

// Respond to frontend
echo "Message sent";
?>

