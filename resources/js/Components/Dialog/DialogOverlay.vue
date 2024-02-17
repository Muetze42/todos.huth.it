<script setup>
import { DialogOverlay } from '@headlessui/vue'
defineEmits(['close'])
</script>

<template>
  <DialogOverlay
    class="fixed inset-0 bg-neutral-900 bg-opacity-75 transition-opacity cursor-pointer"
    @click="$emit('close')"
  />
</template>
