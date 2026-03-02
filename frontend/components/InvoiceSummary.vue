<template>
  <div class="bg-white rounded-lg shadow p-6 sticky top-8">
    <h3 class="text-lg font-semibold text-gray-700 mb-4">Resumen</h3>

    <div class="space-y-3">
      <div class="flex justify-between text-sm">
        <span class="text-gray-600">Subtotal:</span>
        <span class="font-medium">{{ form.currency }} {{ totals.subtotal.toFixed(2) }}</span>
      </div>
      <div class="flex justify-between text-sm">
        <span class="text-gray-600">Impuestos:</span>
        <span class="font-medium">{{ form.currency }} {{ totals.totalTax.toFixed(2) }}</span>
      </div>
      <div class="flex justify-between text-sm">
        <span class="text-gray-600">Descuentos:</span>
        <span class="font-medium text-red-500">-{{ form.currency }} {{ totals.totalDiscount.toFixed(2) }}</span>
      </div>
      <div class="border-t pt-3 flex justify-between">
        <span class="text-lg font-bold text-gray-800">Total:</span>
        <span class="text-lg font-bold text-primary-700">{{ form.currency }} {{ totals.grandTotal.toFixed(2) }}</span>
      </div>
    </div>

    <div class="mt-6">
      <label class="block text-sm font-medium text-gray-600 mb-1">Notas</label>
      <textarea
        v-model="form.notes"
        rows="3"
        placeholder="Notas adicionales..."
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent"
      ></textarea>
    </div>

    <div class="mt-4 p-3 bg-gray-50 rounded-lg text-xs text-gray-500">
      <p><strong>Tipo:</strong> {{ form.itemType === 'servicio' ? 'Servicio' : 'Producto' }}</p>
      <p class="mt-1">
        {{ form.itemType === 'servicio'
          ? 'Descuento aplicado antes del impuesto'
          : 'Descuento aplicado despues del impuesto'
        }}
      </p>
    </div>
  </div>
</template>

<script setup>
const { form, totals } = useInvoice()
</script>