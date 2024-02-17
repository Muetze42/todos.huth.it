<template>
  <Layout :activities="activities">
    <div
      v-for="project in projects.data"
      :key="project.id"
      class="flex justify-between items-center gap-2"
    >
      <Link
        :href="project.route"
        class="flex items-center gap-4 border rounded-sm grow border-slate-800 ring-1 ring-transparent hover:ring-slate-700 p-2 bg-slate-800/50 hover:bg-slate-800/75 select-none"
      >
        <Status :id="project.id" :state="project.status" />
        <span>
          <span class="text-lg font-medium">{{ project.name }}</span>
          <span class="flex flex-wrap flex-col md:flex-row divide-x divide-slate-700/70 text-xs">
            <span class="md:pr-2">Created at: {{ project.created_at }}</span>
            <span class="md:pl-2">Updated at: {{ project.updated_at }}</span>
          </span>
        </span>
      </Link>
      <span v-if="user && user.is_admin" class="grid grid-cols-2 gap-1 place-content-center">
        <button type="button" class="btn" title="Move up" @click="move(project.id, 'moveOrderUp')">
          <font-awesome-icon :icon="['far', 'arrow-up']" />
        </button>
        <button
          type="button"
          class="btn"
          title="Move down"
          @click="move(project.id, 'moveOrderDown')"
        >
          <font-awesome-icon :icon="['far', 'arrow-down']" />
        </button>
        <button
          type="button"
          class="btn"
          title="Move to the top"
          @click="move(project.id, 'moveToStart')"
        >
          <font-awesome-icon :icon="['fas', 'up-to-line']" />
        </button>
        <button
          type="button"
          class="btn"
          title="Move to the end"
          @click="move(project.id, 'moveToEnd')"
        >
          <font-awesome-icon :icon="['fas', 'down-to-line']" />
        </button>
      </span>
    </div>
    <Pagination :links="projects.meta.links" class="mt-4" />
    <div v-if="user && user.is_admin" class="text-center">
      <button type="button" class="btn" @click="showCreateForm = true">Create Project</button>
    </div>
  </Layout>
  <EditForm v-if="showCreateForm" @close="showCreateForm = false" />
</template>
<script>
import { router } from '@inertiajs/vue3'
/**
 * @property projects
 * @property projects.data
 * @property projects.data.id
 * @property projects.data.name
 * @property projects.data.url
 * @property projects.data.route
 * @property projects.data.created_at
 * @property projects.data.updated_at
 * @property projects.meta
 * @property projects.meta.links
 */
export default {
  props: {
    projects: {
      type: Object,
      required: true
    },
    activities: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      showCreateForm: false
    }
  },
  methods: {
    move(project, method) {
      router.post(
        project + '/order',
        {
          method: method
        },
        {
          preserveScroll: true
        }
      )
    }
  }
}
</script>
