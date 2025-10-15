import { createApp } from 'vue'
import axios from 'axios'

// Configure axios
window.axios = axios
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

// Get CSRF token
const token = document.head.querySelector('meta[name="csrf-token"]')
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token')
}

// Initialize Vue app if there are components to mount
const appElement = document.getElementById('app')
if (appElement && appElement.hasAttribute('data-vue')) {
    // Only initialize Vue if explicitly requested
    const app = createApp({})
    app.mount('#app')
} else {
    // For non-Vue pages, just make sure axios is available globally
    console.log('Vue not initialized - using vanilla JavaScript mode')
}

// Global utility functions for Laravel Blade templates
window.Laravel = {
    csrfToken: token ? token.content : null,
    baseUrl: window.location.origin
}