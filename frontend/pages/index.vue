<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold text-gray-800">Nueva Factura</h2>
      <button
        @click="handleSubmit"
        :disabled="loading"
        aria-label="Crear factura"
        class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition disabled:opacity-50"
      >
        {{ loading ? 'Guardando...' : 'Crear Factura' }}
      </button>
    </div>

    <div v-if="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
      {{ successMessage }}
      <a v-if="pdfUrl" :href="pdfUrl" target="_blank" class="underline ml-2">Descargar PDF</a>
    </div>

    <div v-if="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 whitespace-pre-line">
      {{ errorMessage }}
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2 space-y-6">
        <InvoiceHeader />
        <InvoiceParties />
        <InvoiceItems />
      </div>
      <div>
        <InvoiceSummary />
      </div>
    </div>
  </div>
</template>

<script setup>
const { form, totals, resetForm } = useInvoice()
const { createInvoice, getPdfUrl } = useApi()

const loading = ref(false)
const successMessage = ref('')
const errorMessage = ref('')
const pdfUrl = ref('')

async function handleSubmit() {
  loading.value = true
  successMessage.value = ''
  errorMessage.value = ''
  pdfUrl.value = ''

  try {
    const response = await createInvoice(form.value)
    successMessage.value = 'Factura creada exitosamente'
    pdfUrl.value = getPdfUrl(response.invoice.id)
    resetForm()
  } catch (error) {
    const errorData = error?.data || {}
    if (errorData.details && errorData.details.length > 0) {
      const msgs = errorData.details.map(d => d.message)
      errorMessage.value = msgs.join('\n')
    } else {
      errorMessage.value = errorData.error || 'Error al crear la factura'
    }
  } finally {
    loading.value = false
  }
}
</script>