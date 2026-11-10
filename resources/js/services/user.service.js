import api from "@/utils/api.js";

export const userService = {
    list: (params) => api.get('/admin/users', {params}).then((res) => res.data),
    create: (payload) => api.post('/admin/users', payload).then((res) => res.data),
    update: (id, payload) => api.put(`/admin/users/${id}`, payload).then((res) => res.data),
    remove: (id) => api.delete(`/admin/users/${id}`),
};
