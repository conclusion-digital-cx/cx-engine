/*
this.$store.dispatch('LOADING')
this.$store.dispatch('LOADING_DONE')
this.$store.dispatch('UI_MESSAGE', {text: 'Hello'})
this.$store.dispatch('UI_MESSAGE', {text: 'Hello', color: 'error'})
this.$store.dispatch('UI_ERROR_MESSAGE', 'Hello')
this.$store.dispatch('UI_MESSAGE_MODAL', 'Hello')
this.$store.dispatch('UI_SERVER_ERROR')

*/
export default {
  state: {
  // App wide UI
    dark: false, // window.localStorage.getItem('theme') === 'dark',
    errors: [],
    snackbar: {
      show: false,
      color: 'error', // info success,
      text: 'Oops',
      title: 'Server error'
    }
  },
  getters: {

  },

  // Async
  actions: {
    UI_SERVER_ERROR ({ commit }, text = 'Something went wrong, try again later.') {
      commit('UI_MESSAGE', { text, color: 'error', modal: true })
    },
    UI_MESSAGE ({ commit }, payload) { commit('UI_MESSAGE', payload) },
    UI_ERROR_MESSAGE ({ commit }, text) { commit('UI_MESSAGE', { text, color: 'error' }) },
    UI_ERROR_MESSAGE_MODAL ({ commit }, text) { commit('UI_MESSAGE', { text, modal: true }) },
    UI_MESSAGE_MODAL ({ commit }, payload) { commit('UI_MESSAGE', { ...payload, modal: true }) }
  },
  mutations: {
    LOADING (state) { state.loading = true },

    LOADING_DONE (state) { state.loading = false },

    THEME (state, value) {
      window.localStorage.setItem('theme', value ? 'dark' : 'light')
      state.dark = !!value
    },

    // Layout UI
    // https://codepen.io/ktsn/pen/Bzxkjd
    UI_MESSAGE (state, { title = 'Server error', text = '', color, modal = false, timeout = 5000 } = {}) {
      state.snackbar = {
        title,
        show: true,
        color,
        text,
        modal,
        timeout
      }
    }
  }
}
