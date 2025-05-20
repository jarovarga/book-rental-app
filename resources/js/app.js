import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import { createRouter, createWebHistory } from 'vue-router';
import App from './App.vue';
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'

// Import components for routing
import BookIndex from './components/books/Index.vue';
import BookCreate from './components/books/Create.vue';
import BookEdit from './components/books/Edit.vue';
import BookShow from './components/books/Show.vue';
import AuthorIndex from './components/authors/Index.vue';
import AuthorCreate from './components/authors/Create.vue';
import AuthorEdit from './components/authors/Edit.vue';
import AuthorShow from './components/authors/Show.vue';
import Home from './components/Home.vue';

// Create router
const routes = [
    { path: '/', component: Home, name: 'home' },
    { path: '/books', component: BookIndex, name: 'books.index' },
    { path: '/books/create', component: BookCreate, name: 'books.create' },
    { path: '/books/:id', component: BookShow, name: 'books.show' },
    { path: '/books/:id/edit', component: BookEdit, name: 'books.edit' },
    { path: '/authors', component: AuthorIndex, name: 'authors.index' },
    { path: '/authors/create', component: AuthorCreate, name: 'authors.create' },
    { path: '/authors/:id', component: AuthorShow, name: 'authors.show' },
    { path: '/authors/:id/edit', component: AuthorEdit, name: 'authors.edit' },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Create Pinia store
const pinia = createPinia();

// Create and mount the Vue application
const app = createApp(App);
app.use(router);
app.use(pinia);
app.mount('#app');
