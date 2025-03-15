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

                <!-- Post content -->
                <div v-else-if="post" class="space-y-4">
                    <!-- Post image if available -->
                    <div v-if="post.post.image_url" class="mb-4">
                        <img
                            :src="post.post.image_url"
                            :alt="post.post.title"
                            class="mx-auto max-h-80 rounded-lg object-contain"
                        />
                    </div>

                    <!-- Post description -->
                    <p class="whitespace-pre-line text-gray-700">
                        {{ post.post.description }}
                    </p>

                    <!-- Comments section -->
                    <div
                        v-if="post.comments && post.comments.length"
                        class="mt-8"
                    >
                        <h4 class="mb-3 text-lg font-medium">Comments</h4>
                        <div class="space-y-4">
                            <div
                                v-for="comment in post.comments"
                                :key="comment.id"
                                class="rounded-md border border-gray-200 p-3"
                            >
                                <p class="text-sm text-gray-700">
                                    {{ comment.content }}
                                </p>
                                <div
                                    class="mt-2 flex items-center text-xs text-gray-500"
                                >
                                    <span>{{ comment.user.name }}</span>
                                    <span class="mx-1">•</span>
                                    <span>{{
                                        formatDate(comment.created_at)
                                    }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        v-else-if="post.comments"
                        class="mt-6 text-center text-gray-500"
                    >
                        No comments yet.
                    </div>
                </div>
            </div>

            <AlertDialogFooter>
                <AlertDialogAction @click="emit('update:show', false)">
                    Close
                </AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
