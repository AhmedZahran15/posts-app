<script setup>
import { Input } from '@/Components/ui/input';
import { Textarea } from '@/Components/ui/textarea';
import { Label } from '@/Components/ui/label';

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    content: {
        type: String,
        required: true,
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
});

const emit = defineEmits(['update:title', 'update:content']);
</script>

<template>
    <div class="space-y-4">
        <div class="space-y-2">
            <Label for="title">Title</Label>
            <Input
                id="title"
                :value="title"
                @input="emit('update:title', $event.target.value)"
                placeholder="Enter post title"
            />
            <p v-if="errors.title" class="mt-1 text-sm text-red-500">
                {{ errors.title }}
            </p>
        </div>

        <div class="space-y-2">
            <Label for="content">Content</Label>
            <Textarea
                id="content"
                :value="content"
                @input="emit('update:content', $event.target.value)"
                placeholder="Enter post content"
                rows="5"
            />
            <p v-if="errors.content" class="mt-1 text-sm text-red-500">
                {{ errors.content }}
            </p>
        </div>
    </div>
</template>
