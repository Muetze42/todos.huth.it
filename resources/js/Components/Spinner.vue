<script setup>
defineProps({
  iconClass: {
    type: String,
    required: false,
    default: null
  }
})
</script>
<template>
  <font-awesome-icon
    :icon="['fas', 'spinner']"
    class="animate-spin"
    :class="iconClass"
    fixed-width
  />
</template>
