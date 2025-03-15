<script setup>
import { ref, watch } from 'vue';
import axios from 'axios';
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

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    postId: {
        type: [Number, String],
        default: null,
    },
});

const emit = defineEmits(['update:show']);

// State management
const post = ref(null);
const loading = ref(false);
const error = ref(null);

// Fetch post data when dialog is shown
watch(
    () => props.show,
    async (isVisible) => {
        if (isVisible && props.postId) {
            await fetchPostData();
        } else {
            // Reset state when dialog closes
            post.value = null;
            error.value = null;
        }
    },
);

// Also watch postId changes to refetch data if needed
watch(
    () => props.postId,
    async (newPostId) => {
        if (props.show && newPostId) {
            await fetchPostData();
        }
    },
);

const fetchPostData = async () => {
    loading.value = true;
    error.value = null;

    try {
        // Try both URL formats
        const url = `/posts/${props.postId}`;
        const response = await axios.get(url);
        post.value = response.data;
    } catch (err) {
        console.error('Error fetching post:', err);
        error.value = 'Failed to load post data. Please try again.';
    } finally {
        loading.value = false;
    }
};

const formatDate = (date) => {
    if (!date) return '';

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
                <AlertDialogTitle class="text-xl">
                    <span v-if="loading">Loading...</span>
                    <span v-else-if="error">Error</span>
                    <span v-else>{{ post?.post.title }}</span>
                </AlertDialogTitle>
                <AlertDialogDescription v-if="post?.user">
                    By {{ post.user.name }} •
                    {{ formatDate(post.post.created_at) }}
                </AlertDialogDescription>
            </AlertDialogHeader>

            <div class="py-4">
                <!-- Loading state -->
                <div v-if="loading" class="flex justify-center py-8">
                    <div
                        class="h-8 w-8 animate-spin rounded-full border-4 border-primary border-t-transparent"
                    ></div>
                </div>

                <!-- Error state -->
                <div v-else-if="error" class="py-4 text-center text-red-500">
                    {{ error }}
                    <button
                        @click="fetchPostData"
                        class="mt-2 text-sm text-blue-500 hover:underline"
                    >
                        Try again
                    </button>
                </div>

                <!-- Content when loaded -->
                <div v-else-if="post">
                    <!-- Post description section -->
                    <div class="prose max-w-none">
                        <div class="mb-4">
                            <h3 class="text-md font-medium text-gray-700">
                                Description:
                            </h3>
                            <div class="mt-2 whitespace-pre-line text-gray-600">
                                {{ post.post.description }}
                            </div>
                        </div>
                    </div>

                    <!-- Comments section -->
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

                    <div
                        v-else-if="post.comments && post.comments.length === 0"
                        class="mt-6 text-center text-gray-500"
                    >
                        No comments yet
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
