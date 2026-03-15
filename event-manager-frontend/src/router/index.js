import { createRouter, createWebHistory } from 'vue-router'
import { pinia } from '../stores'
import { useAuthStore } from '../stores/auth'

import LoginView from '../views/LoginView.vue'
import ForgotPasswordView from '../views/ForgotPasswordView.vue'
import ResetPasswordView from '../views/ResetPasswordView.vue'
import EventsView from '../views/EventsView.vue'
import SupportView from '../views/SupportView.vue'
import AgentChatsView from '../views/AgentChatsView.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      redirect: '/login',
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/LoginView.vue'),
      meta: { guestOnly: true },
    },
    {
      path: '/forgot-password',
      name: 'forgot-password',
      component: () => import('../views/ForgotPasswordView.vue'),
      meta: { guestOnly: true },
    },
    {
      path: '/reset-password',
      name: 'reset-password',
      component: () => import('../views/ResetPasswordView.vue'),
      meta: { guestOnly: true },
    },
    {
      path: '/events',
      name: 'events',
      component: () => import('../views/EventsView.vue'),
      meta: { requiresAuth: true },
    },
    {
      path: '/support',
      name: 'support',
      component: () => import('../views/SupportView.vue'),
      meta: { requiresAuth: true },
    },
    {
      path: '/agent/chats',
      name: 'agent-chats',
      component: () => import('../views/AgentChatsView.vue'),
      meta: { requiresAuth: true, requiresAgent: true },
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'not-found',
      component: () => import('../views/NotFoundView.vue'),
    },
  ],
})

router.beforeEach(async (to) => {
  const authStore = useAuthStore(pinia)
  await authStore.initialize()

  if (to.meta.guestOnly && authStore.isAuthenticated) {
    return authStore.isAgent ? '/agent/chats' : '/events'
  }

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    return '/login'
  }

  if (to.meta.requiresAgent && !authStore.isAgent) {
    return '/events'
  }
})

export default router
