/* Pseudo file for IDE */
import Vue from 'vue'
import { Link } from '@inertiajs/vue3'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

import Dialog from '@/Components/Dialog/Dialog.vue'
import EditForm from '@/Pages/Project/EditForm.vue'
import Layout from '@/Components/Layout.vue'
import Pagination from '@/Components/Pagination.vue'
import Spinner from '@/Components/Spinner.vue'
import Status from '@/Components/Enums/Status.vue'

Vue.component('Dialog', Dialog)
Vue.component('EditForm', EditForm)
Vue.component('FontAwesomeIcon', FontAwesomeIcon)
Vue.component('Layout', Layout)
Vue.component('Link', Link)
Vue.component('Pagination', Pagination)
Vue.component('Spinner', Spinner)
Vue.component('Status', Status)
Vue.mixin({
  data() {
    return {
      user: {
        nickname: 'String',
        avatar: 'String',
        is_admin: [true, false]
      },
      page: {
        title: 'String'
      }
    }
  },
  methods: {
    errorHandler(error) {
      console.log(error)
    }
  }
})
