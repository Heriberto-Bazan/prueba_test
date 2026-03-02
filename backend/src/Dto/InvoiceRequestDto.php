<?php

namespace App\Dto;

use App\Constants\InvoiceMessages;
use Symfony\Component\Validator\Constraints as Assert;

class InvoiceRequestDto
{
    #[Assert\NotBlank(message: InvoiceMessages::INVOICE_NUMBER_REQUIRED)]
    public string $invoiceNumber = '';

    #[Assert\NotBlank(message: InvoiceMessages::ISSUE_DATE_REQUIRED)]
    public string $issueDate = '';

    public ?string $dueDate = null;

    #[Assert\NotBlank(message: InvoiceMessages::FROM_NAME_REQUIRED)]
    public string $fromName = '';

    public ?string $fromEmail = null;

    public ?string $fromAddress = null;

    #[Assert\NotBlank(message: InvoiceMessages::TO_NAME_REQUIRED)]
    public string $toName = '';

    public ?string $toEmail = null;

    public ?string $toAddress = null;

    #[Assert\Choice(choices: ['producto', 'servicio'], message: InvoiceMessages::ITEM_TYPE_INVALID)]
    public string $itemType = 'servicio';

    public string $currency = 'MXN';

    public ?string $notes = null;

    #[Assert\NotBlank(message: InvoiceMessages::ITEMS_REQUIRED)]
    #[Assert\Count(min: 1, minMessage: InvoiceMessages::ITEMS_REQUIRED)]
    #[Assert\Valid]
    public array $items = [];
}