// Plugins
import pages from './pages/routes.js.js'
import CollectionsName from './collections/_name'
import media from './media/routes.js.js'
import roles from './roles/routes.js'

export default [
  {
    text: 'Themes',
    path: '/themes',
    component: CollectionsName,
    props: (route) => ({ name: 'themes' })
  },
  ...pages,
  ...media,
  ...roles
]
