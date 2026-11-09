import axios from 'axios';
import {app_slug} from "@/utils/config.js";

export const TOKEN_KEY = 'haat_token';

const api = axios.create({
    baseURL: '/api',
    headers: { Accept: 'application/json' },
});

api.interceptors.request.use((config) => {
    const token = localStorage.getItem(TOKEN_KEY);

    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }

    return config;
});

api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401 && localStorage.getItem(TOKEN_KEY)) {
            window.dispatchEvent(new Event(`${app_slug}:unauthorized`));
        }

        return Promise.reject(error);
    }
);

export default api;
