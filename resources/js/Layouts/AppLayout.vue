<script setup>
// ... existing code ...
import { Toaster } from '@/Components/ui/toast';
</script>

<template>
    <!-- ... existing code ... -->
    <main>
        <slot />
    </main>
    <Toaster />
    <!-- Add this line to make toast notifications appear -->
    <!-- ... existing code ... -->
</template>
