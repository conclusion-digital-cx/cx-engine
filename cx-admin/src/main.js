import Vue from 'vue'
// import Vue from 'https://cdn.jsdelivr.net/npm/vue@2.6.11/dist/vue.esm.browser.js'
import App from './App.vue'
import router from './router'
import store from './store/index'

// Load common components
import './components/register'
import './layouts/index'

import vuetify from './plugins/vuetify'
import Service from './service.js'
import ServiceFactory from './serviceFactory.js'

//  Mixin service
// TODO add in API ?
Vue.prototype.$service = Service(store.state.settings)
Vue.prototype.$serviceFactory = ServiceFactory(store.state.settings)

// Vue.prototype.$snackbar = (msg) => {
//     alert(msg)
//     console.log(this)
// }

Vue.prototype.$snackbarState = Vue.observable({
  text: 'loading...',
  visible: false
})

Vue.mixin({
  methods: {
    $snackbar (message, color = '') {
      console.log(message)
      this.$snackbarState.text = message
      this.$snackbarState.color = color
      this.$snackbarState.visible = true
    }
  }
})

Vue.config.productionTip = false

window.vue = new Vue({
  router,
  store,
  vuetify,
  render: h => h(App)
}).$mount('#app')
