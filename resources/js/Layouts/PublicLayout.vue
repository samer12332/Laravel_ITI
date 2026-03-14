<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const user = computed(() => page.props.auth?.user);
const flashSuccess = computed(() => page.props.flash?.success);
</script>

<template>
    <div class="min-h-screen bg-slate-100 text-slate-800">
        <nav class="border-b border-slate-200 bg-white shadow-sm">
            <div
                class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8"
            >
                <div class="flex items-center gap-6">
                    <Link
                        :href="route('posts.index')"
                        class="text-2xl font-semibold tracking-tight text-slate-900"
                    >
                        ITI Blog
                    </Link>

                    <div class="flex items-center gap-5">
                        <Link
                            :href="route('posts.index')"
                            class="text-sm font-medium text-slate-600 transition hover:text-slate-900"
                        >
                            All Posts
                        </Link>
                        <Link
                            v-if="user"
                            :href="route('posts.create')"
                            class="text-sm font-medium text-slate-600 transition hover:text-slate-900"
                        >
                            Create Post
                        </Link>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <template v-if="user">
                        <Link
                            :href="route('profile.edit')"
                            class="rounded-md px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
                        >
                            Profile
                        </Link>
                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-700"
                        >
                            Logout
                        </Link>
                    </template>

                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="rounded-md px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
                        >
                            Login
                        </Link>
                        <Link
                            :href="route('register')"
                            class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-700"
                        >
                            Register
                        </Link>
                    </template>
                </div>
            </div>
        </nav>

        <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div
                v-if="flashSuccess"
                class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700"
            >
                {{ flashSuccess }}
            </div>

            <slot />
        </main>
    </div>
</template>
