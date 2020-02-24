import service from './service'

export const namespaced = true

export const state = () => ({
  items: []
})

export const actions = {
  async load ({ commit }) {
    commit('set', await service.get({ populate: 'orders' }))
  },

  async delete ({ commit, dispatch }, id) {
    commit('set', await service.deleteById(id))

    // Quick fix, reload data
    dispatch('load')
  }
}

export const getters = {

}

export const mutations = {
  set: (state, items) => {
    state.items = items
  }
}
