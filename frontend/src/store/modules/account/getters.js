/**
 * @vuepress
 * ---
 * title: Account store getters
 * headline: Getters to facilitate account info checking
 * ---
 */

/**
 * Getters for the account module
 *
 * The getters that are available on the
 * account module.
 */

export default {
  userId: (state) => state.userId,

  name: (state) =>
    `${state.firstName} ${state.lastName} ${state.secondLastName}`,

  userAvatar: (state) =>
    state.avatar,

  userEmail: (state) =>
    state.email,

  modelPermission: (state) => (app, model) => {
    const appAlt = app.replace('-', '_')
    const appD = state.permissions[app] || state.permissions[appAlt] || {}
    const modelAlt = model.replace('-', '_')
    return appD[model] || appD[modelAlt] || {}
  },

  /**
   * Check if user belong to a specific group
   */
  userInGroup: (state) => (groupName) =>
    state.groups.some(g => g.name === groupName),

  /**
   * Check if user belongs to the Admin group
   */
  userIsAdmin: (state) => state.groups.some(g => g.name === 'Admin'),

  /**
   * Checks if user has a given action permission in an app/model
   */
  userCan: (state, getters) => (action, app, model) => {
    const modelPermission = getters.modelPermission(app, model)
    const actionAlt = action.replace('-', '_')
    return modelPermission[action] || modelPermission[actionAlt]
  },

  /**
   * Shortcut for userCan('add')
   */
  userCanAdd: (state, getters) =>
    (app, model) => getters.userCan('add', app, model),
  /**
   * Shortcut for userCan('view')
   */
  userCanView: (state, getters) =>
    (app, model) => getters.userCan('view', app, model),
  /**
   * Shortcut for userCan('change')
   */
  userCanChange: (state, getters) =>
    (app, model) => getters.userCan('change', app, model),
  /**
   * Shortcut for userCan('delete')
   */
  userCanDelete: (state, getters) =>
    (app, model) => getters.userCan('delete', app, model)
}
