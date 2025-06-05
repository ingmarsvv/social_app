import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import PrimeVue from 'primevue/config'
import Aura from '@primeuix/themes/aura';
import ToastService from 'primevue/toastservice';

import App from './App.vue'
import router from './router'

import axios from 'axios'

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;
axios.defaults.baseURL = 'http://localhost'
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(PrimeVue, {
    theme: {
        preset: Aura
    }
})
app.use(ToastService);

// Wait for router to be ready before mounting the app, this fixes a nav bar flicker
router.isReady().then(() => {
    app.mount('#app')
  })
  