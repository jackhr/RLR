<?php
$title_suffix = "Reservation";
$page = "reservation";
$description = "Book a car rental with RL Rentals (Antigua). Choose from a variety of vehicles and rental options. Reserve your car today.";

include_once 'includes/header.php';
?>

<section class="general-header">
    <h1>Reservation / Book Now</h1>
</section>

<section id="reservation-steps">
    <div class="inner">
        <script src="https://rl-rentals-antigua.rlrentalsantigua.com/public/car-rental/integrations/assets/integrator"></script>
        <div
            class="hq-rental-software-integration"
            data-integrator_link="https://rl-rentals-antigua.rlrentalsantigua.com/public/car-rental/integrations"
            data-brand="waqnqiy8-n2g3-n4n8-gmnz-mivzps4tunz3"
            data-snippet="reservations"
            data-skip_language=""
            data-rate_type_uuid=""
            data-referral=""
        ></div>
    </div>
</section>

<?php include_once 'includes/footer.php'; ?>