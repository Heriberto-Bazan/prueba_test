export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },

  modules: [
    '@nuxtjs/tailwindcss',
  ],

  css: ['~/assets/css/main.css'],

  runtimeConfig: {
    public: {
      apiUrl: process.env.NUXT_PUBLIC_API_URL || 'http://localhost:8000'
    }
  },

  ssr: true,

  experimental: {
    payloadExtraction: true,
  },

  app: {
    head: {
      htmlAttrs: {
        lang: 'es',
      },
      title: 'Generador de Facturas',
      meta: [
        { charset: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        { name: 'description', content: 'Sistema de generacion de facturas' },
        { name: 'theme-color', content: '#1B4F72' },
      ]
    }
  }
})