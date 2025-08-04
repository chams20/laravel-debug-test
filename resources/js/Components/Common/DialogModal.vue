<script setup>
import Modal from "./Modal.vue";

defineEmits(["close"]);

defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  maxWidth: {
    type: String,
    default: "2xl",
  },
  closeable: {
    type: Boolean,
    default: true,
  },
});
</script>

<template>
  <Modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="$emit('close')">
    <div class="px-6 py-4">
      <div class="text-lg">
        <slot name="header" />
      </div>

      <div class="mt-4">
        <slot />
      </div>
    </div>

    <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 text-right">
      <slot name="footer" />
    </div>
  </Modal>
</template>
