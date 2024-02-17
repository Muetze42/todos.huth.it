<script setup>
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
</script>
<template>
  <header class="nav-section border-b">
    <div class="container">
      <nav class="flex justify-between items-center">
        <div class="inline-flex flex-wrap gap-2 items-center">
          To-Do List
          <font-awesome-icon :icon="['far', 'angles-right']" fixed-width size="sm" />
          <Link href="/">
            <h1>Projects</h1>
          </Link>
          <template v-if="page && page.title">
            <font-awesome-icon :icon="['far', 'angles-right']" fixed-width size="sm" />
            <h2>{{ page.title }}</h2>
          </template>
        </div>
        <Menu v-if="user" v-slot="{ open }" as="div" class="relative inline-block">
          <MenuButton as="button" class="transition-all transform btn flex gap-1.5 justify-center">
            <img :src="user.avatar" :alt="user.nickname + '’s Avatar'" class="h-5 rounded-full" />
            <span>{{ user.nickname }}</span>
            <font-awesome-icon :icon="['fas', 'caret-right']" :class="{ 'rotate-90': open }" />
          </MenuButton>
          <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
          >
            <MenuItems class="absolute w-full bg-gray-800 menu-items border border-gray-700/50 p-1">
              <MenuItem as="button" type="button" class="btn btn-danger w-full" @click="logout"
                >Logout
              </MenuItem>
            </MenuItems>
          </transition>
        </Menu>
        <div v-else>
          <button class="btn" type="button" @click="showLogin = true">
            <font-awesome-icon :icon="['fab', 'github']" fixed-width />
            Login
          </button>
        </div>
      </nav>
    </div>
  </header>
  <Dialog :show="showLogin" title="Login" @close="showLogin = false">
    <div class="p-4 text-center flex flex-col gap-2">
      <div>
        <label class="checkbox">
          <input v-model="accepted" type="checkbox" class="form-checkbox" value="1" />
          <span class="mr-1">I Agree to</span>
          <a href="https://huth.it/privacy" target="_blank">Privacy Policy</a>
        </label>
      </div>
      <div>
        <label class="checkbox">
          <input v-model="remember" type="checkbox" class="form-checkbox" value="1" />
          <span>Remember me</span>
        </label>
      </div>
      <div>
        <button
          type="button"
          class="btn"
          :disabled="!accepted"
          :class="{ 'btn-disabled': !accepted }"
          value="1"
          @click="login"
        >
          <Spinner v-if="processing" />
          <font-awesome-icon v-else :icon="['fab', 'github']" fixed-width />
          Login
        </button>
      </div>
    </div>
  </Dialog>
  <main class="container py-3 grow">
    <div class="md:flex md:justify-between md:divide-x divide-slate-800">
      <div class="md:grow pt-6 md:pb-6 flex flex-col gap-4 md:mr-8">
        <slot />
      </div>
      <aside class="md:w-64 lg:w-80 md:shrink-0 pt-6 pb-12 md:pb-20 md:pl-6">
        <h2 class="font-medium border-b-2 border-slate-800 md:pl-4 pb-2 mb-3">
          {{ $page.url == '/' ? 'Last Activities' : 'Last Project Activities' }}
        </h2>
        <div class="flex flex-col gap-2 text-sm">
          <div
            v-for="activity in activities"
            :key="activity.id"
            class="border border-slate-800 rounded-sm p-2"
          >
            <div class="items-center nav flex flex-wrap gap-1">
              <img
                :src="activity.causer.avatar"
                class="h-3 rounded-full"
                :alt="activity.causer.name"
              />
              {{ activity.causer.name }}
            </div>
            <div class="nav">
              <Link class="w-full" :href="activity.route">
                {{ activity.message }}
              </Link>
            </div>
            <div class="text-xs">{{ activity.time }}</div>
          </div>
        </div>
      </aside>
    </div>
  </main>
  <footer class="nav-section border-t">
    <div class="container flex max-sm:flex-col gap-0.5 justify-center sm:justify-between">
      <div class="max-sm:text-center">
        To-Do List <font-awesome-icon :icon="['fad', 'code']" class="mr-1" /> 2024 by
        <a href="https://huth.it" target="_blank">Norman Huth</a>
      </div>
      <div class="max-sm:text-center">
        <font-awesome-icon :icon="['fab', 'github']" class="mr-1" />
        <a href="https://github.com/Muetze42/todos.huth.it" target="_blank">Source Code</a>
      </div>
    </div>
  </footer>
</template>
<script>
/**
 * @property activities
 * @property activities.data
 * @property activities.data.causer
 * @property activities.data.causer.id
 * @property activities.data.causer.name
 * @property activities.data.causer.avatar
 * @property activities.data.route
 * @property activities.data.message
 * @property activities.data.time
 */
import { router } from '@inertiajs/vue3'

export default {
  props: {
    title: {
      type: String,
      required: false
    },
    activities: {
      type: Object,
      required: false
    }
  },
  data() {
    return {
      accepted: false,
      remember: false,
      showLogin: false,
      processing: false
    }
  },
  methods: {
    login() {
      let ref = this
      this.processing = true
      axios
        .post('/auth', { remember: this.remember, accepted: this.accepted })
        .then(function (response) {
          if (response && response.status === 200) {
            window.location.href = response.data
          }
        })
        .catch(function (error) {
          error.response.status === 422
            ? alert(error.response.data.message)
            : ref.errorHandler(error)
          ref.processing = false
        })
    },
    logout() {
      if (confirm('Do you really want to log out?')) {
        router.post('/auth/logout')
      }
    }
  }
}
</script>
