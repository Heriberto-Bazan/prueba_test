<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'invoices')]
#[ORM\HasLifecycleCallbacks]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private string $invoiceNumber = '';

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $issueDate = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $dueDate = null;

    #[ORM\Column(length: 255)]
    private string $fromName = '';

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fromEmail = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $fromAddress = null;

    #[ORM\Column(length: 255)]
    private string $toName = '';

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $toEmail = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $toAddress = null;

    #[ORM\Column(length: 20)]
    private string $itemType = 'servicio';

    #[ORM\Column(length: 10)]
    private string $currency = 'MXN';

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $subtotal = '0';

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $totalTax = '0';

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $totalDiscount = '0';

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $grandTotal = '0';

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTime();
    }

    #[ORM\OneToMany(targetEntity: InvoiceItem::class, mappedBy: 'invoice', cascade: ['persist', 'remove'])]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = null;
    }

    public function getId(): ?int { return $this->id; }

    public function getInvoiceNumber(): string { return $this->invoiceNumber; }
    public function setInvoiceNumber(string $val): self { $this->invoiceNumber = $val; return $this; }

    public function getIssueDate(): ?\DateTimeInterface { return $this->issueDate; }
    public function setIssueDate(\DateTimeInterface $val): self { $this->issueDate = $val; return $this; }

    public function getDueDate(): ?\DateTimeInterface { return $this->dueDate; }
    public function setDueDate(?\DateTimeInterface $val): self { $this->dueDate = $val; return $this; }

    public function getFromName(): string { return $this->fromName; }
    public function setFromName(string $val): self { $this->fromName = $val; return $this; }

    public function getFromEmail(): ?string { return $this->fromEmail; }
    public function setFromEmail(?string $val): self { $this->fromEmail = $val; return $this; }

    public function getFromAddress(): ?string { return $this->fromAddress; }
    public function setFromAddress(?string $val): self { $this->fromAddress = $val; return $this; }

    public function getToName(): string { return $this->toName; }
    public function setToName(string $val): self { $this->toName = $val; return $this; }

    public function getToEmail(): ?string { return $this->toEmail; }
    public function setToEmail(?string $val): self { $this->toEmail = $val; return $this; }

    public function getToAddress(): ?string { return $this->toAddress; }
    public function setToAddress(?string $val): self { $this->toAddress = $val; return $this; }

    public function getItemType(): string { return $this->itemType; }
    public function setItemType(string $val): self { $this->itemType = $val; return $this; }

    public function getCurrency(): string { return $this->currency; }
    public function setCurrency(string $val): self { $this->currency = $val; return $this; }

    public function getNotes(): ?string { return $this->notes; }
    public function setNotes(?string $val): self { $this->notes = $val; return $this; }

    public function getSubtotal(): string { return $this->subtotal; }
    public function setSubtotal(string $val): self { $this->subtotal = $val; return $this; }

    public function getTotalTax(): string { return $this->totalTax; }
    public function setTotalTax(string $val): self { $this->totalTax = $val; return $this; }

    public function getTotalDiscount(): string { return $this->totalDiscount; }
    public function setTotalDiscount(string $val): self { $this->totalDiscount = $val; return $this; }

    public function getGrandTotal(): string { return $this->grandTotal; }
    public function setGrandTotal(string $val): self { $this->grandTotal = $val; return $this; }

    public function getCreatedAt(): ?\DateTimeInterface { return $this->createdAt; }

    public function getUpdatedAt(): ?\DateTimeInterface { return $this->updatedAt; }

    public function setUpdatedAt(?\DateTimeInterface $val): self { $this->updatedAt = $val; return $this; }

    public function getItems(): Collection { return $this->items; }

    public function addItem(InvoiceItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setInvoice($this);
        }
        return $this;
    }

    public function removeItem(InvoiceItem $item): self
    {
        $this->items->removeElement($item);
        return $this;
    }
}