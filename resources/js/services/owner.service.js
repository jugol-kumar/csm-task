import api from "@/utils/api.js";

export const ownerService = {
    list: (params) => api.get('/admin/owners', {params}).then((res) => res.data),
    create: (payload) => api.post('/admin/owners', payload).then((res) => res.data),
    update: (id, payload) => api.put(`/admin/owners/${id}`, payload).then((res) => res.data),
    remove: (id) => api.delete(`/admin/owners/${id}`),
};
