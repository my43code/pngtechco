<?php
// Handle contact form submissions
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = trim($_POST["name"] ?? "");
    $email   = trim($_POST["email"] ?? "");
    $phone   = trim($_POST["phone"] ?? "");
    $service = trim($_POST["service"] ?? "");
    $message = trim($_POST["message"] ?? "");

    // Basic validation
    if ($name === "" || $email === "" || $service === "" || $message === "") {
        header("Location: ../contact.html?status=error");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../contact.html?status=error");
        exit();
    }

    // INSERT into database
    $stmt = $conn->prepare(
        "INSERT INTO contact_messages (name, email, phone, service, message)
         VALUES (?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
        header("Location: ../contact.html?status=error");
        exit();
    }

    $stmt->bind_param("sssss", $name, $email, $phone, $service, $message);

    if ($stmt->execute()) {

        // ------------------------------------------------
        //  EMAIL NOTIFICATION TO PNGTECHCO
        // ------------------------------------------------

        $to = "manu.maso@pngtechco.com";   // Your actual email
        $subject = "New Contact Form Message - PNGTECHCO";

        $email_body  = "You have received a new message:\n\n";
        $email_body .= "Name: $name\n";
        $email_body .= "Email: $email\n";
        $email_body .= "Phone: $phone\n";
        $email_body .= "Service: $service\n\n";
        $email_body .= "Message:\n$message\n\n";
        $email_body .= "Sent from PNGTECHCO Website.";

        // You MUST create this email inside Hostinger Emails
        $headers  = "From: no-reply@pngtechco.com\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        // Send the email
        mail($to, $subject, $email_body, $headers);

        // Redirect success
        header("Location: ../contact.html?status=success");
        exit();
    } else {
        header("Location: ../contact.html?status=error");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>

 <!-- SUCCESS / ERROR MESSAGES --
                <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
                    <div class="alert success">Thank you, your message has been sent.</div>
                <?php elseif (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
                    <div class="alert error">Sorry, something went wrong. Please try again.</div>
                <?php endif; ?>-->