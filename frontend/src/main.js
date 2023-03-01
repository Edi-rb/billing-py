import 'material-design-icons-iconfont/dist/material-design-icons.css'
import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from '@/store/index'
import './registerServiceWorker'
import chartist from './plugins/chartist'
import vuetify from './plugins/vuetify'
import sweetalert from './plugins/sweetalert'

Vue.config.productionTip = false

new Vue({
  router,
  store,
  chartist,
  vuetify,
  sweetalert,
  render: h => h(App)
}).$mount('#app')
