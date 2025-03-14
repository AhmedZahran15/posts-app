<script setup>
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/Components/ui/alert-dialog';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    post: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(['update:show']);

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<template>
    <AlertDialog :open="show" @update:open="emit('update:show', $event)">
        <AlertDialogContent class="max-h-[80vh] max-w-2xl overflow-auto">
            <AlertDialogHeader>
                <AlertDialogTitle class="text-xl">{{
                    post?.title
                }}</AlertDialogTitle>
                <AlertDialogDescription v-if="post?.user">
                    By {{ post.user.name }} • {{ formatDate(post.created_at) }}
                </AlertDialogDescription>
            </AlertDialogHeader>

            <div class="py-4" v-if="post">
                <div class="prose max-w-none">
                    <div class="whitespace-pre-line">{{ post.content }}</div>
                </div>

                <div
                    class="mt-6"
                    v-if="post.comments && post.comments.length > 0"
                >
                    <h3 class="mb-4 text-lg font-medium text-gray-900">
                        Comments
                    </h3>
                    <div class="space-y-4">
                        <div
                            v-for="comment in post.comments"
                            :key="comment.id"
                            class="rounded-md bg-gray-50 p-4"
                        >
                            <div class="flex items-center justify-between">
                                <div class="font-medium">
                                    {{ comment.user?.name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ formatDate(comment.created_at) }}
                                </div>
                            </div>
                            <div class="mt-2 text-gray-700">
                                {{ comment.content }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <AlertDialogFooter>
                <AlertDialogCancel @click="emit('update:show', false)">
                    Close
                </AlertDialogCancel>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
