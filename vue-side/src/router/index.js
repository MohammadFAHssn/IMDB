import { createRouter, createWebHistory } from 'vue-router'
import Movie from '../components/Movie.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/movie',
      name: 'movie',
      component: Movie,
    },
  ],
})

export default router
