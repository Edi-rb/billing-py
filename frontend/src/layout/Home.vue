<template>
  <v-app
    class="bg-color--bluelight">
    <dasboard-app-bar />

    <div class="bt_page-content">
      <dasboard-drawer />

      <dasboard-view />
    </div>
  </v-app>
</template>

<script>
// @ is an alias to /src
import DasboardAppBar from '@/components/dashboard/AppBar.vue'
import DasboardDrawer from '@/components/dashboard/Drawer.vue'
import DasboardView from '@/components/dashboard/View.vue'

export default {
  name: 'DashboardHome',

  components: {
    DasboardAppBar,
    DasboardDrawer,
    DasboardView
  }
}
</script>
