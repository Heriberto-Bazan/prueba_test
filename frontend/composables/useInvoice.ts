import { ref, computed } from 'vue'
import type { InvoiceItem, InvoiceForm, InvoiceTotals } from '~/types/invoice'

function createEmptyItem(): InvoiceItem {
  return {
    description: '',
    quantity: 1,
    price: 0,
    taxRate: 16,
    discount: 0,
  }
}

const form = ref<InvoiceForm>({
  invoiceNumber: '',
  issueDate: new Date().toISOString().split('T')[0],
  dueDate: '',
  fromName: '',
  fromEmail: '',
  fromAddress: '',
  toName: '',
  toEmail: '',
  toAddress: '',
  itemType: 'servicio',
  currency: 'MXN',
  notes: '',
  items: [createEmptyItem()],
})

export const useInvoice = () => {
  function addItem() {
    form.value.items.push(createEmptyItem())
  }

  function removeItem(index: number) {
    if (form.value.items.length > 1) {
      form.value.items.splice(index, 1)
    }
  }

  function calculateItem(item: InvoiceItem) {
    const base = item.price * item.quantity

    if (form.value.itemType === 'servicio') {
      const discountAmount = base * (item.discount / 100)
      const afterDiscount = base - discountAmount
      const taxAmount = afterDiscount * (item.taxRate / 100)
      const total = afterDiscount + taxAmount

      return {
        subtotal: Math.round(base * 100) / 100,
        taxAmount: Math.round(taxAmount * 100) / 100,
        discountAmount: Math.round(discountAmount * 100) / 100,
        total: Math.round(total * 100) / 100,
      }
    } else {
      const taxAmount = base * (item.taxRate / 100)
      const withTax = base + taxAmount
      const discountAmount = withTax * (item.discount / 100)
      const total = withTax - discountAmount

      return {
        subtotal: Math.round(base * 100) / 100,
        taxAmount: Math.round(taxAmount * 100) / 100,
        discountAmount: Math.round(discountAmount * 100) / 100,
        total: Math.round(total * 100) / 100,
      }
    }
  }

  const calculatedItems = computed(() => {
    return form.value.items.map(item => ({
      ...item,
      ...calculateItem(item),
    }))
  })

  const totals = computed<InvoiceTotals>(() => {
    let subtotal = 0
    let totalTax = 0
    let totalDiscount = 0
    let grandTotal = 0

    calculatedItems.value.forEach(item => {
      subtotal += item.subtotal
      totalTax += item.taxAmount
      totalDiscount += item.discountAmount
      grandTotal += item.total
    })

    return {
      subtotal: Math.round(subtotal * 100) / 100,
      totalTax: Math.round(totalTax * 100) / 100,
      totalDiscount: Math.round(totalDiscount * 100) / 100,
      grandTotal: Math.round(grandTotal * 100) / 100,
    }
  })

  function resetForm() {
    form.value = {
      invoiceNumber: '',
      issueDate: new Date().toISOString().split('T')[0],
      dueDate: '',
      fromName: '',
      fromEmail: '',
      fromAddress: '',
      toName: '',
      toEmail: '',
      toAddress: '',
      itemType: 'servicio',
      currency: 'MXN',
      notes: '',
      items: [createEmptyItem()],
    }
  }

  return {
    form,
    addItem,
    removeItem,
    calculatedItems,
    totals,
    resetForm,
    createEmptyItem,
  }
}