<?php

namespace App\Service;

use App\Dto\InvoiceItemDto;
use App\Dto\InvoiceRequestDto;

class InvoiceCalculator
{
   
    public function calculateItem(InvoiceItemDto $item, string $itemType): array
    {
        $base = $item->price * $item->quantity;

        if ($itemType === 'servicio') {
            
            $discountAmount = $base * ($item->discount / 100);
            $afterDiscount = $base - $discountAmount;
            $taxAmount = $afterDiscount * ($item->taxRate / 100);
            $total = $afterDiscount + $taxAmount;
        } else {
           
            $taxAmount = $base * ($item->taxRate / 100);
            $withTax = $base + $taxAmount;
            $discountAmount = $withTax * ($item->discount / 100);
            $total = $withTax - $discountAmount;
        }

        return [
            'subtotal' => round($base, 2),
            'taxAmount' => round($taxAmount, 2),
            'discountAmount' => round($discountAmount, 2),
            'total' => round($total, 2),
        ];
    }

    public function calculateInvoice(InvoiceRequestDto $dto): array
    {
        $subtotal = 0;
        $totalTax = 0;
        $totalDiscount = 0;
        $grandTotal = 0;
        $calculatedItems = [];

        foreach ($dto->items as $item) {
            $result = $this->calculateItem($item, $dto->itemType);

            $subtotal += $result['subtotal'];
            $totalTax += $result['taxAmount'];
            $totalDiscount += $result['discountAmount'];
            $grandTotal += $result['total'];

            $calculatedItems[] = [
                'description' => $item->description,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'taxRate' => $item->taxRate,
                'discount' => $item->discount,
                'subtotal' => $result['subtotal'],
                'taxAmount' => $result['taxAmount'],
                'discountAmount' => $result['discountAmount'],
                'total' => $result['total'],
            ];
        }

        return [
            'subtotal' => round($subtotal, 2),
            'totalTax' => round($totalTax, 2),
            'totalDiscount' => round($totalDiscount, 2),
            'grandTotal' => round($grandTotal, 2),
            'items' => $calculatedItems,
        ];
    }
}
