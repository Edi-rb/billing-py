<template>
  <v-app>
    <v-content>
      <v-container fluid fill-height tag="section" dark  class="bt_login pa-0">
        <v-row
          class="bt_login__container diagonal">
          <v-col cols="12" md="5" class="diagonal">
            <div class="diagonal__image"></div>
            <div class="diagonal__overlay"></div>
          </v-col>
          <v-col cols="12" md="7" class="bt_login__signup d-flex align-center justify-center">
              <v-form ref="form" v-model="valid" dark class="signup-form"  >

                <template v-if="!recovery">
                <h3 class="display-1 white--text font-weight-light py-5">
                  Ingreso al Sistema CFDI
                </h3>
                <v-text-field autofocus :disabled="loading" v-model="payload.user" :rules="userRules" label="Usuario" outlined class="signup-form__v-input white--text"
                ></v-text-field>
                <v-text-field :disabled="loading" v-model="payload.password" :rules="passwordRules" label="Contraseña" type="password" hint="Al menos 6 caracteres" outlined ></v-text-field>
                <v-checkbox :disabled="loading" v-model="payload.checkbox" label="Recordar Contraseña" class="signup-form__v-input"
                ></v-checkbox>

                <v-btn :loading="loading" :disabled="!valid"  color="info" class="mr-4" @click="submit">Entrar</v-btn>
                <v-btn :disabled="loading" color="secondary" @click="changeForm">Recuperar Contraseña</v-btn>
                <!-- <v-btn color="secondary" @click="clear">Borrar</v-btn> -->
              </template>

              <template v-else>
                <h3 class="display-1 white--text font-weight-light py-5">
                  Recuperar Contraseña
                </h3>
                <v-text-field label="Email Registrado" :rules="emailRules" outlined class="signup-form__v-input white--text"
                ></v-text-field>

                <v-btn :disabled="!valid" color="info" class="mr-4">Recuperar</v-btn>
                <v-btn color="warning" class="mr-4" @click="changeForm">Volver a intentar Acceder</v-btn>

              </template>
              </v-form>

          </v-col>
        </v-row>
      </v-container>
    </v-content>

  </v-app>

</template>

<script>
// import { validationMixin } from 'vuelidate'
// import { required, maxLength, email } from 'vuelidate/lib/validators'

export default {
  // mixins: [validationMixin],

  // validations: {
  //   user: { required, maxLength: maxLength(10) },
  //   password: { required, email },
  //   select: { required },
  //   checkbox: {
  //     checked (val) {
  //       return val
  //     }
  //   }
  // },

  data: () => ({
    // user: '',
    // password: '',
    // checkbox: false,
    payload: {},
    loading: false,
    recovery: false,
    valid: false,
    userRules: [
      v => !!v || 'Usuario requerido'
    ],
    passwordRules: [
      v => !!v || 'Contraseña requerida',
      v => (v && v.length >= 6) || 'La contraseña debe tener al menos 6 caracteres'
    ],
    emailRules: [
      v => !!v || 'E-mail is required',
      v => /.+@.+\..+/.test(v) || 'E-mail must be valid'
    ]
  }),

  // computed: {
  //   checkboxErrors () {
  //     const errors = []
  //     if (!this.$v.checkbox.$dirty) return errors
  //     !this.$v.checkbox.checked && errors.push('You must agree to continue!')
  //     return errors
  //   },
  //   userErrors () {
  //     const errors = []
  //     if (!this.$v.user.$dirty) return errors
  //     !this.$v.user.required && errors.push('Usuario es requerido')
  //     return errors
  //   },
  //   passwordErrors () {
  //     const errors = []
  //     if (!this.$v.password.$dirty) return errors
  //     !this.$v.user.maxLength && errors.push('La contraseña debe tener al menos 6 caracteres')
  //     !this.$v.password.required && errors.push('La contraseña es requerido')
  //     return errors
  //   }
  // },

  methods: {
    // clear () {
    //   this.$v.$reset()
    //   this.user = ''
    //   this.password = ''
    //   this.checkbox = false
    // },
    submit () {
      const user = {
        userId: 1,
        username: this.payload.user,
        firstName: this.payload.user,
        lastName: '',
        secondLastName: '',
        email: 'correo@falso.com',
        // Profile information
        avatar: 'https://picsum.photos/200/300',
        phone: '123456',
        cellphone: '22223456789'
      }
      this.loading = true
      var authentication = new Promise((resolve, reject) => {
        setTimeout(() => {
          if (this.payload.user.length > 1) {
            resolve(user)
          } else {
            reject(Error('No se pudo iniciar sesión')
            )
          }
        }, 1000)
      })
      authentication
        .then((res) => {
          this.$store.commit('account/STORE', res)
          this.$store.commit('auth/LOGIN', 'un token')
          this.$router.push({ name: 'Dashboard' })
          this.loading = false
        })
        .catch((err) => {
          this.loading = false
          throw err
        })
    },
    changeForm () {
      this.$refs.form.reset()
      this.recovery = !this.recovery
    }
  }
}
</script>
