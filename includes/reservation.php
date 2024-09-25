<?php
session_start();

include 'connection.php';
include 'helpers.php';

// Get the JSON data
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Check if JSON was properly decoded
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['error' => 'Invalid JSON input']);
    exit;
}

if (isset($data['step'])) {
    $_SESSION['reservation']['step'] = $data['step'];
    unset($data['step']);
}

if ($data['action'] === 'itinerary') {
    unset($data['action']);
    $days = getDifferenceInDays($data['pickUpDate']['date'], $data['returnDate']['date']);
    $_SESSION['reservation']['itinerary'] = $data;
    $_SESSION['reservation']['itinerary']['days'] = $days;

    if (isset($_SESSION['reservation']['vehicle'])) {
        $vehicle_discount_query = "SELECT * FROM vehicle_discounts WHERE vehicle_id = {$_SESSION['reservation']['vehicle']['id']} AND `days` <= $days ORDER BY `days` DESC LIMIT 1";
        $vehicle_discount_result = mysqli_query($con, $vehicle_discount_query);
        $discount = mysqli_fetch_assoc($vehicle_discount_result);

        $_SESSION['reservation']['discount'] = $discount;
    }

    $data = $_SESSION['reservation'];
}

if ($data['action'] === 'vehicle') {
    unset($data['action']);
    $vehicle_query = "SELECT * FROM vehicles WHERE id = {$data['id']}";
    $vehicle_result = mysqli_query($con, $vehicle_query);
    $vehicle = mysqli_fetch_assoc($vehicle_result);
    $vehicle['imgSrc'] = "/assets/images/vehicles/{$vehicle['slug']}.jpg";
    $_SESSION['reservation']['vehicle'] = $vehicle;

    if (isset($_SESSION['reservation']['itinerary'])) {
        $days = $_SESSION['reservation']['itinerary']['days'];

        $vehicle_discount_query = "SELECT * FROM vehicle_discounts WHERE vehicle_id = {$data['id']} AND `days` <= $days ORDER BY `days` DESC LIMIT 1";
        $vehicle_discount_result = mysqli_query($con, $vehicle_discount_query);
        $discount = mysqli_fetch_assoc($vehicle_discount_result);

        $_SESSION['reservation']['discount'] = $discount;
    }

    $data = $_SESSION['reservation'];
}

if ($data['action'] === 'add_add_on') {
    $add_on_query = "SELECT * FROM add_ons WHERE id = {$data['id']}";
    $add_on_result = mysqli_query($con, $add_on_query);
    $add_on = mysqli_fetch_assoc($add_on_result);

    // merge new add on with current addons on the session object and sort the array by id
    $_SESSION['reservation']['add_ons'][$add_on['id']] = $add_on;
    uasort($_SESSION['reservation']['add_ons'], function ($a, $b) {
        return $a['id'] - $b['id'];
    });

    $data = $_SESSION['reservation'];
}

if ($data['action'] === 'remove_add_on') {
    $add_on_query = "SELECT * FROM add_ons WHERE id = {$data['id']}";
    $add_on_result = mysqli_query($con, $add_on_query);
    $add_on = mysqli_fetch_assoc($add_on_result);

    // remove add_on from current addons on the session object
    $_SESSION['reservation']['add_ons'] = array_filter($_SESSION['reservation']['add_ons'], function ($a) use ($add_on) {
        return $a['id'] !== $add_on['id'];
    });

    $data = $_SESSION['reservation'];
}

if ($data['action'] === 'get_reservation') {
    $data = $_SESSION['reservation'];
}

if ($data['action'] === 'reset_reservation') {
    unset($_SESSION['reservation']);
}

if ($data['action'] === 'reset_itinerary') {
    unset($_SESSION['reservation']['itinerary']);
}

if ($data['action'] === 'reset_car_selection') {
    unset($_SESSION['reservation']['car']);
}

if ($data['action'] === 'reset_add_ons') {
    unset($_SESSION['reservation']['add_ons']);
}

if ($data['action'] === 'reset_contact_info') {
    unset($_SESSION['reservation']['contact_info']);
}

// Send back the data as JSON
echo json_encode($data ? $data : $_POST);
