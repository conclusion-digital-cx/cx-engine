import routes from './routes.js'
import NavigationDrawer from './components/NavigationDrawer.js'
import service from "./service.js"

// Global components
import "./components/index.js"

const router = new VueRouter({
    routes
})

//  Mixin service
Vue.prototype.$service = service
// Vue.prototype.$snackbar = (msg) => {
//     alert(msg)
//     console.log(this)
// } 

Vue.mixin({
    created() {
        this.$snackbar = (msg) => {
            // alert(msg)
            console.log(this)
            this.snackbar = true
            this.text = msg
        }
    }
})

const app = new Vue({
    data: vm => ({
        snackbar: false,
        text: '',
        // Navigation
        items: [
            { path: '/', text: 'Dashboard' },
            { path: '/themes', text: 'Themes' },
            { path: '/pages', text: 'Pages' },
            { path: '/users', text: 'Users' },
            { path: '/blocks', text: 'Blocks' },
            { path: '/media', text: 'Media' },
            { path: '/collections', text: 'Collections' },
            { divider: true },
            { path: '/settings', text: 'Settings' },
        ]
    }),
    computed: {
        _items() {
            return [
                ...this.items,
                // ...routes TODO auto add in plugins
            ]
        }
    },
    components: {
        NavigationDrawer
    },
    router,
    el: '#app',
    vuetify: new Vuetify({
        theme: {
            dark: true
        }
    }),
    created() {
        // this.snackbar = true
        // this.text = 'Cool'
    }
})