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
import { Input } from '@/Components/ui/input';
import { Textarea } from '@/Components/ui/textarea';
import { Label } from '@/Components/ui/label';

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

const formData = ref({
    title: '',
    description: '',
    image: null,
});

const currentImageUrl = ref(null);
const imagePreview = ref(null);
const errors = ref({});
const isSubmitting = ref(false);

watch(
    () => props.post,
    (newPost) => {
        if (newPost) {
            formData.value.title = newPost.title;
            formData.value.description = newPost.description;
            currentImageUrl.value = newPost.image_url;
            imagePreview.value = null;
        }
    },
    { immediate: true },
);

const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        formData.value.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const handleSubmit = async () => {
    if (!props.post) return;

    isSubmitting.value = true;
    errors.value = {};

    try {
        const form = new FormData();
        form.append('title', formData.value.title);
        form.append('description', formData.value.description);
        form.append('_method', 'PUT'); // For method spoofing

        if (formData.value.image) {
            form.append('image', formData.value.image);
        }

        const response = await axios.post(
            route('posts.update', { post: props.post.id }),
            form,
            {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            },
        );

        emit('updated', response.data.post);
        emit('update:show', false);
    } catch (error) {
        if (error.response && error.response.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            console.error('Error updating post:', error);
        }
    } finally {
        isSubmitting.value = false;
    }
};

const handleCancel = () => {
    errors.value = {};
    formData.value.image = null;
    imagePreview.value = null;
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
                <div class="space-y-4">
                    <div class="space-y-2">
                        <Label for="edit-title">Title</Label>
                        <Input
                            id="edit-title"
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
                        <Label for="edit-description">Content</Label>
                        <Textarea
                            id="edit-description"
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

                    <!-- Image Upload Field -->
                    <div class="space-y-2">
                        <Label for="edit-image"
                            >Image (JPG, JPEG, PNG only)</Label
                        >
                        <Input
                            type="file"
                            id="edit-image"
                            @change="handleImageChange"
                            accept=".jpg,.jpeg,.png"
                            :class="{ 'border-red-500': errors.image }"
                        />
                        <p
                            v-if="errors.image"
                            class="mt-1 text-sm text-red-500"
                        >
                            {{ errors.image[0] }}
                        </p>

                        <!-- Current Image Preview -->
                        <div
                            v-if="currentImageUrl && !imagePreview"
                            class="mt-2"
                        >
                            <p class="mb-1 text-sm text-gray-500">
                                Current image:
                            </p>
                            <img
                                :src="currentImageUrl"
                                alt="Current image"
                                class="h-40 w-auto rounded border border-gray-200 object-contain"
                            />
                        </div>

                        <!-- New Image Preview -->
                        <div v-if="imagePreview" class="mt-2">
                            <p class="mb-1 text-sm text-gray-500">New image:</p>
                            <img
                                :src="imagePreview"
                                alt="Preview"
                                class="h-40 w-auto rounded border border-gray-200 object-contain"
                            />
                        </div>
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
                <button
                    type="button"
                    class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground ring-offset-background transition-colors hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"
                    :disabled="isSubmitting"
                    @click.prevent="handleSubmit"
                >
                    {{ isSubmitting ? 'Saving...' : 'Save Changes' }}
                </button>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
