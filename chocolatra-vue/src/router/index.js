import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/site/HomeView.vue'
import LoginView from '../views/admin/LoginView.vue'
import DashboardHomeView from '../views/admin/DasboardHomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/adm',
      name: 'adm-login',
      component: LoginView,
    },
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue'),
    },
    {
      path: '/adm',
      name: 'adm',
      component: () => import('../views/admin/AdmView.vue'),
      meta: { requiresAuth: true },
      children: [
        {
          path: 'dashboard',
          name: 'dashboard',
          component: DashboardHomeView,
        },

        // Usuarios
        {
          path: 'users/new',
          name: 'users',
          component: () => import('../views/admin/User/NewUserView.vue'),
        },

        // Trufas
        {
          path: 'trufas/new',
          name: 'trufas',
          component: () => import('../views/admin/Trufas/TrufasView.vue'),
        },
      ],
    },
  ],
})

router.beforeEach((to) => {
  const isAuthenticated = localStorage.getItem('token')

  if (to.meta.requiresAuth && !isAuthenticated) {
    return { name: 'adm-login' }
  }

  if (to.nome === 'home' && isAuthenticated) {
    return { name: 'dashboard' }
  }
})

export default router
