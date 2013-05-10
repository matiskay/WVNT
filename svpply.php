<?php

/* Config */
define('SVPPLY_USERNAME', '');
define('OPEN_EXCHANGE_ID', '');

?>

<?php

function limit_text($string, $limit) {
  $string = trim($string);
  return (strlen($string) > $limit) ? substr($string, 0 , $limit) . '...' : $string;
}

function numberOfDecimals($value) {

  if ((int)$value == $value) {
    return 0;
  }

  else if (!is_numeric($value)) {
    return FALSE;
  }

  return strlen($value) - strrpos($value, '.') - 1;

}

$url = sprintf("http://api.svpply.com/v1/users/%s/wants/products.json", SVPPLY_USERNAME);

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_URL => $url,
  CURLOPT_FOLLOWLOCATION => 1
));

$response = json_decode(curl_exec($curl));

curl_close($curl);

$products = $response->response->products;

$currencies = '';

if (($currencies = apc_fetch('currency_cache')) == FALSE) {

  $url = sprintf("http://openexchangerates.org/api/latest.json?app_id=%s", OPEN_EXCHANGE_ID);

  $currencies = json_decode(file_get_contents($url));

  apc_store('currency_cache', $currencies);

  apc_store('last_updated', time());

}

else  {

  if (apc_fetch('last_updated') < (time() - (60 * 60 * 4))) {

    $url = sprintf("http://openexchangerates.org/api/latest.json?app_id=%s", OPEN_EXCHANGE_ID);

    $currencies = json_decode(file_get_contents($url));

    apc_store('currency_cache', $currencies);

    apc_store('last_updated', time());

  }

}

$gbp = $currencies->rates->GBP; // change ISO code for your currency

$price = 0;

$rows = array();

foreach ($products as $want) {


    $price += $want->price;
    $want->price *= $gbp;
    $want->price = number_format($want->price, numberOfDecimals($want->price) == 0 ? 0 : 2, ".", ",");
    $product_price = "&pound;" . $want->price;

    $row = "";

    $row .= '<div class="box product">';
    $row .= '<img src="img/placeholder.png" data-src="' . $want->image . '" />';
    $row .= '<div class="product-meta">';
    $row .= '<div class="product-price-container">';
    $row .= '<div class="product-price">' . $product_price . '</div>';
    $row .= '</div>';
    $row .= '<div class="product-name">' . limit_text($want->page_title, 15) . '</div>';
    $row .= '</div>';
    $row .= '</div>';

    $rows[] = $row;
}

$dollar_price = "$" . number_format($price, numberOfDecimals($price) == 0 ? 0 : 2, ".", ",");

$price = $price * $gbp;

$gbp_price = "&pound;" . number_format($price, numberOfDecimals($price) == 0 ? 0 : 2, ".", ",");

?>


</table>

<html>

<head>
  <title>I WVNT</title>
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,700italic,400,300,600,700' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/main.css">
</head>

<body>

<div class="header">
  <div class="container">
    <span>How much will it cost?</span>
    <a href="/" class="logo"></a>
  </div>
</div>

<div class="container">
  <div id="main_cont">
    <?php
    foreach($rows as $row) {
      echo $row;
    }
    ?>
  </div>

  <div class="total">
    <span>oh my! You have to spend</span>
    <p><?php print $dollar_price; ?></p>
    <p><?php print $gbp_price; ?></p>
  </div><!-- end .total -->
</div>

<div class="footer">
  <div class="container">
    <span>WVNT was made by a <a href="#">bunch of nerds</a></span>
    <a href="#" class="fork-it">Fork it!</a>
  </div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.1.min.js"><\/script>')</script>
<script src="js/vendor/jquery.unveil.min.js"></script>
<script src="js/plugins.js"></script>
<script src="js/main.js"></script>

</body>

</html>
