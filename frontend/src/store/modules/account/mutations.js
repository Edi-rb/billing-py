/* ============
 * Mutations for the account module
 * ============
 *
 * The mutations that are available on the
 * account module.
 */

import Vue from 'vue'
import { STORE, UPDATE, CHECK, UNLOAD } from './mutation-types'

export default {
  [CHECK] (state) {
    let accountData = Vue.$storage.getItem('account')
    accountData = accountData ? JSON.parse(atob(accountData)) : null
    state.accountLoaded = accountData
  },

  [UNLOAD] (state) {
    state.accountLoaded = null
  },

  [STORE] (state, account) {
    state.userId = account.userId
    state.username = account.username
    state.firstName = account.firstName
    state.lastName = account.lastName
    state.secondLastName = account.secondLastName
    state.email = account.email
    // Profile information
    state.avatar = account.avatar
    state.phone = account.phone
    state.cellphone = account.cellphone
  },

  [UPDATE] (state, account) {
    state.userId = account.userId
    state.username = account.username
    state.firstName = account.firstName
    state.lastName = account.lastName
    state.secondLastName = account.secondLastName
    state.email = account.email
    // Profile information
    state.avatar = account.avatar
    state.phone = account.phone
    state.cellphone = account.cellphone
  }
}
