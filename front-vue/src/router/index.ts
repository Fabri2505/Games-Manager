import { createRouter, createWebHistory, type RouteRecord, type RouteRecordRaw } from 'vue-router'

import Register from '@/pages/Register.vue';
import Login from '@/pages/Login.vue'

const routes:RouteRecordRaw[] = [
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: { 
      title: 'Iniciar Sesión - Royal Casino',
      requiresGuest: true 
    }
  },
  {
    path: '/register',
    name: 'Register', 
    component: Register,
    meta: { 
      title: 'Crear Cuenta - Royal Casino',
      requiresGuest: true 
    }
  }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: routes,
})

export default router
