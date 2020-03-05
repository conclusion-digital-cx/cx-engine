import Forgot from './Forgot.vue'
import ForgotCheck from './ForgotCheck.vue'
import Login from './Login.vue'
import Create from './Create.vue'
import Logout from './Logout.vue'
import Reset from './Reset.vue'

export default [
  {
    path: '/login',
    component: Login,
    meta: {
      layout: 'LoginLayout',
      public: true, // Allow access to even if not logged in
      onlyWhenLoggedOut: true
    },
    props: {
      redirectTo: '/selectcompany'
    }
  },
  { path: '/create', component: Create, meta: { layout: 'LoginLayout' } },
  { path: '/logout', component: Logout, meta: { layout: 'LoginLayout' } },
  { path: '/forgot', component: Forgot, meta: { layout: 'LoginLayout' } },
  { path: '/forgot/check', component: ForgotCheck, meta: { layout: 'LoginLayout' } },
  { path: '/reset/:secret', component: Reset, meta: { layout: 'LoginLayout' } }
]
