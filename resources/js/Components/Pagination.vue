<script setup>
defineProps({
  links: {
    type: Array,
    required: true
  }
})
</script>

<template>
  <div v-if="links.length > 3" class="pagination">
    <template v-for="link in links">
      <Component
        :is="link.url && !link.active ? 'Link' : 'span'"
        v-if="link.url || link.label === '...'"
        :key="link.label"
        :href="link.url"
        :class="{ 'btn-disabled': link.active }"
        class="btn sm:gap-1"
      >
        <font-awesome-icon
          v-if="link.label === 'Previous'"
          :icon="['far', 'angles-left']"
          fixed-width
          size="xs"
        />
        {{ link.label }}
        <font-awesome-icon
          v-if="link.label === 'Next'"
          :icon="['far', 'angles-right']"
          fixed-width
          size="xs"
        />
      </Component>
    </template>
  </div>
</template>
