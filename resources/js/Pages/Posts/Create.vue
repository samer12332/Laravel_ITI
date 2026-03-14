<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import PostForm from '@/Pages/Posts/Partials/PostForm.vue';

const props = defineProps({
    creators: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    title: '',
    description: '',
    creator: props.creators[0] ? String(props.creators[0].id) : '',
    image: null,
});

const submit = () => {
    form.post(route('posts.store'), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Create Post" />

    <PublicLayout>
        <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            <h1 class="text-2xl font-semibold text-slate-900">Create Post</h1>
            <p class="mt-1 text-sm text-slate-500">Add a new post using the Inertia form.</p>

            <div class="mt-8">
                <PostForm
                    :form="form"
                    :creators="creators"
                    submit-label="Create"
                    @submit="submit"
                />
            </div>
        </div>
    </PublicLayout>
</template>
