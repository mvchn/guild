<?php

namespace App\Event;

use App\Entity\Attribute;
use Symfony\Contracts\EventDispatcher\Event;

class AttributeEvent extends Event
{
    public const NEW = 'attribute.new';

    protected $attribute;

    public function __construct(Attribute $attribute)
    {
        $this->attribute = $attribute;
    }

    public function getAttribute(): Attribute
    {
        return $this->attribute;
    }
}