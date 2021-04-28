<?php

namespace App\Event;

use App\Entity\Order;
use Symfony\Contracts\EventDispatcher\Event;

class OrderEvent extends Event
{
    public const NEW = 'order.new';
    public const CONFIRMED = 'order.confirmed';
    public const MODIFY = 'order.modify';

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }
}