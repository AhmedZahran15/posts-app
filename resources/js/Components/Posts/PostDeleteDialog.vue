<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
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
    post: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(['update:show', 'deleted']);

const isDeleting = ref(false);
const form = useForm({});

const handleConfirmDelete = () => {
    if (!props.post) return;

    isDeleting.value = true;
    form.delete(route('posts.destroy', { post: props.post.id }), {
        headers: { Accept: 'application/json' },
        preserveScroll: true,
        onSuccess: () => {
            emit('deleted');
            isDeleting.value = false;
        },
        onError: () => {
            isDeleting.value = false;
        },
    });
};
</script>

<template>
    <AlertDialog :open="show" @update:open="emit('update:show', $event)">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>Delete Post</AlertDialogTitle>
                <AlertDialogDescription>
                    Are you sure you want to delete this post? This action
                    cannot be undone.
                </AlertDialogDescription>
            </AlertDialogHeader>

            <div class="py-4" v-if="post">
                <p class="font-medium text-gray-700">{{ post.title }}</p>
                <p class="mt-1 text-sm text-gray-500">
                    By {{ post.user?.name }}
                </p>
            </div>

            <AlertDialogFooter>
                <AlertDialogCancel
                    :disabled="isDeleting"
                    @click="emit('update:show', false)"
                >
                    Cancel
                </AlertDialogCancel>
                <AlertDialogAction
                    :disabled="isDeleting"
                    @click="handleConfirmDelete"
                    class="bg-destructive text-destructive-foreground hover:bg-destructive/90"
                >
                    {{ isDeleting ? 'Deleting...' : 'Delete' }}
                </AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
