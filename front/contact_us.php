<?php
// Check for empty fields
if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['phone']) || empty($_POST['message']))
{
    echo "No arguments Provided!";
    header("Location: homepage.php?contact=error");
}
   
$name = strip_tags(htmlspecialchars($_POST['name']));
$email_address = strip_tags(htmlspecialchars($_POST['email']));
$phone = strip_tags(htmlspecialchars($_POST['phone']));
$message = strip_tags(htmlspecialchars($_POST['message']));
   
// Create the email and send  the message

// This is where the form will send a message to.
$to = 'POGO@gmail.com';

// This is the email subject
$email_subject = "Website Contact Form:  $name";

// This is the email body
$email_body = "You have received a new message from your website contact form.\n\n"."Here are the details:\n\nName: $name\n\nEmail: $email_address\n\nPhone: $phone\n\nMessage:\n$message";

// This is the email address the generated message will be from
$headers = "From: noreply@yourdomain.com\n";

// This is the email address with reply-to headers
$headers .= "Reply-To: $email_address";

// This is the function to email
mail($to,$email_subject,$email_body,$headers);

// This will re-direct user back to homepage
header("Location: homepage.php?contact=success");

?>