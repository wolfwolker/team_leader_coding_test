<?php

namespace App;

interface DiscountProviderInterface
{
    public function calculateDiscounts(array $order);
}