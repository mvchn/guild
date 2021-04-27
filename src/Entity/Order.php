<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="orders")
 * @ORM\HasLifecycleCallbacks()
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="uuid")
     */
    private $uuid;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=Product::class)
     */
    private $products;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=OrderAttribute::class, mappedBy="order", cascade="persist")
     */
    private $orderAttributes;

    /**
     * @ORM\OneToOne(targetEntity=Stock::class, cascade={"persist", "remove"})
     */
    private $stock;

    public function __construct(string $uuidValue = null)
    {
        $this->uuid = $uuidValue ? Uuid::fromString($uuidValue) : Uuid::v4();
        $this->products = new ArrayCollection();
        $this->status = 'new';
        $this->uuid =  Uuid::v4();
        $this->orderAttributes = new ArrayCollection();
    }

    public function __toString()
    {
        return (string)$this->uuid;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamps()
    {
        if(null === $this->createdAt) {
            $this->createdAt = new \DateTime();
        }

        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid() : ?Uuid
    {
        return $this->uuid;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        $this->products->removeElement($product);

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|OrderAttribute[]
     */
    public function getOrderAttributes(): Collection
    {
        return $this->orderAttributes;
    }

    public function addOrderAttribute(OrderAttribute $orderAttribute): self
    {
        if (!$this->orderAttributes->contains($orderAttribute)) {
            $this->orderAttributes[] = $orderAttribute;
            $orderAttribute->setOrder($this);
        }

        return $this;
    }

    public function removeOrderAttribute(OrderAttribute $orderAttribute): self
    {
        if ($this->orderAttributes->removeElement($orderAttribute)) {
            // set the owning side to null (unless already changed)
            if ($orderAttribute->getOrder() === $this) {
                $orderAttribute->setOrder(null);
            }
        }

        return $this;
    }

    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    public function setStock(?Stock $stock): self
    {
        $this->stock = $stock;

        return $this;
    }
}
