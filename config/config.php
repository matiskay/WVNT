<?php

$app['debug'] = TRUE;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
  'twig.path' => __DIR__ . '/../templates',
));
