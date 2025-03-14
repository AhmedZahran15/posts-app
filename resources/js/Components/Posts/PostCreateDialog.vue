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

// Use simple refs instead of useForm
const formData = ref({
    title: '',
    discription: '',
});
const errors = ref({});
const isSubmitting = ref(false);

const handleSubmit = async () => {
    isSubmitting.value = true;
    errors.value = {};

    try {
        // Configure axios to work with Laravel's CSRF protection
        // Use the pre-configured Laravel sanctum setup
        axios.defaults.withCredentials = true;

        // Send request with axios
        const response = await axios.post(route('posts.store'), {
            title: formData.value.title,
            description: formData.value.discription,
        });

        // Handle success
        resetForm();
        emit('created', response.data.post);
    } catch (error) {
        // Handle validation errors
        if (error.response && error.response.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            console.error('Error creating post:', error);
        }
    } finally {
        isSubmitting.value = false;
    }
};

const resetForm = () => {
    formData.value.title = '';
    formData.value.discription = '';
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
                        />
                        <p
                            v-if="errors.title"
                            class="mt-1 text-sm text-red-500"
                        >
                            {{ errors.title[0] }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <Label for="discription">Content</Label>
                        <Textarea
                            id="discription"
                            v-model="formData.discription"
                            placeholder="Enter post description"
                            rows="5"
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
