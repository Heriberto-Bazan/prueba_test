<?php

namespace App\Tests\Validator;

use App\Dto\InvoiceItemDto;
use App\Dto\InvoiceRequestDto;
use App\Exception\InvoiceValidationException;
use App\Validator\InvoiceValidator;
use PHPUnit\Framework\TestCase;

class InvoiceValidatorTest extends TestCase
{
    private InvoiceValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new InvoiceValidator();
    }

    public function testValidInvoice(): void
    {
        $dto = $this->createValidDto();
        
        $this->validator->validate($dto);
        $this->assertTrue(true);
    }

    public function testInvalidItemType(): void
    {
        $dto = $this->createValidDto();
        $dto->itemType = 'invalido';

        $this->expectException(InvoiceValidationException::class);
        $this->validator->validate($dto);
    }

    public function testDueDateBeforeIssueDate(): void
    {
        $dto = $this->createValidDto();
        $dto->issueDate = '2026-03-01';
        $dto->dueDate = '2026-02-01';

        $this->expectException(InvoiceValidationException::class);
        $this->validator->validate($dto);
    }

    public function testDueDateAfterIssueDateIsValid(): void
    {
        $dto = $this->createValidDto();
        $dto->issueDate = '2026-02-01';
        $dto->dueDate = '2026-03-01';

        $this->validator->validate($dto);
        $this->assertTrue(true);
    }

    public function testEmptyItems(): void
    {
        $dto = $this->createValidDto();
        $dto->items = [];

        $this->expectException(InvoiceValidationException::class);
        $this->validator->validate($dto);
    }

    public function testItemWithZeroAmount(): void
    {
        $dto = $this->createValidDto();
        $dto->items[0]->price = 0;

        $this->expectException(InvoiceValidationException::class);
        $this->validator->validate($dto);
    }

    private function createValidDto(): InvoiceRequestDto
    {
        $dto = new InvoiceRequestDto();
        $dto->invoiceNumber = 'F-001';
        $dto->issueDate = '2026-02-28';
        $dto->fromName = 'Mi Empresa';
        $dto->toName = 'Cliente';
        $dto->itemType = 'servicio';

        $item = new InvoiceItemDto();
        $item->description = 'Consultoria';
        $item->quantity = 1;
        $item->price = 1000;
        $item->taxRate = 16;
        $item->discount = 0;

        $dto->items = [$item];

        return $dto;
    }
}
