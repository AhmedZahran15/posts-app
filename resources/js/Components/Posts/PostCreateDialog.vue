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
import { Input } from '@/Components/ui/input';
import { Textarea } from '@/Components/ui/textarea';
import { Label } from '@/Components/ui/label';
import PostFormFields from './PostFormFields.vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['update:show', 'created']);

const form = useForm({
    title: '',
    content: '',
});

const isSubmitting = ref(false);

const handleSubmit = () => {
    isSubmitting.value = true;
    form.post(route('posts.store'), {
        headers: { Accept: 'application/json' },
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            emit('created');
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
                <AlertDialogTitle>Create New Post</AlertDialogTitle>
                <AlertDialogDescription>
                    Fill out the form below to create a new post.
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
                    {{ isSubmitting ? 'Creating...' : 'Create Post' }}
                </AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
