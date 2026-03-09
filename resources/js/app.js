import './bootstrap';
import { createApp } from 'vue';

import LoginForm from './components/Login/LoginForm.vue';

const app = createApp();

app.component('login-form', LoginForm); 

app.mount('#app');