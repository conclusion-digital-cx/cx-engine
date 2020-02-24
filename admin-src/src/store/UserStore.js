// https://github.com/garethredfern/vue-auth-demo/tree/master/src

import { AuthService } from '@/services/AuthService'

const tweakUser = user => ({
  ...user
  // email: user.username
})

const { localStorage } = window

export const UserStore = {
  state: {
    user: tweakUser(AuthService.currentTokenData()),
    company: JSON.parse(localStorage.getItem('company')) || {}
  },
  getters: {
    getUser: state => {
      return state.user
    }
  },
  mutations: {
    SET_USER: (state, payload) => {
      state.user = tweakUser(payload)
    },
    SET_COMPANY: (state, payload) => {
      state.company = payload
      // window.company = payload
      localStorage.setItem('company', JSON.stringify(payload))
    }
  },
  actions: {
    SET_COMPANY: (context, payload) => { context.commit('SET_COMPANY', payload) },

    SET_USER: (context, payload) => { context.commit('SET_USER', payload) },

    USER_CREATE: async (context, payload) => {
      return AuthService.signup(payload)
    },

    USER_LOGIN: async (context, payload) => {
      const resp = await AuthService.login(payload)
      const tokenPayload = AuthService.parseJwt(resp.access_token)
      // Update store
      context.commit('SET_USER', {
        username: payload.username || payload.email,
        ...tokenPayload
      })
      // this.$router.push(redirectTo)
      return tokenPayload
    },

    logoutUser: (context, payload) => {
      AuthService.logout()
      context.commit('SET_USER', null)
    }

  }
}
