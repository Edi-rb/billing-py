<template>
  <v-container
    fluid
    tag="section"
    class="bt_users"
    no-gutters>
    <v-row
      no-gutters>
      <v-col
        cols="12"
        md="">
        <v-tabs
          v-model="tab"
          centered
          background-color="light-blue darken-4"
          class="bt_tabs bt_tabs--blue"
          grow
        >
          <v-tab
            class="bt_tabs__opc"
            href="#tab-1">
            <v-icon
              class="opc-icon">group</v-icon>
            Lista de Usuarios
          </v-tab>
          <v-tab
            class="bt_tabs__opc"
            href="#tab-2">
            <v-icon
              class="opc-icon">group</v-icon>
            Agregar Usuario
          </v-tab>
        </v-tabs>

        <v-tabs-items v-model="tab">
          <v-tab-item id="tab-1"
          >
            <UsersCrudTable/>
          </v-tab-item>
          <v-tab-item id="tab-2"
          >
            <p>dd</p>
          </v-tab-item>
        </v-tabs-items>
      </v-col>
    </v-row>
  </v-container>

</template>

<script>
import UsersCrudTable from '@/components/users/CrudUserList.vue'

export default {
  name: 'Pguser',
  components: {
    UsersCrudTable
  },
  data () {
    return {
      tab: null,
      items: [
        'Appetizers', 'Entrees', 'Deserts', 'Cocktails'
      ],
      text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'
    }
  }
}
</script>
