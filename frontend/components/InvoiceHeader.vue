<template>
  <div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-700 mb-4">Datos de la Factura</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label for="invoiceNumber" class="block text-sm font-medium text-gray-700 mb-1">Numero de Factura *</label>
        <input
          id="invoiceNumber"
          v-model="form.invoiceNumber"
          type="text"
          placeholder="F-001"
          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
        />
      </div>
      <div>
        <label for="itemType" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Factura *</label>
        <select
          id="itemType"
          v-model="form.itemType"
          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
        >
          <option value="servicio">Servicio</option>
          <option value="producto">Producto</option>
        </select>
        <p class="mt-2 text-xs text-amber-600 bg-amber-50 border border-amber-200 rounded px-3 py-2">
          No se permite mezclar productos y servicios en la misma factura. Todos los items seran de tipo {{ form.itemType === 'servicio' ? 'Servicio' : 'Producto' }}.
        </p>
      </div>
      <div>
        <label for="issueDate" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Emision *</label>
        <input
          id="issueDate"
          v-model="form.issueDate"
          type="date"
          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
        />
      </div>
      <div>
        <label for="dueDate" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Vencimiento</label>
        <input
          id="dueDate"
          v-model="form.dueDate"
          type="date"
          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
const { form } = useInvoice()
</script>