export const app_slug = (import.meta.env.VITE_APP_NAME || '')
    .trim()
    .toLowerCase()
    .replace(/\s+/g, '-');

export const app_name = (import.meta.env.VITE_APP_NAME || '')
