<?php

namespace Tests;

use App\CustomerDiscountProvider;
use App\Discount;
use App\HttpClient;
use Silex\Application;
use Silex\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Created by PhpStorm.
 * User: noel
 * Date: 26/09/16
 * Time: 20:40
 */
class AppTest extends WebTestCase
{
    function testDiscountsMicroservice()
    {
        $data = [
            'order1.json' => '[{"type":"add-product","data":{"line-number":0,"quantity":2,"unit-price":0}}]',
            'order2.json' => '[{"type":"order","data":{"value":"-10%"}},{"type":"add-product","data":{"line-number":0,"quantity":1,"unit-price":0}}]',
            'order3.json' => '[{"type":"product","data":{"line-number":0,"value":"-20%"}}]',
        ];

        $app = $this->createApplication();

        foreach ($data as $input => $output) {
            $request = Request::create(
                "/discount",
                "POST",
                [],[],[],[],file_get_contents(__DIR__.'/../../example-orders/'.$input)
            );
            $request->headers->set("Content-Type", "application/json");
            $response = $app->handle($request);

            $this->assertEquals($output, $response->getContent());
        }
    }

    /**
     * Creates the application.
     *
     * @return HttpKernelInterface
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../../web/app.php';
        $app['debug'] = true;
        unset($app['exception_handler']);

        return $app;
    }
}
