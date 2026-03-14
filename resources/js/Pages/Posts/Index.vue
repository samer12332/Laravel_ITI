<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';

const page = usePage();
const user = page.props.auth?.user;

defineProps({
    posts: {
        type: Object,
        required: true,
    },
});

const destroyPost = (postId) => {
    if (confirm('Are you sure you want to delete this post?')) {
        router.delete(route('posts.destroy', postId));
    }
};
</script>

<template>
    <Head title="Posts" />

    <PublicLayout>
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                <div>
                    <h1 class="text-2xl font-semibold text-slate-900">Posts</h1>
                    <p class="mt-1 text-sm text-slate-500">Browse all published posts.</p>
                </div>

                <Link
                    v-if="user"
                    :href="route('posts.create')"
                    class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-700"
                >
                    Create Post
                </Link>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="px-6 py-4 font-semibold">#</th>
                            <th class="px-6 py-4 font-semibold">Title</th>
                            <th class="px-6 py-4 font-semibold">Slug</th>
                            <th class="px-6 py-4 font-semibold">Posted By</th>
                            <th class="px-6 py-4 font-semibold">Created At</th>
                            <th class="px-6 py-4 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <tr v-for="post in posts.data" :key="post.id" class="bg-white">
                            <td class="px-6 py-5">{{ post.id }}</td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <img
                                        v-if="post.image_url"
                                        :src="post.image_url"
                                        alt="Post image"
                                        class="h-12 w-12 rounded-md object-cover"
                                    >
                                    <span class="font-medium text-slate-900">{{ post.title }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-slate-600">{{ post.slug ?? '-' }}</td>
                            <td class="px-6 py-5 text-slate-600">{{ post.user.name }}</td>
                            <td class="px-6 py-5 text-slate-600">{{ post.created_at }}</td>
                            <td class="px-6 py-5">
                                <div class="flex flex-wrap gap-2">
                                    <Link
                                        :href="route('posts.show', post.id)"
                                        class="rounded-md border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                                    >
                                        View
                                    </Link>
                                    <template v-if="user">
                                        <Link
                                            :href="route('posts.edit', post.id)"
                                            class="rounded-md border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                                        >
                                            Edit
                                        </Link>
                                        <button
                                            type="button"
                                            class="rounded-md border border-rose-200 px-3 py-2 text-sm font-medium text-rose-600 transition hover:bg-rose-50"
                                            @click="destroyPost(post.id)"
                                        >
                                            Delete
                                        </button>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-wrap gap-2 border-t border-slate-200 bg-slate-50 px-6 py-4">
                <template v-for="link in posts.links" :key="`${link.label}-${link.url}`">
                    <span
                        v-if="!link.url"
                        class="rounded-md border border-slate-200 px-3 py-2 text-sm text-slate-400"
                        v-html="link.label"
                    />
                    <Link
                        v-else
                        :href="link.url"
                        class="rounded-md border px-3 py-2 text-sm transition"
                        :class="link.active
                            ? 'border-slate-900 bg-slate-900 text-white'
                            : 'border-slate-300 bg-white text-slate-700 hover:bg-slate-100'"
                        v-html="link.label"
                    />
                </template>
            </div>
        </div>
    </PublicLayout>
</template>
