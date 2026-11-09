import '../css/app.css';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import { useAuthStore } from '@/stores/auth';
import App from "@/App.vue";
import router from "@/routes/router.js";
import {app_slug} from "@/utils/config.js";

const app = createApp(App);
const pinia = createPinia();

app.use(pinia);
app.use(router);

const auth = useAuthStore(pinia);

window.addEventListener(`${app_slug}:unauthorized`, () => {
    const isAdmin = auth.isAdmin;
    auth.clear();
    router.push(isAdmin ? '/admin/login' : '/login');
});

app.mount('#app');

console.log('yes call')
