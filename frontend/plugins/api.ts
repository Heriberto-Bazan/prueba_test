export default defineNuxtPlugin(() => {
  const config = useRuntimeConfig()

  const api = $fetch.create({
    baseURL: config.public.apiUrl,

    onRequest({ options }) {
      options.headers = {
        ...options.headers,
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      }
    },

    onResponseError({ response }) {
      const status = response.status
      const data = response._data

      if (status === 422) {
        console.warn('Errores de validacion:', data?.details)
      }

      if (status === 500) {
        console.error('Error interno del servidor')
      }
    },
  })

  return {
    provide: {
      api,
    },
  }
})