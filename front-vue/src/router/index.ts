import { createRouter, createWebHistory, type RouteRecord, type RouteRecordRaw } from 'vue-router'

import Register from '@/pages/Register.vue'
import Login from '@/pages/Login.vue'
import Welcome from '@/pages/Welcome.vue'
import HomeGames from '@/pages/HomeGames.vue'
import HomeGolpeado from '@/pages/HomeGolpeado.vue'

const routes:RouteRecordRaw[] = [
  {
    path: '/',
    name: 'Welcome',
    component: Welcome,
    meta: {
      title: 'Bienvenido - Royal Casino',
      requiresGuest: true
    }
  },
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
  },
  {
    path: '/home-games',
    name: 'HomeGames', 
    component: HomeGames,
    meta: { 
      title: 'Admin de Juego Totales',
      requiresGuest: true 
    }
  },
  {
    path: '/home-golpeado',
    name: 'HomeGolpeado', 
    component: HomeGolpeado,
    meta: { 
      title: 'Admin de Juego Golpeado',
      requiresGuest: true 
    }
  }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: routes,
})

export default router
