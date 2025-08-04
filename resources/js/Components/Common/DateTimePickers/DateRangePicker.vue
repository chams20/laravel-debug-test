<script setup>
import { ref } from "vue";
import moment from "moment";
import Calendar from "./Partials/CalendarPopup";

const format = "DD/MM/YYYY";

const emit = defineEmits(["update:modelValue"]);

const props = defineProps({
  modelValue: {
    type: Array,
    validator: (value) =>
      value?.every((date) => !date || moment.isMoment(date)),
    default: [null, null],
  },
});

const endDatePicker = ref(null);

// Represents whether or not the datepicker popup is open for each field
const showStartDatePopup = ref(false);
const showEndDatePopup = ref(false);

const onStartDateChange = (date) => {
  // Timeouts are used to ensure that the event is launched after the blur event
  setTimeout(() => {
    emit("update:modelValue", [date, props.modelValue?.[1]]);
    // Open the next field if no value exists
    if (!props.modelValue?.[1]) {
      endDatePicker.value.focus();
      endDatePicker.value.click();
    }
    showStartDatePopup.value = false;
  }, 0);
};

const onEndDateChange = (date) => {
  // Timeouts are used to ensure that the event is launched after the blur event
  setTimeout(() => {
    emit("update:modelValue", [props.modelValue?.[0], date]);
    showEndDatePopup.value = false;
  }, 0);
};

// Common event fired after one of the date picker buttons loses focus
const onBlur = (event) => {
  // Check if the clicked item was inside the current component
  if (!event.currentTarget.contains(event.relatedTarget)) {
    showStartDatePopup.value = false;
    showEndDatePopup.value = false;
  } else {
    event.currentTarget.focus();
  }
};
</script>

<template>
  <div class="grid grid-cols-2">
    <label class="block font-medium text-sm text-gray-700">
      <span>From</span>
    </label>
    <label class="block font-medium text-sm text-gray-700">
      <span>To</span>
    </label>
    <button class="date-button rounded-l-md" @click="showStartDatePopup = true" @blur="onBlur">
      <div>
        {{modelValue?.[0]?.format(format)}}
        <Calendar
          :show="showStartDatePopup"
          :value="modelValue?.[0]"
          :with-time="false"
          @change="onStartDateChange"
        />
      </div>
    </button>
    <button
      ref="endDatePicker"
      class="date-button rounded-r-md"
      @click="showEndDatePopup = true"
      @blur="onBlur"
    >
      <div>
        {{modelValue?.[1]?.format(format)}}
        <Calendar
          :show="showEndDatePopup"
          :value="modelValue?.[1]"
          :with-time="false"
          @change="onEndDateChange"
        />
      </div>
    </button>
  </div>
</template>

<style lang="postcss" scoped>
.date-button {
  @apply relative px-3 h-12 w-full border border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow-sm;
}
</style>
