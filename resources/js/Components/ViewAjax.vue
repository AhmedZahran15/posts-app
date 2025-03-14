<template>
    <div>
        <button
            @click="fetchPostData"
            class="inline-flex items-center rounded-md bg-blue-500 px-4 py-2 text-sm font-medium text-white transition duration-150 ease-in-out hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="mr-2 h-4 w-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                />
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                />
            </svg>
            View Details
        </button>

        <!-- Modal Overlay -->
        <div
            v-if="showModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
            @click.self="closeModal"
        >
            <!-- Modal Content -->
            <div
                class="mx-4 w-full max-w-md transform overflow-hidden rounded-lg bg-white shadow-xl transition-all"
            >
                <!-- Modal Header -->
                <div
                    class="flex items-center justify-between bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4"
                >
                    <h3 class="text-xl font-bold text-white">Post Details</h3>
                    <button
                        @click="closeModal"
                        class="text-white hover:text-gray-200 focus:outline-none"
                    >
                        <svg
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <div v-if="loading" class="flex justify-center">
                        <svg
                            class="h-8 w-8 animate-spin text-indigo-500"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            ></path>
                        </svg>
                    </div>

                    <div v-else-if="error" class="text-center text-red-500">
                        {{ error }}
                    </div>

                    <div v-else-if="post">
                        <div class="mb-4">
                            <h2 class="mb-2 text-2xl font-bold text-gray-800">
                                {{ post.title }}
                            </h2>
                            <div class="mb-4 flex items-center text-gray-600">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="mr-2 h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                    />
                                </svg>
                                <span>{{ post.user_name }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ post.user_email }}</span>
                            </div>
                        </div>

                        <div class="prose max-w-none text-gray-700">
                            <p>{{ post.description }}</p>
                        </div>

                        <div
                            class="mt-6 flex justify-end border-t border-gray-200 pt-4"
                        >
                            <button
                                @click="closeModal"
                                class="inline-flex items-center rounded-md border border-transparent bg-gray-200 px-4 py-2 font-semibold text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                            >
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        id: {
            type: [Number, String],
            required: true,
        },
    },
    data() {
        return {
            post: null,
            showModal: false,
            loading: false,
            error: null,
        };
    },
    methods: {
        fetchPostData() {
            this.showModal = true;
            this.loading = true;
            this.error = null;

            const baseUrl = window.location.origin;
            const url = `${baseUrl}/postdata/${this.id}`;
            axios
                .get(url)
                .then((response) => {
                    this.post = response.data;
                    this.loading = false;
                })
                .catch((error) => {
                    console.error('Error fetching post data:', error);
                    this.error = 'Failed to load post data.';
                    this.loading = false;
                });
        },
        closeModal() {
            this.showModal = false;
        },
    },
};
</script>
