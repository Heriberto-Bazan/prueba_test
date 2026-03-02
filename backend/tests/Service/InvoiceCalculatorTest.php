<?php

namespace App\Tests\Service;

use App\Dto\InvoiceItemDto;
use App\Dto\InvoiceRequestDto;
use App\Service\InvoiceCalculator;
use PHPUnit\Framework\TestCase;

class InvoiceCalculatorTest extends TestCase
{
    private InvoiceCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new InvoiceCalculator();
    }

    public function testCalculateServiceItem(): void
    {
        $item = new InvoiceItemDto();
        $item->description = 'Consultoria';
        $item->quantity = 1;
        $item->price = 1000;
        $item->taxRate = 16;
        $item->discount = 10;

        $result = $this->calculator->calculateItem($item, 'servicio');

        // base = 1000, descuento = 100, afterDiscount = 900, tax = 144, total = 1044
        $this->assertEquals(1000, $result['subtotal']);
        $this->assertEquals(144, $result['taxAmount']);
        $this->assertEquals(100, $result['discountAmount']);
        $this->assertEquals(1044, $result['total']);
    }

    public function testCalculateProductItem(): void
    {
        $item = new InvoiceItemDto();
        $item->description = 'Laptop';
        $item->quantity = 1;
        $item->price = 1000;
        $item->taxRate = 16;
        $item->discount = 10;

        $result = $this->calculator->calculateItem($item, 'producto');

        // base = 1000, tax = 160, withTax = 1160, descuento = 116, total = 1044
        $this->assertEquals(1000, $result['subtotal']);
        $this->assertEquals(160, $result['taxAmount']);
        $this->assertEquals(116, $result['discountAmount']);
        $this->assertEquals(1044, $result['total']);
    }

    public function testCalculateItemWithQuantity(): void
    {
        $item = new InvoiceItemDto();
        $item->description = 'Hora de soporte';
        $item->quantity = 5;
        $item->price = 200;
        $item->taxRate = 16;
        $item->discount = 0;

        $result = $this->calculator->calculateItem($item, 'servicio');

        // base = 200 * 5 = 1000, sin descuento, tax = 160, total = 1160
        $this->assertEquals(1000, $result['subtotal']);
        $this->assertEquals(160, $result['taxAmount']);
        $this->assertEquals(0, $result['discountAmount']);
        $this->assertEquals(1160, $result['total']);
    }

    public function testCalculateItemNoTaxNoDiscount(): void
    {
        $item = new InvoiceItemDto();
        $item->description = 'Item simple';
        $item->quantity = 2;
        $item->price = 500;
        $item->taxRate = 0;
        $item->discount = 0;

        $result = $this->calculator->calculateItem($item, 'servicio');

        $this->assertEquals(1000, $result['subtotal']);
        $this->assertEquals(0, $result['taxAmount']);
        $this->assertEquals(0, $result['discountAmount']);
        $this->assertEquals(1000, $result['total']);
    }

    public function testCalculateInvoiceMultipleItems(): void
    {
        $dto = new InvoiceRequestDto();
        $dto->itemType = 'servicio';

        $item1 = new InvoiceItemDto();
        $item1->description = 'Consultoria';
        $item1->quantity = 1;
        $item1->price = 1000;
        $item1->taxRate = 16;
        $item1->discount = 10;

        $item2 = new InvoiceItemDto();
        $item2->description = 'Diseno';
        $item2->quantity = 2;
        $item2->price = 500;
        $item2->taxRate = 16;
        $item2->discount = 0;

        $dto->items = [$item1, $item2];

        $result = $this->calculator->calculateInvoice($dto);

        // item1: subtotal=1000, tax=144, discount=100, total=1044
        // item2: subtotal=1000, tax=160, discount=0, total=1160
        $this->assertEquals(2000, $result['subtotal']);
        $this->assertEquals(304, $result['totalTax']);
        $this->assertEquals(100, $result['totalDiscount']);
        $this->assertEquals(2204, $result['grandTotal']);
        $this->assertCount(2, $result['items']);
    }

    public function testServiceAndProductDifferentResults(): void
    {
        $item = new InvoiceItemDto();
        $item->description = 'Test';
        $item->quantity = 1;
        $item->price = 1000;
        $item->taxRate = 16;
        $item->discount = 10;

        $serviceResult = $this->calculator->calculateItem($item, 'servicio');
        $productResult = $this->calculator->calculateItem($item, 'producto');

        $this->assertEquals($serviceResult['total'], $productResult['total']);
        $this->assertNotEquals($serviceResult['taxAmount'], $productResult['taxAmount']);
        $this->assertNotEquals($serviceResult['discountAmount'], $productResult['discountAmount']);
    }
}