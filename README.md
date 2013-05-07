WVNT
====

I WVNT is a self-hosted web interface for your Svpply wants. 

Example can be found here: http://maxk.me/svpply.php

The PHP script requires a OpenExchangeRates App id which you can get from here: https://openexchangerates.org/signup/free (free plan gives you 1000 requests p/m.

Beware - it looks terrible. If anybody would like to create a pull request making it look a bit nicer, you will be greatly thanked!

Usage:

 - Upload to your website
 - Enable APC and cURL
 - Update the `$svpply_username`, `$openexchange_id`, and $gbp (change to your ISO currency code) variables.
 - Enjoy.
