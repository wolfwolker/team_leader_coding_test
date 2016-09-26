<?php

namespace App;

//this class is to fake other microservices calls
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HttpClient
{
    private $products;
    private $customers;

    public function __construct()
    {
        $this->products = json_decode(file_get_contents(__DIR__.'/../../data/products.json'), true);
        $this->customers = json_decode(file_get_contents(__DIR__.'/../../data/customers.json'), true);

        $this->products = array_combine(array_column($this->products, 'id'), $this->products);
        $this->customers = array_combine(array_column($this->customers, 'id'), $this->customers);
    }

    public function getProduct($id)
    {
        if (empty($this->products[$id])) {
            throw new NotFoundHttpException("Product $id does not exist");
        }

        return $this->products[$id];
    }

    public function getCustomer($id)
    {
        if (empty($this->customers[$id])) {
            throw new NotFoundHttpException("Customer $id does not exist");
        }

        return $this->customers[$id];
    }
}