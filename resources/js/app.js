import './bootstrap';
import '../css/app.css';


import { createApp } from 'vue';
import RoleSelection from './components/RoleSelection.vue';

const app = createApp({});

app.component('role-selection', RoleSelection);

app.mount('#app');
