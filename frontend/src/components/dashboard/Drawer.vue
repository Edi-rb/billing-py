<template>
  <v-navigation-drawer
    v-model="drawer"
    :expand-on-hover="true"
    :mini-variant="true"
    :temporary="false"
    :permanent="true"
    hide-overlay
    dark
    class="bt_sidebar"
  >
    <v-list>
      <v-list-item class="px-2">
        <v-list-item-avatar>
          <v-img :src="userAvatar"></v-img>
        </v-list-item-avatar>
      </v-list-item>

      <v-list-item link>
        <v-list-item-content>
          <v-list-item-title class="title">{{name}}</v-list-item-title>
          <v-list-item-subtitle>{{userEmail}}</v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>
    </v-list>

    <v-divider></v-divider>

    <v-list
      class="bt_sidebar__list">

      <v-list-item
        :to="routeIndex.children[0].path"
        class="list-tem">
        <v-list-item-icon>
          <v-icon class="v-list-item__icon v-list-group__header__prepend-icon">
            {{routeIndex.meta.action}}
          </v-icon>
        </v-list-item-icon>
        <v-list-item-content>
            <v-list-item-title>{{routeIndex.name}}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-list-group
        v-for="(item,index) in routesGroup"
        :key="index"
        :prepend-icon="item.meta && item.meta.action"
        class="list-tem"
      >
        <template v-slot:activator>
          <v-list-item-content>
            <v-list-item-title
              v-text="item.name"></v-list-item-title>
          </v-list-item-content>
        </template>

        <v-list-item
          v-for="(child, index) in item.children"
          :key="index"
          :to="child.path"
        >
          <v-list-item-content>
            <v-list-item-title v-text="child.name"></v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list-group>
    </v-list>
  </v-navigation-drawer>
</template>

<script>

import { mapGetters } from 'vuex'

export default {
  name: 'DasboardDrawer',

  computed: {
    ...mapGetters('account', ['name', 'userAvatar', 'userEmail']),

    drawer: {
      get () {
        return this.$store.state.drawer
      },
      set (val) {
        this.$store.commit('SET_DRAWER', val)
        // if (val) {
        //   this.$store.commit('SET_PERMANENT', true)
        //   this.$store.commit('SET_TEMPORARY', false)
        // } else {
        //   this.$store.commit('SET_PERMANENT', false)
        //   this.$store.commit('SET_TEMPORARY', true)
        // }
      }
    },
    routesGroup () {
      return this.$router.options.routes.filter(r => r.name !== 'Login' && r.name !== 'Inicio' && r.name !== 'Not found')
    },
    routeIndex () {
      return this.$router.options.routes.find(r => r.name === 'Inicio')
    }
  }
}
</script>
