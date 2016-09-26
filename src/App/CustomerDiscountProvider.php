<?php

namespace App;

//A customer who has already bought for over € 1000, gets a discount of 10% on the whole order.
class CustomerDiscountProvider implements DiscountProviderInterface
{
    /** @var HttpClient */
    private $httpClient;

    /**
     * CustomerDiscountProvider constructor.
     *
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function calculateDiscounts(array $order)
    {
        $customer = $this->httpClient->getCustomer($order['customer-id']);

        if ($customer['revenue'] > 1000) { //todo improve this setting the revenue threshold as a class constructor argument
            return [new Discount('order', ['value' => '-10%'])];
        }

        return [];
    }
}