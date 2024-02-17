<script setup>
import { Dialog, DialogPanel, TransitionRoot } from '@headlessui/vue'
import TransitionChildVar1 from '@/Components/Dialog/TransitionChildVar1.vue'
import DialogOverlay from '@/Components/Dialog/DialogOverlay.vue'
import TransitionChildVar2 from '@/Components/Dialog/TransitionChildVar2.vue'
defineEmits(['close', 'submit'])
</script>

<template>
  <TransitionRoot as="template">
    <Dialog as="div" class="fixed z-50 inset-0 px-4">
      <div
        class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
      >
        <button type="button" class="sr-only" aria-label="catch focus button" />
        <TransitionChildVar1>
          <DialogOverlay @close="$emit('close')" />
        </TransitionChildVar1>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">
          &#8203;
        </span>
        <TransitionChildVar2>
          <DialogPanel
            as="section"
            class="p-0 border border-gray-800/50 dark:bg-gray-900 rounded-sm inline-block align-bottom overflow-hidden shadow-xl transform transition-all sm:align-middle w-full text-left"
            :class="size"
          >
            <div v-if="title" class="border-b border-gray-600/20 px-2 py-1 font-medium">
              {{ title }}
            </div>
            <slot />
            <div v-if="withFooter" class="border-t border-gray-600/20 flex justify-end gap-2 p-2">
              <button type="button" class="btn px-2" :class="closeBtnClass" @click="$emit('close')">
                {{ closeBtnText }}
              </button>
            </div>
          </DialogPanel>
        </TransitionChildVar2>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script>
export default {
  props: {
    size: {
      type: String,
      required: false,
      default: 'max-w-md'
    },
    title: {
      type: String,
      required: false,
      default: null
    },
    closeBtnText: {
      type: String,
      required: false,
      default: 'Close'
    },
    closeBtnClass: {
      type: String,
      required: false,
      default: ''
    },
    withFooter: {
      type: Boolean,
      required: false,
      default: true
    }
  },
  created() {
    let ref = this
    document.addEventListener('keyup', function (event) {
      if (event.key === 'Escape' || event.code === 'Escape') {
        ref.$emit('close')
      }
    })
  }
}
</script>
