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
      path: '/catalogo',
      name: 'catalogo',
      component: () => import('../views/site/Trufas/CatalogoView.vue'),
    },
    {
      path: '/cart',
      name: 'carrinho',
      component: () => import('../views/site/CartView.vue'),
      meta: { requiresAClientAuth: true },
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/site/AboutView.vue'),
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/site/LoginView.vue'),
    },
    {
      path: '/registro',
      name: 'registro',
      component: () => import('../views/site/RegistroView.vue'),
    },
    {
      path: '/meus-pedidos',
      name: 'meus-pedidos',
      component: () => import('../views/site/MeusPedidosView.vue'),
      meta: { requiresClienteLogin: true },
    },

    // Admin
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
        {
          path: 'sabores/new',
          name: 'sabores',
          component: () => import('../views/admin/Trufas/SaboresView.vue'),
        }
      ],
    },
  ],
})

router.beforeEach((to) => {
  const isAdminAuth = localStorage.getItem('admin_token')
  const isClientAuth = localStorage.getItem('token')

  if (to.meta.requiresAuth && !isAdminAuth) {
    return { name: 'adm-login' }
  }

  if (to.meta.requiresAClientAuth && !isClientAuth) {
    return { 
      name: 'login', 
      query: { redirect: to.fullPath } 
    }
  }

  if (to.nome === 'home' && isAdminAuth) {
    return { name: 'dashboard' }
  }
})

export default router
