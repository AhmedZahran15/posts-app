<script setup>
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/Components/ui/table';
import { Pagination } from '@/Components/ui/pagination';
import { router } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
const props = defineProps({
    posts: Object,
});

const emit = defineEmits(['edit', 'delete', 'view']);

const formattedDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const goToPage = (url) => {
    router.visit(url);
};
</script>

<template>
    <div>
        <Table>
            <TableCaption>List of posts</TableCaption>
            <TableHeader>
                <TableRow>
                    <TableHead>ID</TableHead>
                    <TableHead>Title</TableHead>
                    <TableHead>Author</TableHead>
                    <TableHead>Created</TableHead>
                    <TableHead>Actions</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow
                    v-for="post in posts.data"
                    :key="post.id"
                    class="hover:bg-gray-50"
                >
                    <TableCell>{{ post.id }}</TableCell>
                    <TableCell class="font-medium">{{ post.title }}</TableCell>
                    <TableCell>{{ post.user?.name }}</TableCell>
                    <TableCell>{{ formattedDate(post.created_at) }}</TableCell>
                    <TableCell>
                        <div class="flex space-x-2">
                            <Button
                                variant="default"
                                size="sm"
                                @click="emit('view', post)"
                            >
                                View
                            </Button>
                            <Button
                                variant="outline"
                                size="sm"
                                @click="emit('edit', post)"
                            >
                                Edit
                            </Button>
                            <Button
                                variant="destructive"
                                size="sm"
                                @click="emit('delete', post)"
                            >
                                Delete
                            </Button>
                        </div>
                    </TableCell>
                </TableRow>
                <TableRow v-if="posts.data.length === 0">
                    <TableCell colspan="5" class="py-4 text-center"
                        >No posts found</TableCell
                    >
                </TableRow>
            </TableBody>
        </Table>

        <!-- Pagination -->
        <div class="mt-4 flex justify-end">
            <Pagination
                v-if="posts.total > 0"
                :links="posts.links"
                @page-selected="goToPage"
            />
        </div>
    </div>
</template>
