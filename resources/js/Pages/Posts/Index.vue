<script setup>
import { ref, onMounted, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useToast } from '@/Components/ui/toast/use-toast';
import PostList from '@/Components/Posts/PostList.vue';
import PostCreateDialog from '@/Components/Posts/PostCreateDialog.vue';
import PostEditDialog from '@/Components/Posts/PostEditDialog.vue';
import PostDeleteDialog from '@/Components/Posts/PostDeleteDialog.vue';
import PostViewDialog from '@/Components/Posts/PostViewDialog.vue';

// Import pagination components
import {
    Pagination,
    PaginationList,
    PaginationListItem,
} from '@/Components/ui/pagination';
import {
    PaginationEllipsis,
    PaginationFirst,
    PaginationLast,
    PaginationNext,
    PaginationPrev,
} from '@/Components/ui/pagination';

const props = defineProps({
    posts: Object,
    flash: Object,
});

const { toast } = useToast();
const showCreateDialog = ref(false);
const showEditDialog = ref(false);
const showDeleteDialog = ref(false);
const showViewDialog = ref(false);
const selectedPost = ref(null);

const selectedPostId = ref(null);

onMounted(() => {
    if (props.flash?.message) {
        toast({
            title: 'Notification',
            description: props.flash.message,
        });
    }
});

const openCreateDialog = () => {
    showCreateDialog.value = true;
};

const openEditDialog = (post) => {
    selectedPost.value = post;
    showEditDialog.value = true;
};

const openDeleteDialog = (post) => {
    selectedPost.value = post;
    showDeleteDialog.value = true;
};

const openViewDialog = (post) => {
    selectedPostId.value = post.id;
    showViewDialog.value = true;
};

const handlePostCreated = (newPost) => {
    showCreateDialog.value = false;

    // Add the new post to the posts list if it's returned from the API
    if (newPost && props.posts.data) {
        // Add to the beginning of the list
        props.posts.data.unshift(newPost);

        // Remove the last item if we're at the pagination limit
        if (
            props.posts.per_page &&
            props.posts.data.length > props.posts.per_page
        ) {
            props.posts.data.pop();
        }
    }

    toast({
        title: 'Success',
        description: 'Post created successfully',
    });
};

const handlePostUpdated = (updatedPost) => {
    showEditDialog.value = false;

    // Update the post in the list if it's returned from the API
    if (updatedPost && props.posts.data) {
        const index = props.posts.data.findIndex(
            (post) => post.id === updatedPost.id,
        );
        if (index !== -1) {
            props.posts.data[index] = updatedPost;
        }
    }

    toast({
        title: 'Success',
        description: 'Post updated successfully',
    });
};

const handlePostDeleted = () => {
    showDeleteDialog.value = false;
    toast({
        title: 'Success',
        description: 'Post moved to trash',
    });
};

// Create computed properties for pagination
const currentPage = computed(() => props.posts.current_page);
const lastPage = computed(() => props.posts.last_page);

// Generate pagination links array for rendering
const paginationLinks = computed(() => {
    const links = [];

    if (lastPage.value <= 7) {
        // Show all pages if total pages are 7 or fewer
        for (let i = 1; i <= lastPage.value; i++) {
            links.push(i);
        }
    } else {
        // Always include first page
        links.push(1);

        // Calculate which page numbers to show
        if (currentPage.value <= 3) {
            // Near the start
            links.push(2, 3, 4, 5, '...', lastPage.value);
        } else if (currentPage.value >= lastPage.value - 2) {
            // Near the end
            links.push(
                '...',
                lastPage.value - 4,
                lastPage.value - 3,
                lastPage.value - 2,
                lastPage.value - 1,
                lastPage.value,
            );
        } else {
            // Somewhere in the middle
            links.push(
                '...',
                currentPage.value - 1,
                currentPage.value,
                currentPage.value + 1,
                '...',
                lastPage.value,
            );
        }
    }

    return links;
});
</script>

<template>
    <Head title="Posts" />

    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Posts
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-white p-6">
                        <div class="mb-6 flex items-center justify-between">
                            <h2 class="text-2xl font-semibold text-gray-800">
                                Posts
                            </h2>
                            <button
                                class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                @click="openCreateDialog"
                            >
                                Create Post
                            </button>
                        </div>

                        <PostList
                            :posts="posts"
                            @view="openViewDialog"
                            @edit="openEditDialog"
                            @delete="openDeleteDialog"
                        />

                        <!-- Pagination with improved horizontal layout -->
                        <div class="mt-8 border-t border-gray-200 pt-4">
                            <!-- Add page counter above pagination -->
                            <div class="mb-2 text-center text-sm text-gray-600">
                                Page {{ currentPage }} of {{ lastPage }}
                            </div>
                            <div class="flex justify-center">
                                <Pagination
                                    class="flex flex-row items-center space-x-2"
                                >
                                    <!-- Fix First Page button -->
                                    <Link
                                        :href="`/posts?page=1`"
                                        :class="[
                                            'inline-flex h-9 w-9 items-center justify-center rounded-md',
                                            currentPage === 1
                                                ? 'cursor-not-allowed opacity-50'
                                                : 'hover:bg-muted',
                                        ]"
                                        :disabled="currentPage === 1"
                                        preserve-scroll
                                    >
                                        <PaginationFirst />
                                    </Link>

                                    <!-- Fix Previous Page button -->
                                    <Link
                                        :href="
                                            currentPage > 1
                                                ? `/posts?page=${currentPage - 1}`
                                                : '#'
                                        "
                                        :class="[
                                            'inline-flex h-9 w-9 items-center justify-center rounded-md',
                                            currentPage === 1
                                                ? 'cursor-not-allowed opacity-50'
                                                : 'hover:bg-muted',
                                        ]"
                                        :disabled="currentPage === 1"
                                        preserve-scroll
                                    >
                                        <PaginationPrev />
                                    </Link>

                                    <PaginationList class="flex items-center">
                                        <template
                                            v-for="(
                                                page, index
                                            ) in paginationLinks"
                                            :key="index"
                                        >
                                            <PaginationListItem
                                                v-if="page === '...'"
                                                class="mx-1"
                                            >
                                                <PaginationEllipsis
                                                    class="h-9 w-9"
                                                />
                                            </PaginationListItem>
                                            <PaginationListItem
                                                v-else
                                                :value="page"
                                                class="mx-1"
                                            >
                                                <Link
                                                    :href="`/posts?page=${page}`"
                                                    :class="[
                                                        'inline-flex h-9 w-9 items-center justify-center rounded-md text-sm transition-colors',
                                                        page === currentPage
                                                            ? 'bg-primary text-primary-foreground hover:bg-primary/90'
                                                            : 'hover:bg-muted',
                                                    ]"
                                                    preserve-scroll
                                                >
                                                    {{ page }}
                                                </Link>
                                            </PaginationListItem>
                                        </template>
                                    </PaginationList>

                                    <!-- Fix Next Page button -->
                                    <Link
                                        :href="
                                            currentPage < lastPage
                                                ? `/posts?page=${currentPage + 1}`
                                                : '#'
                                        "
                                        :class="[
                                            'inline-flex h-9 w-9 items-center justify-center rounded-md',
                                            currentPage === lastPage
                                                ? 'cursor-not-allowed opacity-50'
                                                : 'hover:bg-muted',
                                        ]"
                                        :disabled="currentPage === lastPage"
                                        preserve-scroll
                                    >
                                        <PaginationNext />
                                    </Link>

                                    <!-- Fix Last Page button -->
                                    <Link
                                        :href="`/posts?page=${lastPage}`"
                                        :class="[
                                            'inline-flex h-9 w-9 items-center justify-center rounded-md',
                                            currentPage === lastPage
                                                ? 'cursor-not-allowed opacity-50'
                                                : 'hover:bg-muted',
                                        ]"
                                        :disabled="currentPage === lastPage"
                                        preserve-scroll
                                    >
                                        <PaginationLast />
                                    </Link>
                                </Pagination>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals -->
        <PostCreateDialog
            :show="showCreateDialog"
            @update:show="showCreateDialog = $event"
            @created="handlePostCreated"
        />

        <PostEditDialog
            :show="showEditDialog"
            :post="selectedPost"
            @update:show="showEditDialog = $event"
            @updated="handlePostUpdated"
        />

        <PostDeleteDialog
            :show="showDeleteDialog"
            :post="selectedPost"
            @update:show="showDeleteDialog = $event"
            @deleted="handlePostDeleted"
        />

        <PostViewDialog
            :show="showViewDialog"
            :postId="selectedPostId"
            @update:show="showViewDialog = $event"
        />
    </AppLayout>
</template>
