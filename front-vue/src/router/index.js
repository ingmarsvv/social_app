import { createRouter, createWebHistory } from 'vue-router'
import RegisterView from '@/views/RegisterView.vue'
import PostsView from '@/views/MainView.vue'
import ProfileView from '@/views/ProfileView.vue'
import PostShowView from '@/views/PostShowView.vue'
import { useAuth } from '@/composables/useAuth.js'


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/LoginView.vue'),
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterView,
    },
    {
      path: '/main',
      name: 'main',
      component: PostsView,
      meta: { requiresAuth: true },
    },
    {
      path: '/profile',
      name: 'profile',
      component: ProfileView,
      meta: { requiresAuth: true },
    },
    {
      path: '/post/:id',
      name: 'post.show',
      component: PostShowView,
      meta: { requiresAuth: true },
    },
    {
      path: '/:pathMatch(.*)*',
      redirect: '/login' 
    }
  ],
})

router.beforeEach(async (to, from, next) => {
  const requiresAuth = to.meta.requiresAuth
  const { fetchUser, isAuthenticated } = useAuth()
  
  await fetchUser()
  
  const authenticated = isAuthenticated()

  if (requiresAuth && !authenticated) { //if requires auth and not authenticated redirect to login
    return next({ name: 'login' })
  }
  if ((to.name === 'login' || to.name === 'register') && authenticated) { //if user authenticated prevent login and register
    return next({ name: 'main' })
  }
  next() // normal execution
})

export default router
