<script setup>
import { ref } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import status from '@/data/status.json'

function move(task, method) {
  router.post(
    'tasks/' + task + '/order',
    {
      method: method
    },
    {
      preserveScroll: true
    }
  )
}

function destroyTask(task) {
  if (confirm('Delete this task?') == true) {
    router.delete('tasks/' + task.id)
  }
}

function destroyReference(reference) {
  if (confirm('Delete this reference?') == true) {
    router.delete('references/' + reference)
  }
}

const form = useForm({
  name: null,
  description: null,
  status: 2,
  visibility: 0
})

const referenceForm = useForm({
  url: null
})

defineProps({
  project: {
    type: Object,
    required: true
  },
  tasks: {
    type: Object,
    required: true
  },
  activities: {
    type: Object,
    required: true
  }
})

function editTask(task) {
  taskToEdit.value = task.id
  form.name = task.name
  form.description = task.description
}

const showEditForm = ref(false)
const taskToEdit = ref(null)

/**
 * @property project.id
 * @property project.name
 * @property project.url
 * @property project.route
 * @property project.status
 * @property project.created_at
 * @property project.updated_at
 */
</script>

<template>
  <Layout :activities="activities">
    <div class="border-slate-800 bg-slate-800/50 divide-y divide-slate-700/70">
      <div class="flex gap-1 items-center p-2">
        <Status :id="project.id" :state="project.status" />
        <h1 class="text-lg grow font-medium p-2">{{ project.name }}</h1>
        <button
          v-if="user && user.is_admin && taskToEdit === null"
          class="btn h-8"
          @click="showEditForm = true"
        >
          <font-awesome-icon :icon="['far', 'pen-to-square']" />
        </button>
      </div>
      <div v-if="project.url" class="p-2.5">
        <a :href="project.url" target="_blank">
          {{ project.url }}
        </a>
      </div>
      <div v-if="tasks.length" class="p-2">
        <ul class="flex flex-col gap-2">
          <li
            v-for="task in tasks"
            :key="task.id"
            class="p-1 border border-slate-700 rounded-sm flex items-start gap-2"
          >
            <div class="flex flex-col gap-1">
              <Status :id="task.id" :state="task.status" />
              <button
                v-if="user && user.is_admin && taskToEdit === null"
                class="status flex gap-1.5 justify-center"
                title="Delete task"
                @click="destroyTask(task)"
              >
                <font-awesome-icon :icon="['far', 'ban']" />
              </button>
            </div>
            <div v-if="taskToEdit !== task.id" class="grow">
              <div class="font-bold">{{ task.name }}</div>
              <p v-if="task.description">{{ task.description }}</p>
              <div
                v-if="task.references || (user && user.is_admin)"
                class="px-1 mt-1 border border-slate-700"
              >
                <div class="font-medium underline">&nbsp;References&nbsp;</div>
                <ul class="flex flex-col gap-1">
                  <li
                    v-for="(reference, referenceId) in task.references"
                    :key="referenceId"
                    class="flex gap-1"
                  >
                    <button class="btn" @click="destroyReference(referenceId)">
                      <font-awesome-icon :icon="['far', 'ban']" />
                    </button>
                    <a :href="reference" target="_blank">{{ reference }}</a>
                  </li>
                </ul>
                <form
                  v-if="user && user.is_admin"
                  class="flex gap-2 mt-1"
                  @submit.prevent="
                    referenceForm.post('/references/' + task.id, {
                      onSuccess: () => {
                        form.reset()
                      }
                    })
                  "
                >
                  <input v-model="referenceForm.url" type="text" class="form-input py-1" required />
                  <div v-if="referenceForm.errors.url" class="bg-red-600 text-red-50 px-1 font-semibold">
                    {{ referenceForm.errors.url }}
                  </div>
                  <button
                    type="submit"
                    class="btn"
                    :disabled="!referenceForm.url"
                    :class="{ 'btn-disabled': !referenceForm.url }"
                  >
                    Add Reference
                  </button>
                </form>
              </div>
            </div>
            <form
              v-else
              class="grow p-1 flex flex-col"
              @submit.prevent="
                form.put('/tasks/' + task.id, {
                  onSuccess: () => {
                    taskToEdit = null
                    form.reset()
                  }
                })
              "
            >
              <input v-model="form.name" type="text" class="form-input w-full" required />
              <div v-if="form.errors.name" class="bg-red-600 text-red-50 px-1 font-semibold">
                {{ form.errors.name }}
              </div>
              <textarea v-model="form.description" class="form-textarea w-full" />
              <div v-if="form.errors.description" class="bg-red-600 text-red-50 px-1 font-semibold">
                {{ form.errors.description }}
              </div>
              <div class="py-1 flex gap-2">
                <button type="button" class="btn btn-danger" @click="taskToEdit = null">
                  Cancel
                </button>
                <button type="submit" class="btn">Save</button>
              </div>
            </form>
            <div v-if="user && user.is_admin && taskToEdit === null" class="w-8">
              <button
                type="button"
                class="btn w-full"
                title="Move up"
                @click="move(task.id, 'moveOrderUp')"
              >
                <font-awesome-icon :icon="['far', 'arrow-up']" />
              </button>
              <button
                type="button"
                class="btn w-full"
                title="Move down"
                @click="move(task.id, 'moveOrderDown')"
              >
                <font-awesome-icon :icon="['far', 'arrow-down']" />
              </button>
              <button type="button" class="btn w-full" title="Edit" @click="editTask(task)">
                <font-awesome-icon :icon="['far', 'pen-to-square']" fixed-width />
              </button>
            </div>
          </li>
        </ul>
      </div>
      <form
        v-if="user && user.is_admin && taskToEdit === null"
        class="p-2 flex flex-col gap-2"
        @submit.prevent="
          form.post('/tasks/' + project.id, {
            onSuccess: () => {
              form.reset()
            }
          })
        "
      >
        Create a Task
        <input v-model="form.name" type="text" class="form-input w-full" required />
        <div v-if="form.errors.name" class="bg-red-600 text-red-50 px-1 font-semibold">
          {{ form.errors.name }}
        </div>
        <textarea v-model="form.description" class="form-textarea w-full" />
        <div v-if="form.errors.description" class="bg-red-600 text-red-50 px-1 font-semibold">
          {{ form.errors.description }}
        </div>
        <div>
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
        <div>
          <button type="submit" class="btn">Create</button>
        </div>
      </form>
    </div>
  </Layout>
  <EditForm v-if="showEditForm" :project="project" @close="showEditForm = false" />
</template>
