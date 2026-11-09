import api from "@/utils/api.js";

export const storefrontService = {
    shops: (params) => api.get('/storefront/shops', { params }).then((res) => res.data),
    shop: (slug) => api.get(`/storefront/shops/${slug}`).then((res) => res.data),
    products: (slug, params) => api.get(`/storefront/shops/${slug}/products`, { params }).then((res) => res.data),
    latest: (params) => api.get('/storefront/products', { params }).then((res) => res.data),
    product: (slug, id) => api.get(`/storefront/shops/${slug}/products/${id}`).then((res) => res.data),
};
