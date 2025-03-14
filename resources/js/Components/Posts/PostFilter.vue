<script setup>
import { ref, watch } from 'vue';
import { Input } from '@/Components/ui/input';
import { Search } from 'lucide-vue-next';

const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['update:modelValue']);

const searchInput = ref(props.modelValue);

watch(searchInput, (newVal) => {
    emit('update:modelValue', newVal);
});
</script>

<template>
    <div class="mb-6">
        <div class="relative">
            <Search
                class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-500"
            />
            <Input
                v-model="searchInput"
                class="pl-10"
                placeholder="Search posts..."
            />
        </div>
    </div>
</template>
