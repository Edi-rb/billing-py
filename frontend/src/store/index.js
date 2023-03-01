import Vue from 'vue'
import Vuex from 'vuex'

import account from './modules/account'
import auth from './modules/auth'
import drawer from './drawer'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {

  },
  mutations: {

  },
  actions: {

  },
  modules: {
    account,
    auth,
    drawer
  }
})
