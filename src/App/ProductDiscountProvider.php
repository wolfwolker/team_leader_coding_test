<?php

namespace App;

//For every products of category "Switches" (id 2), when you buy five, you get a sixth for free.
//If you buy two or more products of category "Tools" (id 1), you get a 20% discount on the cheapest product.

//todo we could improve this by splitting this provider into 2 different ones or using pre-configured semantic rules
// (ie using https://hoa-project.net/En/Literature/Hack/Ruler.html)
class ProductDiscountProvider implements DiscountProviderInterface
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

    public function calculateSwitchesDiscounts(array $order)
    {
        $discounts = [];

        foreach ($order['items'] as $lineNumber => $item) {
            $product = $this->httpClient->getProduct($item['product-id']);
            $qty = floor((int)$item['quantity'] / 5);
            if ($product['category'] == "2" && $qty > 0) { //this applies also to 10,15,20,etc
                $discounts[] = new Discount(
                    'add-product',
                    ['line-number' => $lineNumber, 'quantity' => $qty, 'unit-price' => 0]
                );
            }
        }

        return $discounts;
    }

    public function calculateDiscounts(array $order)
    {
        if (empty($order['items'])) {
            return null;
        }

        return array_merge($this->calculateSwitchesDiscounts($order), $this->calculateToolsDiscounts($order));
    }

    public function calculateToolsDiscounts(array $order)
    {
        $tools = 0;
        $cheapestToolPrice = $cheapestToolLineNumber = null;
        $discounts = [];

        foreach ($order['items'] as $lineNumber => $item) {
            $product = $this->httpClient->getProduct($item['product-id']);
            if ($product['category'] == "1") {
                $tools += $item['quantity'];
                if (null === $cheapestToolPrice || $cheapestToolPrice > $item['unit-price']) {
                    $cheapestToolPrice = (float)$item['unit-price'];
                    $cheapestToolLineNumber = $lineNumber;
                }
            }
        }

        if ($tools >= 2) {
            $discounts[] = new Discount('product', ['line-number' => $cheapestToolLineNumber, 'value' => '-20%']);
        }

        return $discounts;
    }
}