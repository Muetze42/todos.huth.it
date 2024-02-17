<script setup>
import { useForm } from '@inertiajs/vue3'
import { computed } from 'vue'
import status from '@/data/status.json'
import visibility from '@/data/visibility.json'

/**
 * @property project
 * @property project.id
 * @property project.id
 */

const props = defineProps({
  project: {
    type: Object,
    required: false,
    default() {
      return {
        name: null,
        url: null,
        status: null,
        visibility: null
      }
    }
  }
})

const form = useForm({
  name: props.project?.name,
  url: props.project?.url,
  status: props.project.status !== null ? props.project.status : 2,
  visibility: props.project.visibility !== null ? props.project.visibility : 0
})

const disableSubmit = computed(() => {
  return !form.name
})

defineEmits(['close'])
</script>

<template>
  <Dialog :show="true" :with-footer="false" @close="$emit('close')">
    <form
      class="flex flex-col gap-1"
      @submit.prevent="
        form.post('/' + (project && project.id ? project.id : ''), {
          onSuccess: () => $emit('close')
        })
      "
    >
      <div class="p-2">
        Name
        <input
          v-model="form.name"
          type="text"
          class="form-input w-full"
          placeholder="Name"
          required
        />
        <div v-if="form.errors.name" class="bg-red-600 text-red-50 px-1 font-semibold">
          {{ form.errors.name }}
        </div>
      </div>
      <div class="p-2">
        URL
        <input v-model="form.url" type="text" class="form-input w-full" placeholder="Url" />
        <div v-if="form.errors.url" class="bg-red-600 text-red-50 px-1 font-semibold">
          {{ form.errors.url }}
        </div>
      </div>
      <div class="p-2">
        Status
        <select v-model="form.status" class="form-select w-full">
          <option v-for="(item, value) in status" :key="value" :value="value">
            {{ item.label }}
          </option>
        </select>
        <div v-if="form.errors.status" class="bg-red-600 text-red-50 px-1 font-semibold">
          {{ form.errors.status }}
        </div>
      </div>
      <div class="p-2">
        Visibility
        <select v-model="form.visibility" class="form-select w-full">
          <option v-for="(name, value) in visibility" :key="value" :value="value">
            {{ name }}
          </option>
        </select>
        <div v-if="form.errors.visibility" class="bg-red-600 text-red-50 px-1 font-semibold">
          {{ form.errors.visibility }}
        </div>
      </div>
      <div class="border-t border-gray-600/20 flex justify-end gap-2 p-2">
        <button type="button" class="btn btn-danger" @click="$emit('close')">Cancel</button>
        <button
          type="submit"
          class="btn"
          :disabled="disableSubmit"
          :class="{ 'btn-disabled': disableSubmit }"
        >
          Save
        </button>
      </div>
    </form>
  </Dialog>
</template>
