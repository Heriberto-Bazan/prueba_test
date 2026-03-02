<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'invoice_items')]
#[ORM\HasLifecycleCallbacks]
class InvoiceItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Invoice::class, inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Invoice $invoice = null;

    #[ORM\Column(length: 255)]
    private string $description = '';

    #[ORM\Column]
    private int $quantity = 1;

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $price = '0';

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2)]
    private string $taxRate = '0';

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2)]
    private string $discount = '0';

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $subtotal = '0';

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $taxAmount = '0';

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $discountAmount = '0';

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $total = '0';

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = null;
    }

    public function getId(): ?int { return $this->id; }

    public function getInvoice(): ?Invoice { return $this->invoice; }
    public function setInvoice(?Invoice $val): self { $this->invoice = $val; return $this; }

    public function getDescription(): string { return $this->description; }
    public function setDescription(string $val): self { $this->description = $val; return $this; }

    public function getQuantity(): int { return $this->quantity; }
    public function setQuantity(int $val): self { $this->quantity = $val; return $this; }

    public function getPrice(): string { return $this->price; }
    public function setPrice(string $val): self { $this->price = $val; return $this; }

    public function getTaxRate(): string { return $this->taxRate; }
    public function setTaxRate(string $val): self { $this->taxRate = $val; return $this; }

    public function getDiscount(): string { return $this->discount; }
    public function setDiscount(string $val): self { $this->discount = $val; return $this; }

    public function getSubtotal(): string { return $this->subtotal; }
    public function setSubtotal(string $val): self { $this->subtotal = $val; return $this; }

    public function getTaxAmount(): string { return $this->taxAmount; }
    public function setTaxAmount(string $val): self { $this->taxAmount = $val; return $this; }

    public function getDiscountAmount(): string { return $this->discountAmount; }
    public function setDiscountAmount(string $val): self { $this->discountAmount = $val; return $this; }

    public function getTotal(): string { return $this->total; }
    public function setTotal(string $val): self { $this->total = $val; return $this; }

    public function getCreatedAt(): ?\DateTimeInterface { return $this->createdAt; }

    public function getUpdatedAt(): ?\DateTimeInterface { return $this->updatedAt; }
    public function setUpdatedAt(?\DateTimeInterface $val): self { $this->updatedAt = $val; return $this; }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTime();
    }
}