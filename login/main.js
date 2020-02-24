import routes from './routes.js'
import service from './service.js'

const { Vue } = window
const { VueRouter } = window
const { Vuetify } = window

// Global components
// import './components/index.js'

const router = new VueRouter({
  routes
})

//  Mixin service
const $service = service()
Vue.prototype.$service = $service

Vue.prototype.$store = {
  actions: {
    async USER_LOGIN (payload) {
      try {
        const resp = await $service.login(payload)

        if (resp) {
          // this.$router.push(this.redirectTo)
          // window.localStorage.setItem('token', resp.token)
          window.location.href = `http://localhost:8080/#?token=${resp.token}`
        }
        return resp
      } catch (err) {
        alert(err)
      }
    }
  },

  dispatch (event, payload) {
    this.actions[event](payload)
  }
}

Vue.mixin({
  data: vm => ({
    // snackbar: false,
    // text: ''
  }),
  created () {
    this.$snackbar = (msg) => {
      // alert(msg)
      console.log(this)
      this.snackbar = true
      this.text = msg
    }
  }
})

const app = new Vue({
  el: '#app',
  components: {
  },
  data: vm => ({
    snackbar: false,
    text: ''
  }),
  computed: {
    _items () {
      return [
        ...this.items
        // ...routes TODO auto add in plugins
      ]
    }
  },
  router,
  vuetify: new Vuetify({
    theme: {
      dark: true
    }
  }),
  created () {
    // this.snackbar = true
    // this.text = 'Cool'
  }
})
