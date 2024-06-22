import axios from 'axios';

const api = axios.create({
    baseURL: '/api',
});

api.interceptors.request.use(config => {
    const token = window.localStorage.getItem('token')
        || window.sessionStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
}, error => {
    return Promise.reject(error);
});

export default api;
