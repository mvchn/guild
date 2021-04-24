<?php

namespace App\Entity;

use App\Repository\OrderAttributeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderAttributeRepository::class)
 */
class OrderAttribute
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="orderAttributes")
     */
    private $ordr;

    /**
     * @ORM\ManyToOne(targetEntity=Attribute::class, cascade={"persist"})
     */
    private $attribute;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrdr(): ?Order
    {
        return $this->ordr;
    }

    public function setOrdr(?Order $ordr): self
    {
        $this->ordr = $ordr;

        return $this;
    }

    public function getAttribute(): ?Attribute
    {
        return $this->attribute;
    }

    public function setAttribute(?Attribute $attribute): self
    {
        $this->attribute = $attribute;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
