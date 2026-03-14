<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineEmits(['submit']);

defineProps({
    form: {
        type: Object,
        required: true,
    },
    creators: {
        type: Array,
        required: true,
    },
    submitLabel: {
        type: String,
        required: true,
    },
    imageUrl: {
        type: String,
        default: null,
    },
});
</script>

<template>
    <form class="space-y-8" @submit.prevent="$emit('submit')">
        <div class="space-y-3">
            <InputLabel for="title" value="Title" />
            <TextInput id="title" v-model="form.title" type="text" class="block w-full" />
            <InputError :message="form.errors.title" />
        </div>

        <div class="space-y-3">
            <InputLabel for="description" value="Description" />
            <textarea
                id="description"
                v-model="form.description"
                rows="5"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            ></textarea>
            <InputError :message="form.errors.description" />
        </div>

        <div class="space-y-3">
            <InputLabel for="creator" value="Post Creator" />
            <select
                id="creator"
                v-model="form.creator"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
                <option
                    v-for="creator in creators"
                    :key="creator.id"
                    :value="String(creator.id)"
                >
                    {{ creator.name }}
                </option>
            </select>
            <InputError :message="form.errors.creator" />
        </div>

        <div class="space-y-3">
            <InputLabel for="image" value="Post Image" />
            <img
                v-if="imageUrl"
                :src="imageUrl"
                alt="Post image preview"
                class="h-48 w-full rounded-lg object-cover"
            >
            <input
                id="image"
                type="file"
                accept=".jpg,.png"
                class="block w-full rounded-md border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700"
                @input="form.image = $event.target.files[0]"
            >
            <InputError :message="form.errors.image" />
        </div>

        <PrimaryButton :disabled="form.processing" :class="{ 'opacity-25': form.processing }">
            {{ submitLabel }}
        </PrimaryButton>
    </form>
</template>
