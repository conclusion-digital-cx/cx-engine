import List from './components/List.js'
import Dashboard from './Dashboard.js'

// Plugins
import pages from './plugins/pages/routes.js'
import Collections_name from './plugins/collections/_name.js'
import collections from './plugins/collections/routes.js'
import sitemap from './plugins/sitemap/routes.js'
import media from './plugins/media/routes.js'
import settings from './plugins/settings/routes.js'

export default [
    { path: '/', component: Dashboard, props: true },
    // Default CMS collections
    { text: 'Themes', path: '/themes', component: Collections_name, props: (route) => ({ name: "themes" }) },
    ...pages,
    ...collections,
    ...media,
    ...settings,

    { path: '/users', component: Collections_name, props: (route) => ({ name: "users" }) },

    {
        path: '/blocks', component: List, props: (route) => ({
            title: "Blocks",
            name: "blocks",
        })
    },

    // Work in progress
    ...sitemap
]
