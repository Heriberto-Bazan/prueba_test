export interface InvoiceItem {
  description: string
  quantity: number
  price: number
  taxRate: number
  discount: number
}

export interface InvoiceForm {
  invoiceNumber: string
  issueDate: string
  dueDate: string
  fromName: string
  fromEmail: string
  fromAddress: string
  toName: string
  toEmail: string
  toAddress: string
  itemType: string
  currency: string
  notes: string
  items: InvoiceItem[]
}

export interface CalculatedItem extends InvoiceItem {
  subtotal: number
  taxAmount: number
  discountAmount: number
  total: number
}

export interface InvoiceTotals {
  subtotal: number
  totalTax: number
  totalDiscount: number
  grandTotal: number
}