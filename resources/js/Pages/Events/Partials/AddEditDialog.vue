<script setup>
import { ref, watch } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import { toDatabaseFormat, toDisplayFormat } from "@/utils/formatDate";
import Dialog from "@/Components/Common/DialogModal";
import Button from "@/Components/Common/Button";
import Input from "@/Components/Common/Input";

const emit = defineEmits(["close"]);

const props = defineProps({
    itemToEdit: {
        type: Object,
        default: null,
    },
});

const show = ref(false);
const editing = ref(false);

const form = useForm({
    title: "",
    starts_at: null,
    ends_at: null,
});

// Called when the user clicks on the "Add new" button
const onAddNew = () => {
    form.reset();
    show.value = true;
    editing.value = false;
};

// Called when the user submits the form
const onSubmit = () => {
    const transform = (data) => ({
        ...data,
        starts_at: toDatabaseFormat(data.starts_at),
        ends_at: toDatabaseFormat(data.ends_at),
    });

    const requestParams = {
        preserveScroll: true,
        onSuccess: onClose,
    };

    if (editing.value) {
        form.transform(transform).put(
            route("events.update", props.itemToEdit.id),
            requestParams
        );
    } else {
        form.transform(transform).post(route("events.store"), requestParams);
    }
};

// Called when the dialog closes
const onClose = () => {
    form.reset();
    show.value = false;
    emit("close");
};

// Watch itemToEdit to fill form on edit
watch(
    () => props.itemToEdit,
    (newItem) => {
        if (newItem) {
            form.title = newItem.title || "";
            form.starts_at = newItem.starts_at
                ? toDisplayFormat(newItem.starts_at)
                : "";
            form.ends_at = newItem.ends_at
                ? toDisplayFormat(newItem.ends_at)
                : "";
            show.value = true;
            editing.value = true;
        }
    }
);
</script>

<template>
    <div>
        <Button @click="onAddNew">
            <vue-feather type="plus" />
            <span class="ml-2">Add new</span>
        </Button>
        <Dialog :show="show" @close="onClose">
            <template #header>
                {{ editing ? "Edit event" : "Add new event" }}
            </template>

            <!-- Affichage des erreurs -->
            <div v-if="form.hasErrors" class="mb-4 text-sm text-red-600">
                <ul>
                    <li v-for="(error, key) in form.errors" :key="key">
                        {{ error }}
                    </li>
                </ul>
            </div>

            <!-- Form inputs -->
            <Input
                name="title"
                label="Title"
                v-model="form.title"
                class="mb-6"
            />

            <Input
                name="starts_at"
                label="Start Date"
                type="datetime-local"
                v-model="form.starts_at"
            />

            <Input
                name="ends_at"
                label="End Date"
                type="datetime-local"
                v-model="form.ends_at"
            />

            <template #footer>
                <Button variant="secondary" class="mr-3" @click="onClose">Cancel</Button>
                <Button @click="onSubmit">Submit</Button>
            </template>
        </Dialog>
    </div>
</template>

<style scoped></style>
