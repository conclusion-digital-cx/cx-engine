import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'

// Load common components
import './components'
import './layouts'

import vuetify from './plugins/vuetify'
import service from './service.js'
import serviceFactory from './serviceFactory.js'

//  Mixin service
// TODO add in API ?
Vue.prototype.$service = service(store.state.settings)
Vue.prototype.$serviceFactory = serviceFactory(store.state.settings)

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

new Vue({
  router,
  store,
  vuetify,
  render: h => h(App)
}).$mount('#app')
