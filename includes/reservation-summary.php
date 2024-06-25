<?php

include_once 'helpers.php';

if (!!$order_request) {
    $vehicle['imgSrc'] = "/assets/images/vehicles/{$vehicle['slug']}.jpg";
    $vehicle_name = $vehicle['name'];
    $vehicle_type = $vehicle['type'];
    $vehicle_img_src = $vehicle['imgSrc'];
    $render_change_btn = false;
    $days = getDifferenceInDays($order_request['pick_up'], $order_request['drop_off']);
    $rate = [
        'days' => $days,
        'rate' => makePriceString($vehicle['price_day_USD']),
        'sub_total' => makePriceString((int)$vehicle['price_day_USD'], $days)
    ];
    $estimated_total = ((int)$vehicle['price_day_USD'] * $days) + array_sum(array_column($add_ons, 'cost'));
    $estimated_total = makePriceString($estimated_total);
} else if (isset($_SESSION['reservation'])) {
    $render_change_btn = true;
    $reservation = $_SESSION['reservation'];
    if (isset($reservation['vehicle'])) {
        $vehicle = $reservation['vehicle'];
        $vehicle_name = $vehicle['name'];
        $vehicle_type = $vehicle['type'];
        $vehicle_img_src = $vehicle['imgSrc'];
        $days = 1;
        $rate = [
            'days' => $days,
            'rate' => makePriceString($vehicle['price_day_USD']),
            'sub_total' => makePriceString((int)$vehicle['price_day_USD'])
        ];
        if (isset($reservation['itinerary'])) {
            $itinerary = $reservation['itinerary'];
            $days = getDifferenceInDays($itinerary['pickUpDate']['date'], $itinerary['returnDate']['date']);
            $rate['days'] = $days;
            $rate['sub_total'] = makePriceString((int)$vehicle['price_day_USD'], $days);
        }
        $estimated_total = (int)$vehicle['price_day_USD'] * $days;
    }
    if (isset($reservation['add_ons']) && count($reservation['add_ons']) > 0) {
        $add_ons = $reservation['add_ons'];
        $estimated_total = isset($estimated_total) ? $estimated_total + array_sum(array_column($add_ons, 'cost')) : array_sum(array_column($add_ons, 'cost'));
    }
    $estimated_total = isset($estimated_total) ? makePriceString($estimated_total) : "--";
} else {
    $vehicle_name = "Car";
    $vehicle_type = "Please select your vehicle";
}

?>

<div id="reservation-summary">

    <?php if ($render_change_btn) { ?>
        <span class="change-car-btn continue-btn">Change?</span>
    <?php } ?>

    <h5><?php echo $vehicle_name; ?></h5>
    <h6><?php echo $vehicle_type; ?></h6>

    <div class="car summary">
        <?php if (isset($vehicle)) { ?>
            <img src="<?php echo $vehicle['imgSrc']; ?>" alt="">
        <?php } else { ?>
            <div>+</div>
            <div>
                <span>Find a vehicle</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                    <path d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z" />
                </svg>
            </div>
        <?php } ?>
    </div>

    <div class="rate summary">
        <h6>Rate</h6>
        <?php if (isset($rate)) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Day(s)</th>
                        <th>Rate</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $rate['days']; ?></td>
                        <td><?php echo $rate['rate']; ?></td>
                        <td><?php echo $rate['sub_total']; ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">Rental Subtotal</td>
                        <td><?php echo $rate['sub_total']; ?></td>
                    </tr>
                </tbody>
            </table>
        <?php } else { ?>
            <div>
                <span>--</span>
                <span>--</span>
                <span>--</span>
            </div>
        <?php } ?>
    </div>

    <div class="add-ons summary">
        <h6>Add-ons</h6>
        <?php if (isset($add_ons)) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($add_ons as $id => $add_on) { ?>
                        <tr data-id="<?php echo $id; ?>">
                            <td><?php echo $add_on['name']; ?></td>
                            <td><?php echo makePriceString($add_on['cost']); ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td>Add-ons Subtotal</td>
                        <td><?php echo makePriceString(array_sum(array_column($add_ons, 'cost'))); ?></td>
                    </tr>
                </tbody>
            </table>
        <?php } else { ?>
            <div>
                <span>--</span>
                <span>--</span>
                <span>--</span>
            </div>
        <?php } ?>
    </div>

    <div class="estimated-total">
        <span>Estimated Total</span>
        <span><?php echo $estimated_total; ?></span>
    </div>

</div>