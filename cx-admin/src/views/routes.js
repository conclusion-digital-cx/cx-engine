import NotFound from './NotFound'

// Plugins
import pages from '../addons/pages/routes.js'
import CollectionsName from './collections/_name'
import collections from './collections/routes.js'
import media from '../addons/media/routes.js'
import auth from './auth'
// import roles from './roles'
// import Home from './Home'
import Settings from './Settings'
import types from './Types/index.js'
import content from './content/index.js'
// import List from './content/List'
import roles from './roles/routes.js'

export default [
  ...auth,
  // { path: '/roles', component: roles },
  { path: '/', redirect: '/settings' },
  ...content,
  { text: 'Themes', path: '/themes', component: CollectionsName, props: (route) => ({ name: 'themes' }) },
  ...pages,
  ...collections,
  ...media,
  ...types,
  ...roles,
  // { path: '/users', component: Collections_name, props: (route) => ({ name: "users" }) },
  // { path: '/:typeSlug', component: List, props: true },
  { path: '/settings', component: Settings, props: true },
  { path: '*', component: NotFound, props: true }
]
