import TabView from './TabView.js'
import Form from './Form.js'
import tabs from './tabs.js'

export default [
    {
        path: '/pages',
        redirect: '/pages/published'
    },
    {
        path: '/pages/new', component: Form,
        props: (route) => ({
            name: "pages",
            value: route.query
        })
    },
    {
        path: '/pages/:status',
        component: TabView,
        props: (route) => ({
            name: "pages",
            tabs,
            ...route.params
        }),
        // children: [
        //     { path: ':status', component: Table, props: true },
        // ]
    },
    {
        path: '/pages/edit/:id', component: Form, props: (route) => ({
            name: "pages",
            value: route.query,
            ...route.params
        })
    },

]