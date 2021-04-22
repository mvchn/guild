<?php

namespace App\Event;

use App\Entity\Product;
use Symfony\Contracts\EventDispatcher\Event;

class ProductEvent extends Event
{
    public const NEW = 'product.new';
    public const SHOW_ORDER = 'product.order';
    public const CREATE_ORDER = 'create.order';

    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }
}