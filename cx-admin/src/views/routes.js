import NotFound from './NotFound.vue'
import CollectionsName from './collections/_name.vue'
import collections from './collections/routes.js'
// import media from '../addons/media/routes.js'
import auth from './auth/index'
// import roles from './roles'
// import Home from './Home'
import Settings from './Settings/index.vue'
import types from './Types/index.js'
import content from './content/index.js'
// import List from './content/List'
import roles from './roles/routes.js'

// Plugins
import pages from './pages/routes.js'
import media from './media/routes.js'

export default [
  ...auth,
  // { path: '/roles', component: roles },
  { path: '/', redirect: '/settings' },
  ...content,

  // TODO make pluginable
  {
    text: 'Themes',
    path: '/themes',
    component: CollectionsName,
    props: (route) => ({ name: 'themes' })
  },
  ...media,
  // {
  //   text: 'Media',
  //   path: '/media',
  //   component: CollectionsName,
  //   props: (route) => ({ name: 'media' })
  // },
  {
    text: 'blocks',
    path: '/blocks',
    component: CollectionsName,
    props: (route) => ({ name: 'blocks' })
  },
  ...pages,

  ...collections,
  // ...media,
  ...types,
  ...roles,
  // { path: '/users', component: Collections_name, props: (route) => ({ name: "users" }) },
  // { path: '/:typeSlug', component: List, props: true },
  { path: '/settings', component: Settings, props: true },
  { path: '*', component: NotFound, props: true }
]
