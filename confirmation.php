<?php

$code = htmlspecialchars($_GET['code']);
if($code != md5('Crystal'.$_SERVER['SERVER_ADDR'].'Software')){
    echo 'Code validation error'; exit;
}
$lang = htmlspecialchars($_GET['lang']);
$order_id = htmlspecialchars($_GET['id_order']);
$mid = htmlspecialchars($_GET['mid']);
$email = htmlspecialchars($_GET['email']);
$date_add = htmlspecialchars($_GET['date_add']);
//strtotime("+".rand(0,7)." days")
//$newdate = date('Y-m-d', strtotime("+".rand(0,7)." days"));
$newdate = date('Y-m-d', time());
?>
<!doctype html>
<html id="html" lang="<?php echo $lang; ?>">
<head>
    <meta charset="utf-8">

    <title>Order Confirmed</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<body><?php
echo '<script src="https://apis.google.com/js/platform.js?onload=renderOptIn"
            data-keepinline="true"   async defer></script> <script data-keepinline="true">window.renderOptIn = function() {
            window.gapi.load(\'surveyoptin\', function() {
                window.gapi.surveyoptin.render(
                    {
                        // REQUIRED
                        "merchant_id":"'.$mid.'",
                        "order_id": "'.$order_id.'",
                        "email": "'.$email.'",
                        "delivery_country": "'.$lang.'",
                        "estimated_delivery_date": "'.$newdate.'",

                        // OPTIONAL
                        "opt_in_style": "CENTER_DIALOG"
                    });
            });
        }</script>';
?>
</body>
</html>
