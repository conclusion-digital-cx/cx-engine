import List from './components/List.js'
import PagesList from './pages/index.js'
import Collections from './collections/index.js'
import Dashboard from './Dashboard.js'
import Collections_name from './collections/_name.js'

const routes = [
    { path: '/', component: Dashboard, props: true },
    {
        path: '/collections/:name', component: Collections_name,
        props: (route) => ({
            name: route.params.name,
            id: route.query.id
        })
    },

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

var app = new Vue({
    router,
    el: '#app',
    vuetify: new Vuetify()
})