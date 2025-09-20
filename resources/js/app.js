import './bootstrap';
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import '../css/app.css'
import VueGoodTablePlugin from 'vue-good-table-next';
import 'vue-good-table-next/dist/vue-good-table-next.css'
import VueSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'
import {createBootstrap} from 'bootstrap-vue-next'

// Add the necessary CSS
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue-next/dist/bootstrap-vue-next.css'

const app = createApp(App);
app.use(router);
app.use(VueGoodTablePlugin);
app.use(createBootstrap());
app.component('v-select', VueSelect);

app.mount('#app');