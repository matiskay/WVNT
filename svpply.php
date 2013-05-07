<html>
	<head>
		<title>I WVNT</title>
	</head>
	<style>
	#table {

	margin:0px;padding:0px;

	width:100%;

	border:1px solid #000000;

	

	-moz-border-radius-bottomleft:0px;

	-webkit-border-bottom-left-radius:0px;

	border-bottom-left-radius:0px;

	

	-moz-border-radius-bottomright:0px;

	-webkit-border-bottom-right-radius:0px;

	border-bottom-right-radius:0px;

	

	-moz-border-radius-topright:0px;

	-webkit-border-top-right-radius:0px;

	border-top-right-radius:0px;

	

	-moz-border-radius-topleft:0px;

	-webkit-border-top-left-radius:0px;

	border-top-left-radius:0px;

}#table table{

	width:100%;

	height:100%;

	margin:0px;padding:0px;

}

#table tr:last-child td:last-child {

	-moz-border-radius-bottomright:0px;

	-webkit-border-bottom-right-radius:0px;

	border-bottom-right-radius:0px;

}

#table table tr:first-child td:first-child {

	-moz-border-radius-topleft:0px;

	-webkit-border-top-left-radius:0px;

	border-top-left-radius:0px;

}

#table table tr:first-child td:last-child {

	-moz-border-radius-topright:0px;

	-webkit-border-top-right-radius:0px;

	border-top-right-radius:0px;

}#table tr:last-child td:first-child{

	-moz-border-radius-bottomleft:0px;

	-webkit-border-bottom-left-radius:0px;

	border-bottom-left-radius:0px;

}#table tr:hover td{

	

}
#table tr:nth-child(odd){ background-color:#bce1f2; }

#table tr:nth-child(even)    { background-color:#ffffff; }
#table td {

	vertical-align:middle;

	border:1px solid #000000;

	border-width:0px 1px 1px 0px;

	text-align:left;

	padding:11px;

	font-size:14px;

	font-family: HelveticaNeue, Helvetica Neue, Helvetica, Arial;

	font-weight: 300;

	color:#000000;

}

#table a {

	color: black;
	text-decoration: none;

}

#table a:hover {
	color: #adadad;
	text-decoration: underline;
}

#table tr:last-child td{

	border-width:0px 1px 0px 0px;

}#table tr td:last-child{

	border-width:0px 0px 1px 0px;

}#table tr:last-child td:last-child{

	border-width:0px 0px 0px 0px;

}

#table tr:first-child td{

		background:-o-linear-gradient(bottom, #86c2e8 5%, #86c2e8 100%);	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #86c2e8), color-stop(1, #86c2e8) );
	background:-moz-linear-gradient( center top, #86c2e8 5%, #86c2e8 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#86c2e8", endColorstr="#86c2e8");	background: -o-linear-gradient(top,#86c2e8,86c2e8);


	background-color:#86c2e8;

	border:0px solid #000000;

	text-align:center;

	border-width:0px 0px 1px 1px;

	font-size:14px;

	font-family:Arial;

	font-weight:bold;

	color:#ffffff;

}

#table tr:first-child:hover td{

	background:-o-linear-gradient(bottom, #86c2e8 5%, #86c2e8 100%);	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #86c2e8), color-stop(1, #86c2e8) );
	background:-moz-linear-gradient( center top, #86c2e8 5%, #86c2e8 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#86c2e8", endColorstr="#86c2e8");	background: -o-linear-gradient(top,#86c2e8,86c2e8);


	background-color:#86c2e8;

}

#table tr:first-child td:first-child{

	border-width:0px 0px 1px 0px;

}

#table tr:first-child td:last-child{

	border-width:0px 0px 1px 1px;

}

</style>
	<body>
		<?php

			function numberOfDecimals($value) {

			    if ((int)$value == $value) {
			        return 0;
			    }

			    else if (!is_numeric($value)) {
			        return false;
			    }

			    return strlen($value) - strrpos($value, '.') - 1;

			}

			/*	config 

			 *	@svpply_username = Your Svpply.com Username

			 *	@openexchange_id = Your Openexchangerates.org Application ID

			 */

			// $to = strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], ',')
			// echo substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 5);
			// break;

			$svpply_username = "_max";

			$openexchange_id = ""; // See above to obtain your id


			/*	end config 	*/

			echo "<h2>{$svpply_username}'s wants</h2>";

			$url = "http://api.svpply.com/v1/users/{$svpply_username}/wants/products.json";

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

			if (($currencies = apc_fetch('currency_cache')) == false) {

				$url = "http://openexchangerates.org/api/latest.json?app_id={$openexchange_id}";

				$currencies = json_decode(file_get_contents($url));

				apc_store('currency_cache', $currencies);

				apc_store('last_updated', time());

			}

			else  {

				if (apc_fetch('last_updated') < (time() - (60 * 60 * 4))) {

					$url = "http://openexchangerates.org/api/latest.json?app_id={$openexchange_id}";

					$currencies = json_decode(file_get_contents($url));

					apc_store('currency_cache', $currencies);

					apc_store('last_updated', time());

				}

			}

			$gbp = $currencies->rates->GBP; // change ISO code for your currency

			$price = 0;

			$rows = array();

			foreach ($products as $want) {

				if ((isset($_GET['category']) && $want->category == $_GET['category']) || !isset($_GET['category'])) {

					$row = "";

					$row .= "<tr>";

					$want->width = $want->width / 3;
					$want->height = $want->height / 3;

					$row .= "<td><img width=\"{$want->width}\" height=\"{$want->height}\" src=\"{$want->image}\" /></td>";

					$row .= "<td><a href=\"{$want->page_url}\">{$want->page_title}</a></td>";

					$price += $want->price;

					$want->price *= $gbp;

					$want->price = "&pound;" . number_format($want->price, numberOfDecimals($price) == 0 ? 0 : 2, ".", ",");

					$row .= "<td>{$want->price}</td>";

					$row .= "</tr>";

					$rows[] = $row;
				}

			}

			echo "Total Price (&dollar;): " . number_format($price, numberOfDecimals($price) == 0 ? 0 : 2, ".", ",") . "<br />";

			$price = $price * $gbp;

			echo "Total Price (&pound;): " . number_format($price, numberOfDecimals($price) == 0 ? 0 : 2, ".", ",") . "<br /><br />";

		?>

		<table id="table">
		<tr>
			<td>Image</td>
			<td>Name</td>
			<td>Price</td>
		</tr>

		<?

			foreach($rows as $row) {

				echo $row;

			}

		?>

		</table>

	</body>

</html>
