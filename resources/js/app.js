import './bootstrap'

import { createApp, h } from 'vue'
import { createInertiaApp, Link } from '@inertiajs/vue3'
import * as Sentry from '@sentry/vue'

import Dialog from '@/Components/Dialog/Dialog.vue'
import EditForm from '@/Pages/Project/EditForm.vue'
import Layout from '@/Components/Layout.vue'
import Pagination from '@/Components/Pagination.vue'
import Spinner from '@/Components/Spinner.vue'
import Status from '@/Components/Enums/Status.vue'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { library } from '@fortawesome/fontawesome-svg-core'

import {
  faAnglesLeft,
  faAnglesRight,
  faArrowDown,
  faArrowUp,
  faBan,
  faCheck,
  faPause,
  faPenToSquare,
  faTag,
  faWrench
} from '@fortawesome/pro-regular-svg-icons'
library.add(
  faAnglesLeft,
  faAnglesRight,
  faArrowDown,
  faArrowUp,
  faBan,
  faCheck,
  faPause,
  faPenToSquare,
  faTag,
  faWrench
)

import { faGithub } from '@fortawesome/free-brands-svg-icons'
library.add(faGithub)

import { faSpinner, faCaretRight, faUpToLine, faDownToLine } from '@fortawesome/pro-solid-svg-icons'
library.add(faSpinner, faCaretRight, faUpToLine, faDownToLine)

import { faCode } from '@fortawesome/pro-duotone-svg-icons'
library.add(faCode)

// noinspection JSIgnoredPromiseFromCall
createInertiaApp({
  resolve: (name) => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })
    Sentry.init({
      app,
      dsn: import.meta.env.VITE_SENTRY_DSN_PUBLIC,
      tunnel: '/api/sentry-tunnel',
      trackComponents: true,
      logErrors: true
    })

    app
      .use(plugin)
      .mixin({
        computed: {
          user() {
            return this.$page.props.user
          },
          page() {
            return this.$page.props.page
          }
        },
        methods: {
          errorHandler(error) {
            let status = error.response.status
            if (status === 419 || status === 503) {
              let message =
                status === 419
                  ? 'Your session has expired. Click OK to reload the page.'
                  : 'There is an update in progress. This lasts only a few seconds.'
              alert(message)
              location.reload()
            } else {
              error.response && error.response.data && error.response.data.message
                ? alert('Error ' + status + ': ' + error.response.data.message)
                : alert('Error ' + status)
            }
          }
        }
      })
      .component('Dialog', Dialog)
      .component('EditForm', EditForm)
      .component('Layout', Layout)
      .component('Pagination', Pagination)
      .component('Spinner', Spinner)
      .component('Status', Status)
      .component('FontAwesomeIcon', FontAwesomeIcon)
      .component('Link', Link)
      .mount(el)
  }
})
