<template>
  <div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-700 mb-4">Emisor y Cliente</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="space-y-3">
        <h4 class="font-medium text-primary-700">De (Emisor)</h4>
        <div>
          <label for="fromName" class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
          <input
            id="fromName"
            v-model="form.fromName"
            type="text"
            placeholder="Nombre o empresa"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
          />
        </div>
        <div>
          <label for="fromEmail" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input
            id="fromEmail"
            v-model="form.fromEmail"
            type="email"
            placeholder="correo@empresa.com"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
          />
        </div>
        <div>
          <label for="fromAddress" class="block text-sm font-medium text-gray-700 mb-1">Direccion</label>
          <textarea
            id="fromAddress"
            v-model="form.fromAddress"
            rows="2"
            placeholder="Direccion completa"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
          ></textarea>
        </div>
      </div>

      <div class="space-y-3">
        <h4 class="font-medium text-primary-700">Para (Cliente)</h4>
        <div>
          <label for="toName" class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
          <input
            id="toName"
            v-model="form.toName"
            type="text"
            placeholder="Nombre o empresa"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
          />
        </div>
        <div>
          <label for="toEmail" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input
            id="toEmail"
            v-model="form.toEmail"
            type="email"
            placeholder="correo@cliente.com"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
          />
        </div>
        <div>
          <label for="toAddress" class="block text-sm font-medium text-gray-700 mb-1">Direccion</label>
          <textarea
            id="toAddress"
            v-model="form.toAddress"
            rows="2"
            placeholder="Direccion completa"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
          ></textarea>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const { form } = useInvoice()
</script>