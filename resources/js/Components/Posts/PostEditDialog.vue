<script setup>
import { ref, watch } from 'vue';
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
import PostFormFields from './PostFormFields.vue';

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

const emit = defineEmits(['update:show', 'updated']);

const form = useForm({
    title: '',
    content: '',
});

const isSubmitting = ref(false);

watch(
    () => props.post,
    (newPost) => {
        if (newPost) {
            form.title = newPost.title;
            form.content = newPost.content;
        }
    },
    { immediate: true },
);

const handleSubmit = () => {
    if (!props.post) return;

    isSubmitting.value = true;
    form.put(route('posts.update', { post: props.post.id }), {
        headers: { Accept: 'application/json' },
        preserveScroll: true,
        onSuccess: () => {
            emit('updated');
            isSubmitting.value = false;
        },
        onError: () => {
            isSubmitting.value = false;
        },
    });
};

const handleCancel = () => {
    form.reset();
    emit('update:show', false);
};
</script>

<template>
    <AlertDialog :open="show" @update:open="emit('update:show', $event)">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>Edit Post</AlertDialogTitle>
                <AlertDialogDescription>
                    Make changes to the post and save when done.
                </AlertDialogDescription>
            </AlertDialogHeader>

            <div class="py-4">
                <PostFormFields
                    v-model:title="form.title"
                    v-model:content="form.content"
                    :errors="form.errors"
                />
            </div>

            <AlertDialogFooter>
                <AlertDialogCancel
                    :disabled="isSubmitting"
                    @click="handleCancel"
                >
                    Cancel
                </AlertDialogCancel>
                <AlertDialogAction
                    :disabled="isSubmitting"
                    @click="handleSubmit"
                >
                    {{ isSubmitting ? 'Saving...' : 'Save Changes' }}
                </AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
