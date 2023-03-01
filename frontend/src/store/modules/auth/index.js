
import Vue from 'vue'

const types = {
  CHECK: 'CHECK',
  LOGIN: 'LOGIN',
  LOGOUT: 'LOGOUT'
}

export default {
  namespaced: true,
  state: {
    accessToken: '',
    authenticated: false
  },
  mutations: {
    [types.CHECK] (state) {
      const accessToken = localStorage.getItem('accessToken')
      state.authenticated = !!accessToken
      state.accessToken = accessToken
      if (accessToken) {
        Vue.$http.defaults.headers.common.Authorization = `JWT ${accessToken}`
      }
    },

    [types.LOGIN] (state, token) {
      state.authenticated = true
      state.accessToken = token
      // localStorage.setItem('accessToken', token)
      // Vue.$http.defaults.headers.common.Authorization = `JWT ${token}`
    },

    [types.LOGOUT] (state) {
      state.authenticated = false
      state.accessToken = ''
      // localStorage.removeItem('accessToken')
      // Vue.$http.defaults.headers.common.Authorization = null
    }
  },
  actions: {
    check ({ commit }) {
      commit(types.CHECK)
    },

    login ({ commit, dispatch }, token) {
      commit(types.LOGIN, token)
    },

    logout ({ commit, dispatch }) {
      dispatch('app/removePeriod', {}, { root: true })
      commit(types.LOGOUT)
    }
  }
}
