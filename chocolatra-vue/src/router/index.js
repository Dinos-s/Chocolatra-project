import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/LoginView.vue'
import DashboardHomeView from '../views/DasboardHomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
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
      path: '/dashboard',
      component: () => import('../views/DashboardView.vue'),
      meta: { requiresAuth: true },
      children: [
        {
          path: '',
          name: 'dashboard',
          component: DashboardHomeView,
        },
        {
          path: 'users/new',
          name: 'users',
          component: () => import('../views/User/NewUserView.vue'),
        },
      ],
    },
    // {
    //   path: '/users',
    //   name: 'users',
    //   component: () => import('../views/Users/UsersView.vue'),
    //   meta: { requiresAuth: true },
    // },
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
