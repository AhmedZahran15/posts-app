<script setup>
import { cn } from '@/lib/utils';
import { computed } from 'vue';

const props = defineProps({
    for: {
        type: String,
        default: undefined,
    },
    class: {
        type: String,
        default: '',
    },
});

const classes = computed(() => {
    return cn(
        'text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70',
        props.class,
    );
});
</script>

<template>
    <label :for="props.for" :class="classes">
        <slot />
    </label>
</template>
