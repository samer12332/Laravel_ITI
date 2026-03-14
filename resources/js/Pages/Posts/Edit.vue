<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import PostForm from '@/Pages/Posts/Partials/PostForm.vue';

const props = defineProps({
    post: {
        type: Object,
        required: true,
    },
    creators: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    title: props.post.title,
    description: props.post.description,
    creator: String(props.post.user_id),
    image: null,
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        _method: 'put',
    })).post(route('posts.update', props.post.id), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Edit Post" />

    <PublicLayout>
        <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            <h1 class="text-2xl font-semibold text-slate-900">Edit Post</h1>
            <p class="mt-1 text-sm text-slate-500">Update the post details and image.</p>

            <div class="mt-8">
                <PostForm
                    :form="form"
                    :creators="creators"
                    :image-url="post.image_url"
                    submit-label="Update"
                    @submit="submit"
                />
            </div>
        </div>
    </PublicLayout>
</template>
