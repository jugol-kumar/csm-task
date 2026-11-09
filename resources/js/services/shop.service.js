import api from "@/utils/api.js";

export const shopService = {
    list: (params) => api.get('/admin/stores', { params }).then((res) => res.data),
    create: (payload) => api.post('/admin/stores', payload).then((res) => res.data),
    update: (id, payload) => api.put(`/admin/stores/${id}`, payload).then((res) => res.data),
    remove: (id) => api.delete(`/admin/stores/${id}`),
};
