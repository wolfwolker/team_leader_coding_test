<?php

namespace Tests;

use App\CustomerDiscountProvider;
use App\Discount;
use App\HttpClient;

/**
 * Created by PhpStorm.
 * User: noel
 * Date: 26/09/16
 * Time: 20:40
 */
class CustomerDiscountProviderTest extends \PHPUnit_Framework_TestCase
{
    function testCalculateDiscountsOK()
    {
        $httpClient = \Mockery::mock(HttpClient::class);
        $httpClient
            ->shouldReceive("getCustomer")
            ->andReturn(['revenue' => 2000]);

        $subject = new CustomerDiscountProvider($httpClient);

        $discounts = $subject->calculateDiscounts(['customer-id' => 1]);
        $this->assertCount(1, $discounts);
        $this->assertInstanceOf(Discount::class, reset($discounts));
    }

    function testCalculateDiscountsKO()
    {
        $httpClient = \Mockery::mock(HttpClient::class);
        $httpClient
            ->shouldReceive("getCustomer")
            ->andReturn(['revenue' => 200]);

        $subject = new CustomerDiscountProvider($httpClient);

        $discounts = $subject->calculateDiscounts(['customer-id' => 1]);
        $this->assertCount(0, $discounts);
    }
}
