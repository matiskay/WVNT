<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use SvpplyUsers\SvpplyUsers;

$app = new Application();

require __DIR__ . '/../config/config.php';

function get_total_price($products) {
  $total = 0;

  foreach ($products as $product) {
    $total = $total + $product->price;
  }

  return $total;
}

$app->get('/', function () use ($app) {
  return $app['twig']->render('index.html');
});

$app->get('/{user_name}', function ($user_name) use ($app) {
  $svpply_user = new SvpplyUsers($user_name);
  $products = $svpply_user->get_products();
  if ($products) {
    $total_price = get_total_price($products);

    return $app['twig']->render('user.html',
      array(
        'products' => $products,
        'user_name' => $user_name,
        'total_price' => $total_price,
      )
    );
  }
  return $app['twig']->render('index.html', array(
    'message' => $user_name,
  ));
});

return $app;
