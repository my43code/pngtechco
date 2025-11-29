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
        header("Location: ../contact.php?status=error");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../contact.php?status=error");
        exit();
    }

    // INSERT into database
    $stmt = $conn->prepare(
        "INSERT INTO contact_messages (name, email, phone, service, message)
         VALUES (?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
        header("Location: ../contact.php?status=error");
        exit();
    }

    $stmt->bind_param("sssss", $name, $email, $phone, $service, $message);

    if ($stmt->execute()) {

        // ------------------------------------------------
        //  EMAIL NOTIFICATION TO YOU (PNGTECHCO)
        // ------------------------------------------------

        $to = "manu.maso@pngtechco.com";   // ✔ Your email address
        $subject = "New Contact Form Message from PNGTECHCO Website";

        $email_body  = "You have received a new message from your website:\n\n";
        $email_body .= "Name: $name\n";
        $email_body .= "Email: $email\n";
        $email_body .= "Phone: $phone\n";
        $email_body .= "Service: $service\n";
        $email_body .= "Message:\n$message\n\n";
        $email_body .= "This email was sent from your website contact form.";

        // Important for Hostinger email sending
        $headers  = "From: no-reply@pngtechco.com\r\n";
        $headers .= "Reply-To: $email\r\n";

        // Send email
        mail($to, $subject, $email_body, $headers);

        // Redirect success
        header("Location: ../contact.php?status=success");
    } else {
        // Redirect error
        header("Location: ../contact.php?status=error");
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
