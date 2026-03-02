<?php

namespace App\Dto;

use App\Constants\InvoiceMessages;
use Symfony\Component\Validator\Constraints as Assert;

class InvoiceItemDto
{
    #[Assert\NotBlank(message: InvoiceMessages::DESCRIPTION_REQUIRED)]
    public string $description = '';

    #[Assert\Positive(message: InvoiceMessages::QUANTITY_POSITIVE)]
    public int $quantity = 1;

    #[Assert\PositiveOrZero(message: InvoiceMessages::PRICE_POSITIVE)]
    public float $price = 0;

    #[Assert\Range(min: 0, max: 100, notInRangeMessage: InvoiceMessages::TAX_RATE_RANGE)]
    public float $taxRate = 0;

    #[Assert\Range(min: 0, max: 100, notInRangeMessage: InvoiceMessages::DISCOUNT_RANGE)]
    public float $discount = 0;
}