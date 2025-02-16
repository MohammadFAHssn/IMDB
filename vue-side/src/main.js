import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

import axios from 'axios'

import { AgGridVue } from 'ag-grid-vue3'
import { AllCommunityModule, ModuleRegistry } from 'ag-grid-community'

// Register all Community features
ModuleRegistry.registerModules([AllCommunityModule])

const app = createApp(App)

app.config.globalProperties.$axios = axios.create({
  baseURL: 'http://127.0.0.1:8000/api',
  headers: {
    'Content-Type': 'application/json',
  },
})

app.use(createPinia())
app.use(router)

app.component('AgGridVue', AgGridVue)

app.mount('#app')
