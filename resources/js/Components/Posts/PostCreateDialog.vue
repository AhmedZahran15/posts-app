<script setup>
import { ref } from 'vue';
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
import { Input } from '@/Components/ui/input';
import { Textarea } from '@/Components/ui/textarea';
import { Label } from '@/Components/ui/label';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['update:show', 'created']);

// Fix field name to match backend (description instead of discription)
const formData = ref({
    title: '',
    description: '', // Fixed typo
});
const errors = ref({});
const isSubmitting = ref(false);

const handleSubmit = async () => {
    isSubmitting.value = true;
    errors.value = {};
    try {
        axios.defaults.withCredentials = true;
        const response = await axios.post(route('posts.store'), {
            title: formData.value.title,
            description: formData.value.description,
        });
        resetForm();
        emit('created', response.data.post);
        emit('update:show', false);
    } catch (error) {
        if (error.response && error.response.status === 422) {
            errors.value = error.response.data.errors || {};
        } else {
            console.error('Error creating post:', error);
        }
    } finally {
        isSubmitting.value = false;
    }
};

const resetForm = () => {
    formData.value.title = '';
    formData.value.description = ''; // Fixed field name
    errors.value = {};
};

const handleCancel = () => {
    resetForm();
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
                <div class="space-y-4">
                    <div class="space-y-2">
                        <Label for="title">Title</Label>
                        <Input
                            id="title"
                            v-model="formData.title"
                            placeholder="Enter post title"
                            :class="{ 'border-red-500': errors.title }"
                        />
                        <p
                            v-if="errors.title"
                            class="mt-1 text-sm text-red-500"
                        >
                            {{ errors.title[0] }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <Label for="description">Content</Label>
                        <Textarea
                            id="description"
                            v-model="formData.description"
                            placeholder="Enter post description"
                            rows="5"
                            :class="{ 'border-red-500': errors.description }"
                        />
                        <p
                            v-if="errors.description"
                            class="mt-1 text-sm text-red-500"
                        >
                            {{ errors.description[0] }}
                        </p>
                    </div>
                </div>
            </div>

            <AlertDialogFooter>
                <AlertDialogCancel
                    :disabled="isSubmitting"
                    @click="handleCancel"
                >
                    Cancel
                </AlertDialogCancel>

                <!-- Replace AlertDialogAction with a regular button -->
                <button
                    type="button"
                    class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground ring-offset-background transition-colors hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"
                    :disabled="isSubmitting"
                    @click.prevent="handleSubmit"
                >
                    {{ isSubmitting ? 'Creating...' : 'Create Post' }}
                </button>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
