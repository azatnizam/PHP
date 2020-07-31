<?php

namespace Ozycast\App\Models\Discount;

use Ozycast\App\Interfaces\Discount;
use Ozycast\App\Models\OrderBuilder;

class DiscountFreeProduct implements Discount
{
    protected $discount = 0;

    public function applyDiscount(OrderBuilder $builder)
    {
        // + проверям доступна ли скидка на товары и действительно ли она
        $this->discount = 300;
        return 1;
    }

    /**
     * @return int
     */
    public function getDiscount(): int
    {
        return $this->discount;
    }
}