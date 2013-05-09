<?php

namespace SvpplyUsers;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\BadResponseException;

class SvpplyUsers {
  const API_END_POINT = 'https://api.svpply.com';
  const PRODUCTS_END_POINT = 'wants/products.json';

  function __construct($user_name) {
    $this->client = new Client(self::API_END_POINT . '/{version}/users/{user_name}', array(
      'version' => 'v1',
      'user_name' => $user_name,
    ));
  }

  function get_products() {
    try {
      $request = $this->client->get(self::PRODUCTS_END_POINT)->send();
      $json = $request->getBody();
      $data = json_decode($json);
      return $data->response->products;
    } catch (BadResponseException $e) {
      return FALSE;
    }
  }
}