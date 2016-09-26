<?php

namespace Tests;

use App\CustomerDiscountProvider;
use App\Discount;
use App\HttpClient;
use App\ProductDiscountProvider;

/**
 * Created by PhpStorm.
 * User: noel
 * Date: 26/09/16
 * Time: 20:40
 */
class ProductDiscountProviderTest extends \PHPUnit_Framework_TestCase
{
    function testCalculateSwitchesDiscounts()
    {
        $httpClient = \Mockery::mock(HttpClient::class);
        $httpClient
            ->shouldReceive("getProduct")
            ->andReturn(['category' => "2"]);

        $subject = new ProductDiscountProvider($httpClient);

        /** @var Discount[] $discounts */
        $discounts = $subject->calculateSwitchesDiscounts(['items' => [['product-id' => 3, 'quantity' => 10]]]);
        $this->assertCount(1, $discounts);
        $this->assertInstanceOf(Discount::class, reset($discounts));
        $this->assertEquals(2, $discounts[0]->jsonSerialize()['data']['quantity']);
        $this->assertEquals(0, $discounts[0]->jsonSerialize()['data']['unit-price']);
        $this->assertEquals('add-product', $discounts[0]->jsonSerialize()['type']);
    }

    function testCalculateToolsDiscounts()
    {
        $httpClient = \Mockery::mock(HttpClient::class);
        $httpClient
            ->shouldReceive("getProduct")
            ->andReturn(['category' => "1"]);

        $subject = new ProductDiscountProvider($httpClient);

        /** @var Discount[] $discounts */
        $discounts = $subject->calculateToolsDiscounts(
            ['items' => [['product-id' => 3, 'quantity' => 2, 'unit-price' => 4]]]
        );
        $this->assertCount(1, $discounts);
        $this->assertEquals('-20%', $discounts[0]->jsonSerialize()['data']['value']);
        $this->assertEquals('product', $discounts[0]->jsonSerialize()['type']);
    }
}
