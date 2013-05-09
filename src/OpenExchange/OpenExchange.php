<?php

class OpenExchange {
  const API_END_POINT = 'http://openexchangerates.org/api/';
  const LATEST_EXCHANGE_RATE_END_POINT = 'latest.json';

  function __construct($app_id) {
    $this->client = new Client(self::API_END_POINT);
    $this->app_id = $app_id;
  }

  function get_rates() {
    $json = $this->client->get(self::LATEST_EXCHANGE_RATE_END_POINT . '?app_id=' . $this->app_id)->send()->getBody();
    $data = json_decode($json);
    return $data->rates;
  }
}