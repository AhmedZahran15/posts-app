<script setup>
import { Input } from '@/Components/ui/input';
import { Textarea } from '@/Components/ui/textarea';
import { Label } from '@/Components/ui/label';

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    discription: {
        type: String,
        required: true,
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
});

// Fix emits to match the actual events being used
const emit = defineEmits(['update:title', 'update:discription']);
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
            <Label for="discription">Content</Label>
            <Textarea
                id="discription"
                :value="discription"
                @input="emit('update:discription', $event.target.value)"
                placeholder="Enter post description"
                rows="5"
            />
            <p
                v-if="errors.discription || errors.description"
                class="mt-1 text-sm text-red-500"
            >
                {{ errors.discription || errors.description }}
            </p>
        </div>
    </div>
</template>
