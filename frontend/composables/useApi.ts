export const useApi = () => {
  const config = useRuntimeConfig()
  const baseURL = config.public.apiUrl

  const createInvoice = async (data: any) => {
    return await $fetch(`${baseURL}/api/invoices`, {
      method: 'POST',
      body: data,
    })
  }

  const getInvoices = async () => {
    return await $fetch(`${baseURL}/api/invoices`)
  }

  const calculateInvoice = async (data: any) => {
    return await $fetch(`${baseURL}/api/invoices/calculate`, {
      method: 'POST',
      body: data,
    })
  }

  const getPdfUrl = (id: number) => {
    return `${baseURL}/api/invoices/${id}/pdf`
  }

  return {
    createInvoice,
    getInvoices,
    calculateInvoice,
    getPdfUrl,
  }
}