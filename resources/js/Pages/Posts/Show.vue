<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link, useForm, router, usePage } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

const props = defineProps({
    post: {
        type: Object,
        required: true,
    },
    editingCommentId: {
        type: Number,
        default: null,
    },
});

const page = usePage();
const user = computed(() => page.props.auth?.user ?? null);

const commentForm = useForm({
    author_name: '',
    body: '',
});

const editCommentForm = useForm({
    author_name: '',
    body: '',
});

const editingComment = computed(() =>
    props.post.comments.find((comment) => comment.id === props.editingCommentId) ?? null,
);

watch(
    editingComment,
    (comment) => {
        editCommentForm.author_name = comment?.author_name ?? '';
        editCommentForm.body = comment?.body ?? '';
    },
    { immediate: true },
);

const submitComment = () => {
    commentForm.post(route('comments.store', props.post.id), {
        preserveScroll: true,
        onSuccess: () => commentForm.reset(),
    });
};

const updateComment = () => {
    if (!props.editingCommentId) {
        return;
    }

    editCommentForm.put(route('comments.update', [props.post.id, props.editingCommentId]), {
        preserveScroll: true,
    });
};

const deleteComment = (commentId) => {
    if (confirm('Are you sure you want to delete this comment?')) {
        router.delete(route('comments.destroy', [props.post.id, commentId]), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head :title="post.title" />

    <PublicLayout>
        <div class="space-y-12">
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 bg-slate-50 px-6 py-4 text-sm font-semibold uppercase tracking-[0.18em] text-slate-600">
                    Post Info
                </div>

                <div class="flex flex-col gap-6 px-6 py-6 text-slate-800 lg:flex-row lg:items-start lg:gap-10">
                    <div
                        v-if="post.image_url"
                        class="flex shrink-0 items-center justify-center overflow-hidden rounded-xl border border-slate-200 bg-slate-50 p-4 shadow-sm"
                        style="width: 480px; height: 320px;"
                    >
                        <img
                            :src="post.image_url"
                            :alt="post.title"
                            class="h-full w-full object-contain"
                        >
                    </div>

                    <div class="flex-1 space-y-5">
                        <div class="space-y-2">
                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-sky-600">Title</p>
                            <h1 class="text-4xl font-bold leading-tight text-slate-900">
                                {{ post.title }}
                            </h1>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-4">
                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Description</p>
                            <p class="mt-3 text-lg leading-8 text-slate-700">{{ post.description }}</p>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="rounded-xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
                                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Created At</p>
                                <p class="mt-2 text-base font-medium leading-7 text-slate-800">{{ post.created_at }}</p>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
                                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Slug</p>
                                <p class="mt-2 break-all text-base font-medium text-slate-800">{{ post.slug ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 bg-slate-50 px-6 py-4 text-sm font-semibold uppercase tracking-[0.18em] text-slate-600">
                    Post Creator Info
                </div>

                <div class="grid gap-4 px-6 py-6 text-slate-800 md:grid-cols-3">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Name</p>
                        <p class="mt-2 text-lg font-medium text-slate-900">{{ post.user.name }}</p>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Email</p>
                        <p class="mt-2 text-lg font-medium text-slate-900">{{ post.user.email }}</p>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Created At</p>
                        <p class="mt-2 text-lg font-medium text-slate-900">{{ post.user.created_at }}</p>
                    </div>
                </div>
            </section>

            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 bg-slate-50 px-6 py-4 text-sm font-semibold uppercase tracking-[0.18em] text-slate-600">
                    Comments
                </div>

                <div class="space-y-8 px-6 py-6">
                    <form v-if="user" class="space-y-4" @submit.prevent="submitComment">
                        <div class="space-y-2">
                            <InputLabel for="author_name" value="Your Name" />
                            <input
                                id="author_name"
                                v-model="commentForm.author_name"
                                type="text"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                            <InputError :message="commentForm.errors.author_name" />
                        </div>

                        <div class="space-y-2">
                            <InputLabel for="body" value="Comment" />
                            <textarea
                                id="body"
                                v-model="commentForm.body"
                                rows="4"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            ></textarea>
                            <InputError :message="commentForm.errors.body" />
                        </div>

                        <PrimaryButton :disabled="commentForm.processing" :class="{ 'opacity-25': commentForm.processing }">
                            Add Comment
                        </PrimaryButton>
                    </form>

                    <div
                        v-else
                        class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm text-slate-600"
                    >
                        <Link :href="route('login')" class="font-semibold text-slate-900 hover:underline">
                            Log in
                        </Link>
                        to add, edit, or delete comments.
                    </div>

                    <div class="space-y-4">
                        <article
                            v-for="comment in post.comments"
                            :key="comment.id"
                            class="rounded-xl border border-slate-200 bg-slate-50 p-5"
                        >
                            <form
                                v-if="editingCommentId === comment.id"
                                class="space-y-4"
                                @submit.prevent="updateComment"
                            >
                                <div class="space-y-2">
                                    <InputLabel :for="`author_name_${comment.id}`" value="Your Name" />
                                    <input
                                        :id="`author_name_${comment.id}`"
                                        v-model="editCommentForm.author_name"
                                        type="text"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                    <InputError :message="editCommentForm.errors.author_name" />
                                </div>

                                <div class="space-y-2">
                                    <InputLabel :for="`body_${comment.id}`" value="Comment" />
                                    <textarea
                                        :id="`body_${comment.id}`"
                                        v-model="editCommentForm.body"
                                        rows="4"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    ></textarea>
                                    <InputError :message="editCommentForm.errors.body" />
                                </div>

                                <div class="flex flex-wrap gap-3">
                                    <PrimaryButton :disabled="editCommentForm.processing" :class="{ 'opacity-25': editCommentForm.processing }">
                                        Save Comment
                                    </PrimaryButton>
                                    <Link
                                        :href="route('posts.show', post.id)"
                                        class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100"
                                    >
                                        Cancel
                                    </Link>
                                </div>
                            </form>

                            <div v-else class="flex flex-wrap items-start justify-between gap-4 sm:flex-nowrap">
                                <div class="space-y-2">
                                    <p class="text-lg font-semibold text-slate-800">{{ comment.author_name }}</p>
                                    <p class="text-sm text-slate-500">{{ comment.created_at }}</p>
                                    <p class="text-base text-slate-700">{{ comment.body }}</p>
                                </div>

                                <div v-if="user" class="flex shrink-0 flex-wrap items-center gap-2">
                                    <Link
                                        :href="route('posts.show', { id: post.id, editing_comment: comment.id })"
                                        class="rounded-md border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100"
                                    >
                                        Edit
                                    </Link>
                                    <button
                                        type="button"
                                        class="rounded-md border border-rose-200 px-3 py-2 text-sm font-medium text-rose-600 transition hover:bg-rose-50"
                                        @click="deleteComment(comment.id)"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </article>

                        <p v-if="post.comments.length === 0" class="text-base text-slate-500">
                            No comments yet. Be the first to add one.
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </PublicLayout>
</template>
