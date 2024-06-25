<?php

session_start();

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

    $first_name = $data["first-name"];
    $last_name = $data["last-name"];
    $driver_license = $data["driver-license"];
    $country_region = $data["country-region"];
    $street = $data["street"];
    $town_city = $data["town-city"];
    $state_county = $data["state-county"];
    $phone = $data["phone"];
    $email = $data["email"];

    // Get data from session
    $itinerary = $_SESSION['reservation']['itinerary'];
    $vehicle = $_SESSION['reservation']['vehicle'];
    $add_ons = $_SESSION['reservation']['add_ons'];

    // Caclulate what is needed
    $days = getDifferenceInDays($itinerary['pickUpDate']['date'], $itinerary['returnDate']['date']);
    $sub_total = (int)$vehicle['price_day_USD'] * $days + array_sum(array_column($add_ons, 'cost'));
    $timestamp = time();
    $pick_up_ts = ((int)$itinerary['pickUpDate']['ts'] / 1000);
    $drop_off_ts = ((int)$itinerary['returnDate']['ts'] / 1000);

    // Insert contact info into database
    $contact_info_query = "INSERT INTO `contact_info` (`first_name`, `last_name`, `driver_license`, `country_or_region`, `street`, `town_or_city`, `state_or_county`, `phone`, `email`) VALUES ('{$first_name}', '{$last_name}', '{$driver_license}', '{$country_region}', '{$street}', '{$town_city}', '{$state_county}', '{$phone}', '{$email}');";
    $contact_info_result = mysqli_query($con, $contact_info_query);
    $contact_info_id = mysqli_insert_id($con);

    // Get key that doesn't exist in the database
    $key_is_safe = false;
    while (!$key_is_safe) {
        $key = generateRandomKey();
        $key_query = "SELECT * FROM `order_requests` WHERE `key` = '{$key}'";
        $key_result = mysqli_query($con, $key_query);
        $key_is_safe = mysqli_num_rows($key_result) === 0;
    }

    // Insert order request into database
    $order_request_query = "INSERT INTO `order_requests` (`key`, `pick_up`, `drop_off`, `confirmed`, `contact_info_id`, `sub_total`, `car_id`, `days`) VALUES ('{$key}', FROM_UNIXTIME({$pick_up_ts}), FROM_UNIXTIME({$drop_off_ts}), 0, {$contact_info_id}, '{$sub_total}', {$vehicle['id']}, {$days});";
    $order_request_result = mysqli_query($con, $order_request_query);
    $order_request_id = mysqli_insert_id($con);

    // Relate relevant add ons to order request
    foreach ($add_ons as $add_on_id => $add_on) {
        $order_request_add_on_query = "INSERT INTO `order_request_add_ons` (`order_request_id`, `add_on_id`) VALUES ({$order_request_id}, {$add_on_id});";
        $order_request_add_on_result = mysqli_query($con, $order_request_add_on_query);
    }

    // Email values
    $subject = "Car Rental Request at RL Rentals (Antigua)";
    $headers  = "From: no-reply@rlrentalsantigua.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

    $body = generateEmailBody($first_name, $last_name, $country_region, $street, $town_city, $state_county, $phone, $email, $order_request_id, $vehicle, $add_ons, $itinerary, $days, $sub_total, $timestamp, $key);

    // Send email to client
    $mail_res_client = mail($email, $subject, $body, $headers);

    // Send email to Admin
    $mail_res_admin = mail("jc2o@mac.com,jrainey@tropicalstudios.com,info@rlrentalsantigua.com", $subject, $body, $headers);

    session_destroy();

    respond([
        "success" => true,
        "message" => "success",
        "status" => 200,
        "data" => [
            "mail" => compact("to", "subject", "body", "headers", "mail_res", "mail_res_client", "mail_res_admin"),
            "key" => $key,
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
