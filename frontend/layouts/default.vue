<template>
  <div class="min-h-screen bg-gray-50">
    <header class="bg-primary-700 text-white shadow-md">
      <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold">Generador de Facturas</h1>
        <nav aria-label="Navegacion principal" class="flex gap-4">
          <NuxtLink to="/" class="hover:text-primary-200 transition">Nueva Factura</NuxtLink>
          <NuxtLink to="/invoices" class="hover:text-primary-200 transition">Historial</NuxtLink>
        </nav>
      </div>
    </header>
    <main class="max-w-7xl mx-auto px-4 py-8">
      <slot />
    </main>
  </div>
</template>