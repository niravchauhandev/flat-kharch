import axios from 'axios';

const apiClient = axios.create({
    baseURL: '/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
});

// Request interceptor to attach Bearer token
apiClient.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('flat_kharch_token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => Promise.reject(error)
);

// Response interceptor to handle unauthenticated 401
apiClient.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response && error.response.status === 401) {
            localStorage.removeItem('flat_kharch_token');
            localStorage.removeItem('flat_kharch_user');
            if (window.location.pathname !== '/spa/login') {
                window.location.href = '/spa/login';
            }
        }
        return Promise.reject(error);
    }
);

export default apiClient;
