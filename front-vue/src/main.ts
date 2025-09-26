import { createApp } from 'vue'
import { createPinia } from 'pinia'
import PrimeVue from 'primevue/config';
import App from './App.vue'
import router from './router'
import './style.css'
import { ConfirmationService, ConfirmDialog } from 'primevue';
import Aura from '@primeuix/themes/aura';

const app = createApp(App)

app.use(PrimeVue,{
    theme: {
        preset: Aura
    }
});
app.use(ConfirmationService) 
app.component('ConfirmDialog', ConfirmDialog)

app.use(createPinia())
app.use(router)

app.mount('#app')
