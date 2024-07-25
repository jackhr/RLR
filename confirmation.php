<?php

session_start();

$title_suffix = "Confirmation";
$page = "confirmation";
$description = "Thank you for choosing RL Rentals (Antigua). Your order has been requested.";
$extra_css = "reservation";

include_once 'includes/header.php';

// Get the order request
$order_request_query = "SELECT * FROM order_requests WHERE `key` = '{$_GET['key']}'";
$order_request_result = mysqli_query($con, $order_request_query);
$order_request = mysqli_fetch_assoc($order_request_result);

// Get the add-ons
$order_request_add_on_query = "SELECT * FROM order_request_add_ons WHERE `order_request_id` = {$order_request['id']}";
$order_request_add_on_result = mysqli_query($con, $order_request_add_on_query);
$add_ons = [];
$has_collion_insurance = false;
while ($add_on = mysqli_fetch_assoc($order_request_add_on_result)) {
    $add_on_query = "SELECT * FROM add_ons WHERE `id` = {$add_on['add_on_id']}";
    $add_on_result = mysqli_query($con, $add_on_query);
    $row = mysqli_fetch_assoc($add_on_result);
    if ($row['name'] === "Collision Insurance") $has_collion_insurance = true;
    $add_ons[] = $row;
}

// Get the vehicle
$vehicle_query = "SELECT * FROM vehicles WHERE `id` = {$order_request['car_id']}";
$vehicle_result = mysqli_query($con, $vehicle_query);
$vehicle = mysqli_fetch_assoc($vehicle_result);

// Get the vehicle discounts
$vehicle_discount_query = "SELECT * FROM vehicle_discounts WHERE `vehicle_id` = {$order_request['car_id']} AND `days` <= {$order_request['days']} ORDER BY `days` DESC LIMIT 1";
$vehicle_discount_result = mysqli_query($con, $vehicle_discount_query);
$discount = mysqli_fetch_assoc($vehicle_discount_result);

// Get the contact infos
$contact_info_query = "SELECT * FROM contact_info WHERE `id` = {$order_request['contact_info_id']}";
$contact_info_result = mysqli_query($con, $contact_info_query);
$contact_info = mysqli_fetch_assoc($contact_info_result);

if (!$order_request) {
    header('Location: /');
    exit;
}

?>

<section class="general-header">
    <h1>Order Requested</h1>
</section>

<section id="confirmation-section">
    <div class="inner">
        <h1>Thank you! Your order has been requested.</h1>

        <div class="reservation-flow-container">
            <?php include 'includes/reservation-summary.php'; ?>
            <div class="right">
                <div class="order-header">
                    <span>Order Number:</span>
                    <span><?php echo $order_request['id']; ?></span>
                </div>

                <h6>Summary</h6>

                <div id="order-summary">
                    <div class="order-summary-item itinerary">
                        <?php
                        $location_str = "<b>{$order_request['pick_up_location']}</b>";
                        if ($order_request['pick_up_location'] === "Your Hotel") {
                            $hotel_str = isset($contact_info['hotel']) ? $contact_info['hotel'] : "";
                            $location_str = "<b>{$order_request['pick_up_location']}:</b> {$hotel_str}";
                        }
                        ?>
                        <div class="left">
                            <h6>Pick Up</h6>
                            <p><?php echo date('F d, Y h:i A', strtotime($order_request['pick_up'])); ?></p>
                            <p><?php echo $location_str; ?></p>
                        </div>
                        <div class="right">
                            <h6>Drop Off</h6>
                            <p><?php echo date('F d, Y h:i A', strtotime($order_request['drop_off'])); ?></p>
                            <p><?php echo $location_str; ?></p>
                        </div>
                    </div>
                    <div class="order-summary-item vehicle">
                        <div class="left">
                            <h6>Vehicle Type</h6>
                            <p><?php echo "{$vehicle['name']} {$vehicle['type']}"; ?></p>
                        </div>
                        <div class="right">
                            <h6>Add-ons</h6>
                            <p><?php
                                $counter = 0;
                                foreach ($add_ons as $add_on) {
                                    $prefix = $counter > 0 ? ", " : "";
                                    $counter++;
                                    echo "<span>{$prefix}{$add_on['name']}</span>";
                                } ?>
                            </p>
                        </div>
                    </div>
                    <div class="order-summary-item contact-info">
                        <div class="left">
                            <h6>Your Information</h6>
                            <p>
                                <?php
                                echo "{$contact_info['first_name']} {$contact_info['last_name']}<br>";
                                echo "{$contact_info['street']}<br>";
                                echo "{$contact_info['town_or_city']}, {$contact_info['state_or_county']}<br>";
                                echo "{$contact_info['country_or_region']}<br>";
                                echo "{$contact_info['phone']}<br>";
                                echo "{$contact_info['email']}<br>";
                                ?>
                            </p>
                        </div>
                        <div class="right">
                            <h6>Payment Information</h6>
                            <p>Estimated Total - <?php echo $estimated_total; ?></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>


<?php include_once 'includes/footer.php'; ?>