<script setup>
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useToast } from '@/Components/ui/toast/use-toast';
import PostList from '@/Components/Posts/PostList.vue';
import PostFilter from '@/Components/Posts/PostFilter.vue';
import PostCreateDialog from '@/Components/Posts/PostCreateDialog.vue';
import PostEditDialog from '@/Components/Posts/PostEditDialog.vue';
import PostDeleteDialog from '@/Components/Posts/PostDeleteDialog.vue';
import PostViewDialog from '@/Components/Posts/PostViewDialog.vue';

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
const filterKeyword = ref('');

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

const handlePostCreated = () => {
    showCreateDialog.value = false;
    toast({
        title: 'Success',
        description: 'Post created successfully',
    });
};

const handlePostUpdated = () => {
    showEditDialog.value = false;
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
</script>

<template>
    <AppLayout title="Posts">
        <Head title="Posts" />

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

                        <PostFilter v-model="filterKeyword" />

                        <PostList
                            :posts="posts"
                            :filter="filterKeyword"
                            @view="openViewDialog"
                            @edit="openEditDialog"
                            @delete="openDeleteDialog"
                        />
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
