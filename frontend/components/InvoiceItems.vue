<template>
  <div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg font-semibold text-gray-700">Items</h3>
      <button
        @click="addItem"
        aria-label="Agregar nuevo item a la factura"
        class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition text-sm"
      >
        + Agregar Item
      </button>
    </div>

    <div class="space-y-4">
      <div
        v-for="(item, index) in calculatedItems"
        :key="index"
        class="border border-gray-200 rounded-lg p-4"
      >
        <div class="flex justify-between items-center mb-3">
          <span class="text-sm font-medium text-gray-700">Item #{{ index + 1 }}</span>
          <button
            v-if="form.items.length > 1"
            @click="removeItem(index)"
            aria-label="Eliminar item"
            class="text-red-500 hover:text-red-700 text-sm"
          >
            Eliminar
          </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-6 gap-3">
          <div class="md:col-span-2">
            <label :for="'desc-' + index" class="block text-xs text-gray-700 mb-1">Descripcion</label>
            <input
              :id="'desc-' + index"
              v-model="form.items[index].description"
              type="text"
              placeholder="Descripcion del item"
              class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            />
          </div>
          <div>
            <label :for="'qty-' + index" class="block text-xs text-gray-700 mb-1">Cantidad</label>
            <input
              :id="'qty-' + index"
              v-model.number="form.items[index].quantity"
              type="number"
              min="1"
              class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            />
          </div>
          <div>
            <label :for="'price-' + index" class="block text-xs text-gray-700 mb-1">Precio</label>
            <input
              :id="'price-' + index"
              v-model.number="form.items[index].price"
              type="number"
              min="0"
              step="0.01"
              class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            />
          </div>
          <div>
            <label :for="'tax-' + index" class="block text-xs text-gray-700 mb-1">Impuesto %</label>
            <input
              :id="'tax-' + index"
              v-model.number="form.items[index].taxRate"
              type="number"
              min="0"
              max="100"
              class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            />
          </div>
          <div>
            <label :for="'disc-' + index" class="block text-xs text-gray-700 mb-1">Descuento %</label>
            <input
              :id="'disc-' + index"
              v-model.number="form.items[index].discount"
              type="number"
              min="0"
              max="100"
              class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            />
          </div>
        </div>

        <div class="mt-3 flex justify-end gap-4 text-sm text-gray-700">
          <span>Subtotal: <strong>{{ item.subtotal.toFixed(2) }}</strong></span>
          <span>Impuesto: <strong>{{ item.taxAmount.toFixed(2) }}</strong></span>
          <span>Descuento: <strong>{{ item.discountAmount.toFixed(2) }}</strong></span>
          <span class="text-primary-700">Total: <strong>{{ item.total.toFixed(2) }}</strong></span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const { form, addItem, removeItem, calculatedItems } = useInvoice()
</script>