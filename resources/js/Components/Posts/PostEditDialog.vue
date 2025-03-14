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

// Use simple refs instead of useForm
const formData = ref({
    title: '',
    discription: '',
});
const errors = ref({});
const isSubmitting = ref(false);

watch(
    () => props.post,
    (newPost) => {
        if (newPost) {
            formData.value.title = newPost.title;
            formData.value.discription = newPost.description;
        }
    },
    { immediate: true },
);

const handleSubmit = async () => {
    if (!props.post) return;

    isSubmitting.value = true;
    errors.value = {};

    try {
        // Send request with axios
        const response = await axios.put(
            route('posts.update', { post: props.post.id }),
            {
                title: formData.value.title,
                description: formData.value.discription,
            },
        );

        // Handle success
        emit('updated', response.data.post);
    } catch (error) {
        // Handle validation errors
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
                        />
                        <p
                            v-if="errors.title"
                            class="mt-1 text-sm text-red-500"
                        >
                            {{ errors.title[0] }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <Label for="edit-discription">Content</Label>
                        <Textarea
                            id="edit-discription"
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
                    {{ isSubmitting ? 'Saving...' : 'Save Changes' }}
                </AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
