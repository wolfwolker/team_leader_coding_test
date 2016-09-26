<?php

use Symfony\Component\HttpFoundation\Request;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application(['debug' => true]);

//////////////////////////////////////////////services//////////////////////////////////////////////
$app['http.client'] = function () {
    return new \App\HttpClient();
};

$app['discounts.customer'] = function () use ($app) {
    return new \App\CustomerDiscountProvider($app['http.client']);
};

$app['discounts.product'] = function () use ($app) {
    return new \App\ProductDiscountProvider($app['http.client']);
};

$app['discounts.handler'] = function () use ($app){
    $handler = new \App\DiscountHandler();
    $handler->addDiscountProvider($app['discounts.customer']);
    $handler->addDiscountProvider($app['discounts.product']);

    return $handler;
};

//////////////////////////////////////////////controller//////////////////////////////////////////////
$app->post('/discount', function (Request $request) use ($app) {
    /** @var \App\DiscountHandler $handler */
    $handler = $app['discounts.handler'];

    $discounts = $handler->calculateDiscounts($request->request->all());

    return $app->json($discounts);
});

$app->get("/", function () use ($app) {
    return $app->json("it works!");
});

$app->before(function (Request $request) { //todo we should also validate the request here
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});
/////////////////////////////////////////////////////////////////////////////////////////////////////

$app->run();
return $app;
