<?php

include 'connection.php';

$description = isset($description) ? $description : "Welcome to RL Rentals (Antigua). We offer a wide selection of vehicles for rent. Book your car today!";

$base_title = "RL Rentals (Antigua)";

$title = isset($title_suffix) ? $base_title .= " | " . $title_suffix : $title;

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <meta name="robots" content="noindex">
    <meta name="keywords" content="antigua, antigua and barbuda, antigua rentals, st. john's, car rental, car hire, vehicle rental, car rental near me, airport car rental, cheap car rental, car booking, online car rental, city car rental, car rental services, weekend car rental, business car hire, caribbean rentals">
    <meta name="description" content="<?php echo $description ?>">
    <meta property="og:title" content="<?php echo $base_title ?>">
    <meta property="og:description" content="<?php echo $description; ?>">
    <!-- favicon begin -->
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="/assets/images/favicon/site.webmanifest">
    <!-- favicon end -->
    <title><?php echo $title ?></title>
    <link type="text/css" rel="stylesheet" href="/styles/main.css">
    <?php if (isset($page) && file_exists("styles/{$page}.css")) { ?>
        <link type="text/css" rel="stylesheet" href="/styles/<?php echo $page ?>.css">
    <?php }
    if (isset($extra_css)) { ?>
        <link type="text/css" rel="stylesheet" href="/styles/<?php echo $extra_css ?>.css">
    <?php } ?>
    <script defer src="/js/main.js"></script>

    <!-- BEGIN PLUGINS -->
    <link type="text/css" rel="stylesheet" href="/plugins/font-awesome/font-awesome.all.min.css">
    <link type="text/css" rel="stylesheet" href="/plugins/sweetalert2/styles/sweetalert2.min.css">
    <link type="text/css" rel="stylesheet" href="/plugins/flatpickr/styles/flatpickr.min.css">
    <link type="text/css" rel="stylesheet" href="/plugins/flatpickr/styles/theme.min.css">
    <link type="text/css" rel="stylesheet" href="/plugins/slick/slick/slick.css">
    <link type="text/css" rel="stylesheet" href="/plugins/slick/slick/slick-theme.css">
    <script src="/plugins/jquery/jquery-3.7.1.min.js"></script>
    <script src="/plugins/sweetalert2/js/sweetalert2.all.min.js"></script>
    <script src="/plugins/flatpickr/js/flatpickr.v4.6.13.min.js"></script>
    <script src="/plugins/slick/slick/slick.min.js"></script>
    <!-- END PLUGINS -->

</head>

<body id="<?php echo $page ?>-page">

    <div class="overlay"></div>

    <header>
        <div class="inner">

            <a href="/">
                <img src="/assets/images/logo.png" alt="Website logo">
            </a>

            <nav>
                <a href="/">Home</a>
                <a href="/book-now.php">Book Now</a>
                <a href="/about.php">About Us / Antigua</a>
                <a href="/faq.php">FAQ</a>
                <a href="/contact.php">Contact</a>
            </nav>

            <div id="hamburger-button">
                <div id="hamburger-icon">
                    <div class="hamburger-line"></div>
                    <div class="hamburger-line"></div>
                    <div class="hamburger-line"></div>
                </div>
            </div>

            <div id="hamburger-nav">
                <svg id="close-hamburger" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
                <nav>
                    <a href="/">Home</a>
                    <a href="/book-now.php">Book Now</a>
                    <a href="/about.php">About Us / Antigua</a>
                    <a href="/faq.php">FAQ</a>
                    <a href="/contact.php">Contact</a>
                </nav>
            </div>
        </div>
    </header>