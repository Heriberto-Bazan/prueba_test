<?php

namespace App\Validator;

use App\Constants\InvoiceMessages;
use App\Dto\InvoiceRequestDto;
use App\Exception\InvoiceValidationException;

class InvoiceValidator
{
    public function validate(InvoiceRequestDto $dto): void
    {
        $this->validateItemType($dto);
        $this->validateDates($dto);
        $this->validateItems($dto);
    }

    private function validateItemType(InvoiceRequestDto $dto): void
    {
        if (!in_array($dto->itemType, ['producto', 'servicio'])) {
            throw new InvoiceValidationException(InvoiceMessages::INVALID_ITEM_TYPE);
        }
    }

    private function validateDates(InvoiceRequestDto $dto): void
    {
        if ($dto->dueDate !== null) {
            $issue = new \DateTime($dto->issueDate);
            $due = new \DateTime($dto->dueDate);

            if ($due < $issue) {
                throw new InvoiceValidationException(InvoiceMessages::DUE_DATE_BEFORE_ISSUE);
            }
        }
    }

    private function validateItems(InvoiceRequestDto $dto): void
    {
        if (empty($dto->items)) {
            throw new InvoiceValidationException(InvoiceMessages::EMPTY_ITEMS);
        }

        foreach ($dto->items as $index => $item) {
            if ($item->price * $item->quantity <= 0) {
                throw new InvoiceValidationException(
                    sprintf(InvoiceMessages::INVALID_ITEM_AMOUNT, $index + 1)
                );
            }
        }
    }
}