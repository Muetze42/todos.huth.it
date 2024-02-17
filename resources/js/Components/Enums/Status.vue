<script setup>
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import status from '@/data/status.json'
</script>

<template>
  <Menu
    v-if="user && user.is_admin"
    class="relative inline-block text-left"
    as="div"
    @click.prevent
  >
    <MenuButton
      class="transition-all transform status flex gap-1.5 justify-center"
      :title="'Status: ' + status[state].label"
    >
      <font-awesome-icon :icon="['far', status[state].icon]" />
    </MenuButton>
    <transition
      enter-active-class="transition duration-100 ease-out"
      enter-from-class="transform scale-95 opacity-0"
      enter-to-class="transform scale-100 opacity-100"
      leave-active-class="transition duration-75 ease-in"
      leave-from-class="transform scale-100 opacity-100"
      leave-to-class="transform scale-95 opacity-0"
    >
      <MenuItems
        class="absolute border flex flex-col bg-gray-800 menu-items border border-gray-700/50 p-1 z-30"
      >
        <MenuItem
          v-for="(item, value) in status"
          :key="value"
          :disabled="state == value"
          :class="{ hidden: state == value }"
          as="button"
          type="button"
          class="btn whitespace-nowrap flex gap-1.5 justify-start items-center"
          @click="setState(value)"
        >
          <font-awesome-icon :icon="['far', item.icon]" fixed-width />
          {{ item.label }}
        </MenuItem>
      </MenuItems>
    </transition>
  </Menu>
  <div v-else class="status" :title="'Status: ' + status[state].label">
    <font-awesome-icon :icon="['far', status[state].icon]" />
  </div>
</template>

<script>
import { router } from '@inertiajs/vue3'

export default {
  props: {
    id: {
      type: [String, Number],
      required: true
    },
    state: {
      type: Number,
      required: true
    }
  },
  methods: {
    setState(state) {
      router.post('status/' + this.id, { status: state }, { preserveScroll: true })
    }
  }
}
</script>
