import routes from './views/routes'
import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

export default new VueRouter({
  routes, // short for routes: routes
  linkActiveClass: 'active'
})
