<template>
  <v-container
    fluid
    tag="section"
    class="bt_usersList"
    no-gutters>
    <v-row
      class="justify-center">
      <v-col
        cols="12"
        lg="10">
        <v-data-table
          :headers="headers"
          :items="desserts"
          sort-by="usertype"
          class="elevation-1 bt_table"
        >
          <template v-slot:top>
            <v-toolbar flat color="white">
              <v-toolbar-title>Usuarios</v-toolbar-title>
              <v-divider
                class="mx-4"
                inset
                vertical
              ></v-divider>
              <v-spacer></v-spacer>
              <v-dialog v-model="dialog" max-width="500px" class="bt_dialog">
                <template v-slot:activator="{ on }">
                  <v-btn color="primary" dark class="mb-2" v-on="on">
                    <v-icon
                      class="opc-icon mr-2">person_add</v-icon>
                      Nuevo Usuario
                  </v-btn>
                </template>
                <v-card>
                  <v-card-title
                    class="bt_dialog__title">
                    <span class="headline">{{ formTitle }}</span>
                  </v-card-title>

                  <v-card-text>
                    <v-container
                      class="bt_dialog__container">
                      <v-row>
                        <v-col cols="12" sm="6" md="12">
                          <v-text-field v-model="editedItem.name" label="Nombre de Usuario" outlined></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                          <v-text-field v-model="editedItem.usertype" label="Tipo de Usuario" outlined></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                          <v-text-field v-model="editedItem.email" label="Email" outlined></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                          <v-text-field v-model="editedItem.address" label="Dirección" outlined></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                          <v-text-field v-model="editedItem.photo" label="Foto" outlined></v-text-field>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-card-text>

                  <v-card-actions
                    class="bt_dialog__actions">
                    <v-spacer></v-spacer>
                    <v-btn color="light-blue lighten-5" outlined dense @click="close">Cancelar</v-btn>
                    <v-btn color="light-blue lighten-5" outlined dense @click="save">Guardar</v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-toolbar>
          </template>
          <template v-slot:item.actions="{ item }">
            <v-icon
              small
              class="btntb btntb--edit mr-1"
              @click="editItem(item)"
            >
              mdi-pencil
            </v-icon>
            <v-icon
              small
              class="btntb btntb--remove"
              @click="deleteItem(item)"
            >
              mdi-delete
            </v-icon>
          </template>
          <template v-slot:no-data>
            <v-btn color="primary" @click="initialize">Reset</v-btn>
          </template>
        </v-data-table>
      </v-col>
    </v-row>
  </v-container>

</template>

<script>
export default {
  name: 'UsersCrudTable',
  data: () => ({
    dialog: false,
    headers: [
      {
        text: 'Nombre',
        align: 'start',
        sortable: false,
        value: 'name'
      },
      { text: 'Tipo Usuario', value: 'usertype' },
      { text: 'Email', value: 'email' },
      { text: 'Dirección', value: 'address' },
      { text: 'Foto', value: 'photo' },
      { text: 'Actions', value: 'actions', sortable: false }
    ],
    desserts: [],
    editedIndex: -1,
    editedItem: {
      name: '',
      usertype: 0,
      email: 0,
      address: 0,
      photo: 0
    },
    defaultItem: {
      name: '',
      usertype: 0,
      email: 0,
      address: 0,
      photo: 0
    }
  }),

  computed: {
    formTitle () {
      return this.editedIndex === -1 ? 'Nuevo Usuario' : 'Editar Usuario'
    }
  },

  watch: {
    dialog (val) {
      val || this.close()
    }
  },

  created () {
    this.initialize()
  },

  methods: {
    initialize () {
      this.desserts = [
        {
          name: 'User Apellido 1',
          usertype: 4,
          email: 'email1@domain.com',
          address: '24 pte 43443',
          photo: 24
        },
        {
          name: 'User Apellido 2',
          usertype: 2,
          email: 'email1@domain.com',
          address: '24 pte 43443',
          photo: 24
        },
        {
          name: 'User Apellido 3',
          usertype: 5,
          email: 'email1@domain.com',
          address: '24 pte 43443',
          photo: 24
        },
        {
          name: 'User Apellido 4',
          usertype: 5,
          email: 'email1@domain.com',
          address: '24 pte 43443',
          photo: 24
        },
        {
          name: 'User Apellido 5',
          usertype: 5,
          email: 'email1@domain.com',
          address: '24 pte 43443',
          photo: 24
        },
        {
          name: 'User Apellido 6',
          usertype: 5,
          email: 'email1@domain.com',
          address: '24 pte 43443',
          photo: 24
        },
        {
          name: 'User Apellido 7',
          usertype: 5,
          email: 'email1@domain.com',
          address: '24 pte 43443',
          photo: 24
        },
        {
          name: 'User Apellido 3',
          usertype: 8,
          email: 'email1@domain.com',
          address: '24 pte 43443',
          photo: 24
        },
        {
          name: 'User Apellido 9',
          usertype: 5,
          email: 'email1@domain.com',
          address: '24 pte 43443',
          photo: 24
        },
        {
          name: 'User Apellido 10',
          usertype: 5,
          email: 'email1@domain.com',
          address: '24 pte 43443',
          photo: 24
        },
        {
          name: 'User Apellido 11',
          usertype: 5,
          email: 'email1@domain.com',
          address: '24 pte 43443',
          photo: 24
        },
        {
          name: 'User Apellido 12',
          usertype: 5,
          email: 'email1@domain.com',
          address: '24 pte 43443',
          photo: 24
        }
      ]
    },

    editItem (item) {
      this.editedIndex = this.desserts.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialog = true
    },

    deleteItem (item) {
      const index = this.desserts.indexOf(item)
      // this.$swal('Hello Vue world!!!')
      // confirm('Are you sure you want to delete this item?') && this.desserts.splice(index, 1)

      this.$swal({
        title: '¿Está seguro de eliminar este usuario?',
        text: 'Esta acción no podra revertirse',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si quiero borrarlo'
      }).then((result) => {
        if (result.value) {
          this.desserts.splice(index, 1)
          this.$swal.fire(
            'Borrado!',
            'El usuario se ha eliminado',
            'success'
          )
        }
      })
    },

    close () {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },

    save () {
      if (this.editedIndex > -1) {
        Object.assign(this.desserts[this.editedIndex], this.editedItem)
      } else {
        this.desserts.push(this.editedItem)
      }
      this.close()
    }
  }
}
</script>
