import Vue from 'vue'
import Vuex from 'vuex'
import { UserStore } from './UserStore'
// import createPersistedState from 'vuex-persistedstate'
import ServiceFactory from '@/serviceFactory'

Vue.use(Vuex)

const state = {
}

const actions = {
}

const getters = {
}

const mutations = {
}

const urlParams = new URLSearchParams(window.location.search)

const settings = {
  namespaced: true,
  state: {
    ...window.config,
    headers: {
      Authorization: ''
    },
    server: urlParams.get('host') || '',
    defaultPrimaryKey: urlParams.get('defaultPrimaryKey') || 'id'
  },
  mutations: {
    set: (state, payload) => {
      Object.assign(state, payload)
    }
  }
}

export default new Vuex.Store({
  modules: {
    UserStore,
    settings,

    plugins: {
      namespaced: true,
      state: {
        items: []
      },
      mutations: {
        set: (state, payload) => {
          state.items = payload
        }
      },
      actions: {
        async getAll ({ rootState, commit }) {
          const serviceFactory = ServiceFactory(rootState.settings)
          const resp = await serviceFactory('plugins').getAll()
          commit('set', resp)
        },

        async update ({ rootState, commit }, payload) {
          const serviceFactory = ServiceFactory(rootState.settings)
          return serviceFactory('plugins').updateById(payload._id, payload)
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
      },
      actions: {
        async getAll ({ rootState, commit }) {
          const serviceFactory = ServiceFactory(rootState.settings)
          const resp = await serviceFactory('types').getAll()
          commit('set', resp)
        },

        async update ({ rootState, commit }, payload) {
          const serviceFactory = ServiceFactory(rootState.settings)
          return serviceFactory('types').updateById(payload._id, payload)
        }
      }
    }
  },
  state,
  actions,
  getters,
  mutations
  // plugins: [createPersistedState()]
})
