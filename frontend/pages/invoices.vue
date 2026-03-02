<template>
  <div>
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Historial de Facturas</h2>

    <div v-if="loading" class="text-center py-8 text-gray-500">Cargando...</div>

    <div v-else-if="invoices.length === 0" class="text-center py-8 text-gray-500">
      No hay facturas registradas
    </div>

    <div v-else class="bg-white rounded-lg shadow overflow-hidden">
      <table class="w-full">
        <thead class="bg-primary-700 text-white">
          <tr>
            <th class="px-4 py-3 text-left text-sm">Numero</th>
            <th class="px-4 py-3 text-left text-sm">De</th>
            <th class="px-4 py-3 text-left text-sm">Para</th>
            <th class="px-4 py-3 text-left text-sm">Tipo</th>
            <th class="px-4 py-3 text-right text-sm">Total</th>
            <th class="px-4 py-3 text-left text-sm">Fecha</th>
            <th class="px-4 py-3 text-center text-sm">PDF</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="invoice in invoices" :key="invoice.id" class="border-b hover:bg-gray-50">
            <td class="px-4 py-3 text-sm font-medium">{{ invoice.invoiceNumber }}</td>
            <td class="px-4 py-3 text-sm">{{ invoice.fromName }}</td>
            <td class="px-4 py-3 text-sm">{{ invoice.toName }}</td>
            <td class="px-4 py-3 text-sm capitalize">{{ invoice.itemType }}</td>
            <td class="px-4 py-3 text-sm text-right font-medium">{{ invoice.currency }} {{ Number(invoice.grandTotal).toFixed(2) }}</td>
            <td class="px-4 py-3 text-sm">{{ invoice.issueDate }}</td>
            <td class="px-4 py-3 text-center"><a :href="getPdfUrl(invoice.id)" target="_blank" class="text-primary-600 hover:text-primary-800 text-sm underline">Ver PDF</a></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
const { getInvoices, getPdfUrl } = useApi()

const invoices = ref([])
const loading = ref(true)

onMounted(async () => {
  try {
    invoices.value = await getInvoices()
  } catch (error) {
    console.error('Error al cargar facturas:', error)
  } finally {
    loading.value = false
  }
})
</script>