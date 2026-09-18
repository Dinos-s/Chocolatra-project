import axios from "axios"

// define a rota principal
const api = axios.create({
    baseURL: 'http://localhost:8000/api'
})

api.interceptors.request.use(config => {
    const isAreaAdmin = window.location.pathname.includes('/adm')
    const token = isAreaAdmin ? localStorage.getItem('admin_token') : localStorage.getItem('token')
    
    if (token) {
        config.headers.Authorization = `Bearer ${token}`
    }
    return config
})

export default api