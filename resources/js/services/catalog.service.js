import api from "@/utils/api.js";
import {toFormData} from "@/utils/formData.js";

export const catalogService = {
    list: (params) => api.get('/owner/catalog', { params }).then((res) => res.data),
    createProduct: (payload) => api.post('/owner/catalog', toFormData(payload)).then((res) => res.data),
    updateProduct: (id, payload) => api.post(`/owner/catalog/${id}`, toFormData(payload, 'PUT')).then((res) => res.data),
    removeProduct: (id) => api.delete(`/owner/catalog/${id}`),
};
