import api from "@/utils/api.js";

export const dashboardService = {
    admin: () => api.get('/admin/dashboard').then((res) => res.data),
    owner: () => api.get('/owner/dashboard').then((res) => res.data),
};
