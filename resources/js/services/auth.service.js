import api from "@/utils/api.js";

export const authService = {
    login: (payload) => api.post('/login', payload).then((res) => res.data),
    adminLogin: (payload) => api.post('/admin/login', payload).then((res) => res.data),
    register: (payload) => api.post('/register', payload).then((res) => res.data),
    me: () => api.get('/me').then((res) => res.data),
    logout: () => api.post('/logout'),
};
