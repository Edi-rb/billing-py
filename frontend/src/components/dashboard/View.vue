<template>
  <v-content>
    <router-view />

    <DashboardFooter />
  </v-content>
</template>

<script>
import DashboardFooter from '@/components/dashboard/Footer'

export default {
  name: 'DasboardView',

  components: {
    DashboardFooter
  }
}
</script>
