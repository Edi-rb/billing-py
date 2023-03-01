/**
 * @vuepress
 * ---
 * title: Router configuration
 * headline: Routes setup and settings
 * ---
 */

import Vue from 'vue'
import Router from 'vue-router'
import routes from './routes'

import store from '@/store/index'

Vue.use(Router)

const router = new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: routes
})

router.beforeEach((to, from, next) => {
  const authenticated = store.state.auth.authenticated

  if (to.name === 'Login') {
    if (!authenticated) {
      return next()
    } else {
      return next({ name: 'Inicio' })
    }
  } else if (to.name === 'Not found') {
    return next()
  } else {
    if (!authenticated) {
      next({ name: 'Login' })
    } else {
      return next()
    }
  }
})

export default router
