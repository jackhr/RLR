<?php

function getDifferenceInDays($pickUpDate, $returnDate)
{
    $start = new DateTime($pickUpDate);
    $end = new DateTime($returnDate);
    $diff = $start->diff($end);
    return $diff->days;
}

function makePriceString($rate, $days = 1, $currency = "USD")
{
    // Currency can only be USD or EC
    if ($currency !== "USD" && $currency !== "EC") $currency = "USD";
    return '$' . $currency . ((int)$rate * $days);
}

function generateRandomKey($length = 24)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $key = '';
    for ($i = 0; $i < $length; $i++) {
        $key .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $key;
}

function respond($res)
{
    echo json_encode($res);
    die();
}

function generateEmailBody($first_name, $last_name, $country_region, $street, $town_city, $state_county, $phone, $email, $order_request_id, $vehicle, $add_ons, $itinerary, $days, $sub_total, $timestamp, $key)
{
    function generateAddress($first_name, $last_name, $country_region, $street, $town_city, $state_county, $phone, $email)
    {
        return "{$first_name} {$last_name}<br>{$street}<br>{$town_city}, {$state_county}<br>{$country_region}<br><a href=\"tel:{$phone}\" style=\"color:#d4a32a;font-weight:normal;text-decoration:underline\" target=\"_blank\">{$phone}</a><br><a href=\"mailto:$email\" target=\"_blank\">{$email}</a>";
    }

    $fontFamily = 'font-family:"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;';

    $body = '
    
        <div style="background-color:#f7f7f7;margin:0;padding:70px 0;width:100%">
            <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color:#ffffff;border:1px solid #dedede;border-radius:3px;margin: auto;">
                <tbody>
                    <tr>
                        <td align="center" valign="top">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#d4a32a;color:#ffffff;border-bottom:0;font-weight:bold;line-height:100%;vertical-align:middle;' . $fontFamily . 'border-radius:3px 3px 0 0">
                                <tbody>
                                    <tr>
                                        <td style="padding:36px 48px;display:block">
                                            <h1 style="' . $fontFamily . 'font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:center;color:#ffffff;background-color:inherit">Thank you for your rental request</h1>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top">
                            <table border="0" cellpadding="0" cellspacing="0" width="600">
                                <tbody>
                                    <tr>
                                        <td valign="top" style="background-color:#ffffff">
                                            <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                <tbody>
                                                    <tr>
                                                        <td valign="top" style="padding:48px 48px 32px">
                                                            <div style="color:#636363;' . $fontFamily . 'font-size:14px;line-height:150%;text-align:left">
                                                                <p style="margin:0 0 16px">Hi ' . $first_name . ' ' . $last_name . ',</p>
                                                                <p style="margin:0 0 16px">Just to let you know - we\'ve received your order #43, and it is now being processed.</p>
                                                                <p style="margin:0 0 16px">Pay with cash or card when you pick-up your vehicle.</p>
                                                                <h2 style="color:#d4a32a;display:block;' . $fontFamily . 'font-size:18px;font-weight:bold;line-height:130%;margin:0 0 18px;text-align:left">Order #' . $order_request_id . ' (<time datetime="' . gmdate("Y-m-d\TH:i:s\+00:00", $timestamp) . '">' . (date("F d, Y", $timestamp)) . '</time>)</h2>
                                                                <table cellspacing="0" cellpadding="6" border="1" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;width:100%;font-family:\"Helvetica Neue\"Helvetica,Roboto,Arial,sans-serif">
                                                                    <thead>
                                                                        <tr>
                                                                            <th scope="col" style="' . $fontFamily . 'color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">Product</th>
                                                                            <th scope="col" style="' . $fontFamily . 'color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">Quantity</th>
                                                                            <th scope="col" style="' . $fontFamily . 'color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">Price</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="' . $fontFamily . 'color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;word-wrap:break-word">' . $vehicle['name'] . '</td>
                                                                            <td style="' . $fontFamily . 'color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:Helvetica,Roboto,Arial,sans-serif">' . $days . ' days</td>
                                                                            <td style="' . $fontFamily . 'color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:Helvetica,Roboto,Arial,sans-serif">
                                                                                <span><u></u>USD<span>$</span>' . ($vehicle['price_day_USD'] * $days) . '<u></u></span>
                                                                            </td>
                                                                        </tr>';
    foreach ($add_ons as $add_on) {
        $body .= '                                                      <tr>
                                                                            <td style="' . $fontFamily . 'color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;word-wrap:break-word">' . $add_on['name'] . '</td>
                                                                            <td style="' . $fontFamily . 'color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:Helvetica,Roboto,Arial,sans-serif">1</td>
                                                                            <td style="' . $fontFamily . 'color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:Helvetica,Roboto,Arial,sans-serif">
                                                                                <span><u></u>USD<span>$</span>' . $add_on['cost'] . '<u></u></span>
                                                                            </td>
                                                                        </tr>';
    }
    $body .= '
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <tr>
                                                                            <th scope="row" colspan="2" style="' . $fontFamily . 'color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">Pickup date</th>
                                                                            <td style="' . $fontFamily . 'color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">' . $itinerary['pickUpDate']['altValue'] . '</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th scope="row" colspan="2" style="' . $fontFamily . 'color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">Pickup location</th>
                                                                            <td style="' . $fontFamily . 'color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">' . $itinerary['pickUpLocation'] . '</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th scope="row" colspan="2" style="' . $fontFamily . 'color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">Return date</th>
                                                                            <td style="' . $fontFamily . 'color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">' . $itinerary['returnDate']['altValue'] . '</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th scope="row" colspan="2" style="' . $fontFamily . 'color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">Return location</th>
                                                                            <td style="' . $fontFamily . 'color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">' . $itinerary['returnLocation'] . '</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th scope="row" colspan="2" style="' . $fontFamily . 'color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;border-top-width:4px">Subtotal</th>
                                                                            <td style="' . $fontFamily . 'color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;border-top-width:4px"><span><u></u>USD<span>$</span>' . $sub_total . '<u></u></span></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th scope="row" colspan="2" style="' . $fontFamily . 'color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">Payment method</th>
                                                                            <td style="' . $fontFamily . 'color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">Pay at Pickup</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th scope="row" colspan="2" style="' . $fontFamily . 'color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">Total</th>
                                                                            <td style="' . $fontFamily . 'color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left"><span><u></u>USD<span>$</span>' . $sub_total . '<u></u></span></td>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                                <table cellspacing="0" cellpadding="0" border="0" style="width:100%;vertical-align:top;margin-bottom:40px;padding:0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td valign="top" width="50%" style="text-align:left;' . $fontFamily . 'border:0;padding:0">
                                                                                <h2 style="color:#d4a32a;display:block;' . $fontFamily . 'font-size:18px;font-weight:bold;line-height:130%;margin:18px 0;text-align:left">Billing address</h2>

                                                                                <address style="' . $fontFamily . 'padding:12px;color:#636363;border:1px solid #e5e5e5">' . generateAddress($first_name, $last_name, $country_region, $street, $town_city, $state_county, $phone, $email) . ' </address>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <p style="margin:0 0 16px;text-align: center;">Thanks for using <a href="http://www.rlrentalsantigua.com/confirmation.php?key=' . $key . '" target="_blank">www.rlrentalsantigua.com</a>!</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                <tbody>
                    <tr>
                        <td align="center" valign="top">
                            <table border="0" cellpadding="10" cellspacing="0" width="600">
                                <tbody>
                                    <tr>
                                        <td valign="top" style="padding:0;border-radius:6px">
                                            <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                                <tbody>
                                                    <tr>
                                                        <td colspan="2" valign="middle" style="border-radius:6px;border:0;color:#8a8a8a;' . $fontFamily . 'font-size:12px;line-height:150%;text-align:center;padding:24px 0">
                                                            <p style="margin:0 0 16px">RL Rentals (Antigua)</p>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    ';

    return $body;
}
