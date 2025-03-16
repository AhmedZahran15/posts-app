<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="flex items-center gap-2 overflow-hidden bg-white p-4 shadow-sm sm:rounded-lg"
                >
                    <div class="text-gray-900">You're logged in!</div>
                    <a
                        href="/posts"
                        class="rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-700"
                    >
                        Go to Posts.</a
                    >
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
