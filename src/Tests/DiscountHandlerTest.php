<?php

namespace Tests;

use App\CustomerDiscountProvider;
use App\Discount;
use App\DiscountHandler;
use App\DiscountProviderInterface;
use App\HttpClient;

/**
 * Created by PhpStorm.
 * User: noel
 * Date: 26/09/16
 * Time: 20:40
 */
class DiscountHandlerTest extends \PHPUnit_Framework_TestCase
{
    function testCalculateDiscounts()
    {
        $subject = new DiscountHandler();
        $order = ['id' => 'foo'];
        $result = [new Discount('asdf', ['qwer'])];
        $subject->addDiscountProvider(
            \Mockery::mock(DiscountProviderInterface::class)
                ->shouldReceive('calculateDiscounts')
                ->with($order)
                ->andReturn($result)
                ->getMock()
        );
        $actual = $subject->calculateDiscounts($order);
        $this->assertEquals($result, $actual);
    }
}
