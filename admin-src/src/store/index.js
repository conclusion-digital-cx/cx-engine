import Vue from 'vue'
import Vuex from 'vuex'
import UI from './UI'
import { UserStore } from './UserStore'
import createPersistedState from 'vuex-persistedstate'

Vue.use(Vuex)

const state = {
}

const actions = {
}

const getters = {
}

const mutations = {
}

export default new Vuex.Store({
  modules: {
    UI,
    UserStore,
    settings: {
      namespaced: true,
      state: {
        headers: {
          Authorization: ''
        },
        server: '',
        apiKey: ''
      },
      mutations: {
        set: (state, payload) => {
          Object.assign(state, payload)
        }
      }
    },
    types: {
      namespaced: true,
      state: {
        items: []
      },
      mutations: {
        set: (state, payload) => {
          state.items = payload
        }
      }
    }
  },
  state,
  actions,
  getters,
  mutations,
  plugins: [createPersistedState()]
})
