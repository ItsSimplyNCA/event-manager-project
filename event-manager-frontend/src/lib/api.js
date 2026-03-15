import axios from 'axios'

const api = axios.create({
    baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000',
    withCredentials: true,
    withXSRFToken: true,
    headers: {
        Accept: 'application/json',
    },
})

api.interceptors.response.use(
    (response) => response,
    (error) => {
        const status = error?.response?.status
        const path = window.location.pathname

        const isGuestPage =
            path === '/login' ||
            path === '/forgot-password' ||
            path === '/reset-password'

        if (status === 401 && !isGuestPage) {
            window.location.href = '/login'
        }

        return Promise.reject(error)
    }
)

export default api