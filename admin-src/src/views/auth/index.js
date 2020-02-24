import Forgot from './Forgot'
import ForgotCheck from './ForgotCheck'
import Login from './Login'
import Create from './Create'
import Logout from './Logout'
import Reset from './Reset'

export default [
  {
    path: '/login', component: Login, 
    meta: {
      layout: 'LoginLayout',
      public: true,  // Allow access to even if not logged in
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
