/* ============
 * Actions for the account module
 * ============
 *
 * The actions that are available on the
 * account module.
 */

import * as types from './mutation-types'

export const loadSaved = ({ commit }) => {
  const data = localStorage.getItem('accessToken')
  if (data) {
    commit(types.STORE, { email: data })
  }
}

export const store = ({ commit }, payload) => {
  commit(types.STORE, payload)
}

export const update = ({ commit }, payload) => {
  commit(types.UPDATE, payload)
}

export const check = ({ commit, state }) => {
  commit(types.CHECK)
}

export const load = ({ commit, state }) => {
  commit(types.STORE, state.accountLoaded)
  // commit(types.UNLOAD)
}

export default {
  store,
  update,
  loadSaved,
  check,
  load
}
