import {toFormData} from "axios";
import api from "@/utils/api.js";

export const productService = {
    list: (params) => api.get('/admin/products', {params}).then((res) => res.data),
    create: (payload) => api.post('/admin/products', toFormData(payload)).then((res) => res.data),
    update: (id, payload) => api.post(`/admin/products/${id}`, toFormData(payload, 'PUT')).then((res) => res.data),
    remove: (id) => api.delete(`/admin/products/${id}`),
};
