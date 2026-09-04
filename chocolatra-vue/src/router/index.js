import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/LoginView.vue'
import DashboardHomeView from '../views/DasboardHomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/adm',
      name: 'adm-login',
      component: LoginView,
    },
    // {
    //   path: '/new-user',
    //   name: 'new-user',
    //   component: () => import('../views/User/NewUserView.vue'),
    // },
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
      component: () => import('../views/AdmView.vue'),
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
          component: () => import('../views/User/NewUserView.vue'),
        },

        // Trufas
        {
          path: 'trufas/new',
          name: 'trufas',
          component: () => import('../views/Trufas/TrufasView.vue'),
        },
      ],
    },
  ],
})

router.beforeEach((to) => {
  const isAuthenticated = localStorage.getItem('token')

  if (to.meta.requiresAuth && !isAuthenticated) {
    return { name: 'home' }
  }

  if (to.nome === 'home' && isAuthenticated) {
    return { name: 'dashboard' }
  }
})

export default router
