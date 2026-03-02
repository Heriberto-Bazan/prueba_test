<?php
namespace App\Constants;

class InvoiceMessages
{
    public const DESCRIPTION_REQUIRED = 'La descripción es obligatoria';
    public const QUANTITY_POSITIVE = 'La cantidad debe ser mayor a 0';
    public const PRICE_POSITIVE = 'El precio no puede ser negativo';
    public const TAX_RATE_RANGE = 'El impuesto debe estar entre 0 y 100';
    public const DISCOUNT_RANGE = 'El descuento debe estar entre 0 y 100';

    public const INVOICE_NUMBER_REQUIRED = 'El número de factura es obligatorio';
    public const ISSUE_DATE_REQUIRED = 'La fecha de emisión es obligatoria';
    public const FROM_NAME_REQUIRED = 'El nombre del emisor es obligatorio';
    public const TO_NAME_REQUIRED = 'El nombre del cliente es obligatorio';
    public const ITEM_TYPE_INVALID = 'El tipo debe ser "producto" o "servicio"';
    public const ITEMS_REQUIRED = 'Debe agregar al menos un ítem';

    public const INVALID_ITEM_TYPE = 'El tipo de ítem debe ser "producto" o "servicio"';
    public const DUE_DATE_BEFORE_ISSUE = 'La fecha de vencimiento no puede ser anterior a la fecha de emisión';
    public const EMPTY_ITEMS = 'La factura debe tener al menos un ítem';
    public const INVALID_ITEM_AMOUNT = 'El ítem #%d tiene un monto inválido';
}