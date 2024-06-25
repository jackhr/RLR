<?php

include 'connection.php';
include 'helpers.php';

// Get the JSON data
$json = file_get_contents('php://input');
$data = json_decode($json, true);

try {
    if ($data['h826r2whj4fi_cjz8jxs2zuwahhhk6'] !== "") {
        respond([
            "success" => false,
            "message" => "error",
            "status" => 400,
            "data" => []
        ]);
    }

    // Get data sent via front end fetch request
    $name = $data["name"];
    $email = $data["email"];
    $message = $data["message"];

    // Email values
    $to = "jc2o@mac.com,jrainey@tropicalstudios.com,info@rlrentalsantigua.com";
    $subject = "Someone Has Contacted You From RL Rentals (Antigua) Website";
    $headers  = "From: no-reply@rlrentalsantigua.com\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $body = "Some has contacted you from the RL Rentals (Antigua) website.

Name: $name

Email: $email

Message: $message";

    // Send email to Admin
    $mail_res = mail($to, $subject, $body, $headers);


    respond([
        "success" => true,
        "message" => "success",
        "status" => 200,
        "data" => [
            "mail" => compact("to", "subject", "body", "headers", "mail_res"),
            "data" => $data,
        ]
    ]);
} catch (Exception $e) {
    respond([
        "success" => false,
        "message" => $e->getMessage(),
        "status" => 500,
        "data" => [$e]
    ]);
}
