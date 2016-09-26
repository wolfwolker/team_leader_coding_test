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
class DiscountTest extends \PHPUnit_Framework_TestCase
{
    function testSerialization()
    {
        $subject = new Discount('foo', ['bar']);

        $result = $subject->jsonSerialize();

        $this->assertEquals(['type' => 'foo', 'data' => ['bar']], $result);
    }
}
