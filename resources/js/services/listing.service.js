import api from "@/utils/api.js";
import {toFormData} from "@/utils/formData.js";

export const listingService = {
    list: (params) => api.get('/owner/listings', { params }).then((res) => res.data),
    add: (payload) => api.post('/owner/listings', toFormData(payload)).then((res) => res.data),
    update: (id, payload) => api.post(`/owner/listings/${id}`, toFormData(payload, 'PUT')).then((res) => res.data),
    remove: (id) => api.delete(`/owner/listings/${id}`),
};
