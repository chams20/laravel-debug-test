<script setup>
import { ref, watch } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import moment from "moment";
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

// Convert 'DD/MM/YYYY HH:mm' → 'YYYY-MM-DD HH:mm'
const toDatabaseFormat = (value) => {
    return moment(value, "DD/MM/YYYY HH:mm").format("YYYY-MM-DD HH:mm");
};


// Convert 'YYYY-MM-DD HH:mm:ss' → 'DD/MM/YYYY HH:mm'
const toDisplayFormat = (value) => {
    return moment(value).format("DD/MM/YYYY HH:mm");
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
            <template #header>{{
                editing ? "Edit event" : "Add new event"
            }}</template>

            <Input
                name="title"
                label="Title"
                v-model="form.title"
                class="mb-6"
            />


            <Input
                name="starts_at"
                label="Start date"
                v-model="form.starts_at"
                placeholder="DD/MM/YYYY HH:mm"
                class="mb-4"
            />

            <Input
                name="ends_at"
                label="End date"
                v-model="form.ends_at"
                placeholder="DD/MM/YYYY HH:mm"
            />

            <template #footer>
                <Button variant="secondary" class="mr-3" @click="onClose"
                    >Cancel</Button
                >
                <Button @click="onSubmit">Submit</Button>
            </template>
        </Dialog>
    </div>
</template>

<style scoped></style>
