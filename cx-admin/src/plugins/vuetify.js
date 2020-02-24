import Vue from 'vue'
// a-la-carte
// import Vuetify from 'vuetify/lib';
// full build
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify)

export default new Vuetify({
  theme: {
    dark: true,
  },
  // options: {
  //   customProperties: true
  // },
  // theme: { disable: true }, // <= Removes default coloring
  icons: {
    iconfont: 'mdi'
  }
})
