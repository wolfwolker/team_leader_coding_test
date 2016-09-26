<?php

namespace App;

class DiscountHandler
{
    /** @var DiscountProviderInterface[] */
    private $discountProviders = [];

    /**
     * @param array $order
     *
     * @return Discount[]
     */
    public function calculateDiscounts(array $order)
    {
        $discounts = [];

        foreach ($this->discountProviders as $provider) {
            foreach ($provider->calculateDiscounts($order) as $discount) {
                $discounts[] = $discount;
            }
        }

        return $discounts;
    }

    public function addDiscountProvider(DiscountProviderInterface $provider)
    {
        //todo prevent provider duplications
        $this->discountProviders[] = $provider;
    }
}