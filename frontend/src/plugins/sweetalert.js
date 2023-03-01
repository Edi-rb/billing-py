import Vue from 'vue'
import VueSweetalert2 from 'vue-sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

const options = {
  confirmButtonColor: '#1b2f8a',
  cancelButtonColor: '#d0d4e5'
}

Vue.use(VueSweetalert2, options)

export default new VueSweetalert2({

})
