import List from './components/List.js'
import PagesList from './pages/index.js'
import Collections from './collections/index.js'
import Dashboard from './Dashboard.js'
import sitemap from './sitemap/index.js'
import Collections_name from './collections/_name.js'
import NavigationDrawer from './components/NavigationDrawer.js'

const routes = [
    { path: '/', component: Dashboard, props: true },
    {
        path: '/collections/:name', component: Collections_name,
        props: (route) => ({
            name: route.params.name,
            id: route.query.id
        })
    },

    { path: '/sitemap', component: sitemap, props: (route) => ({ name: "pages" }) },

    // Default CMS collections
    { path: '/pages', component: PagesList, props: (route) => ({ name: "pages" }) },
    {
        path: '/blocks', component: List, props: (route) => ({
            title: "Blocks",
            name: "blocks",
        })
    },
    {
        path: '/collections', component: Collections, props: (route) => ({ name: "collections" }),
        // children: [
        //     {
        //         path: '/:name/edit',
        //         component: Collections,
        //         // props: true,
        //         props: (route) => ({ name: "collections" }),
        //     }
        // ]
    },
]

const router = new VueRouter({
    routes
})

const app = new Vue({
    data: vm => ({
        items: [
           { to: '/', text:'Dashboard'},
           { to: '/layouts', text:'Layouts'},
           { to: '/pages', text:'Pages'},
        //    { to: '/collections/pages', text:'Pages'},
           { to: '/collections', text:'Collections'},
           { to: '/blocks', text:'Blocks'},
        //    { to: '/', text:'Dashboard'},
        ]   
    }),
    components: {
        NavigationDrawer
    },
    router,
    el: '#app',
    vuetify: new Vuetify({
        theme: {
            dark: true
        }
    })
})