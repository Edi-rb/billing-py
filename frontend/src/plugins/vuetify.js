import Vue from 'vue'
import Vuetify from 'vuetify/lib'
import '@/sass/main.sass'

Vue.use(Vuetify)

const theme = {
  primary: '#273EAE',
  secondary: '#182A7C',
  accent: '#36C2CF',
  info: '#506FE4'
}

export default new Vuetify({
  theme: {
    themes: {
      light: theme
    }
  },
  icons: {
    iconfont: 'md'
  }
})
