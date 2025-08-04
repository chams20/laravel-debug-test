<script setup>
import { onMounted, ref } from "vue";

defineProps({
  modelValue: String,
  label: String,
  name: {
    type: String,
    required: true,
  },
});

defineEmits(["update:modelValue"]);

const input = ref(null);

onMounted(() => {
  if (input.value.hasAttribute("autofocus")) {
    input.value.focus();
  }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
  <div>
    <label :for="name" class="font-medium text-sm text-gray-700">
      <span v-if="label">{{ label }}</span>
    </label>
    <input
      :name="name"
      ref="input"
      class="p-3 w-full outline-0 border border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm transition"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
    />
  </div>
</template>
