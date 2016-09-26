<?php

namespace App;

//A customer who has already bought for over € 1000, gets a discount of 10% on the whole order.
class Discount implements \JsonSerializable
{
    private $type;
    private $data;

    /**
     * Discount constructor.
     *
     * @param $type
     * @param array $data
     */
    public function __construct($type, array $data)
    {
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * {@inheritDoc}
     */
    function jsonSerialize()
    {
        return [
            'type' => $this->type,
            'data' => $this->data,
        ];
    }
}