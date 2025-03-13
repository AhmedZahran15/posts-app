<x-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Status Messages -->
            @if(session('error'))
                <x-alert type="error" :message="session('error')" />
            @endif

            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif

            <!-- Post details card -->
            <x-card>
                <!-- Post header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold mb-3 text-gray-800">{{ $post->title }}</h1>
                    <div class="flex items-center text-gray-500 mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="font-medium text-indigo-600">{{ $post->user->name }}</span>
                        <span class="mx-2">•</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ \Carbon\Carbon::parse($post->created_at)->format('M d, Y') }}</span>
                    </div>
                </div>

                <!-- Post content -->
                <div class="prose max-w-none text-gray-700 leading-relaxed">
                    {{ $post->description }}
                </div>

                <!-- Post footer -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <x-button :href="route('posts.index')" type="primary"
                           icon='<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                              </svg>'>
                            Back to Posts
                        </x-button>

                        @if(Auth::id() == $post->user_id)
                            <div class="flex space-x-3">
                                <x-button :href="route('posts.edit', $post->id)" type="primary"
                                   icon='<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>'>
                                    Edit Post
                                </x-button>
                            </div>
                        @endif
                    </div>
                </div>
            </x-card>

            <!-- Comments section -->
            <div class="mt-8">
                <x-card>
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-bold text-gray-800">Comments</h2>
                        <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-xs font-medium rounded-full">
                            {{ $post->comments->count() }}
                        </span>
                    </div>

                    <!-- Add comment form -->
                    <div class="mb-10 bg-gray-50 rounded-lg p-6 border border-gray-100">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Add a comment</h3>
                        <form action="{{ route('posts.comments.store', $post) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <textarea id="content" name="content" rows="3"
                                    class="block w-full px-4 py-3 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150 ease-in-out"
                                    placeholder="Write your comment here..."
                                    required>{{ old('content') }}</textarea>
                            </div>
                            <div class="flex justify-end">
                                <x-button type="primary"
                                   icon='<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>'>
                                    Post Comment
                                </x-button>
                            </div>
                        </form>
                    </div>

                    <!-- Comments list -->
                    <div class="space-y-6">
                        @forelse ($post->comments()->orderBy('created_at', 'desc')->get() as $comment)
                            <div class="p-6 rounded-lg bg-white border border-gray-200 shadow-sm hover:shadow-md transition-all" id="comment-{{ $comment->id }}">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold text-lg">
                                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="font-medium text-gray-800">{{ $comment->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>

                                    @if(Auth::id() == $comment->user_id)
                                        <div class="flex space-x-2">
                                            <x-button
                                                :href="route('posts.comments.edit', ['post' => $post->id, 'comment' => $comment->id])"
                                                type="info"
                                                size="sm"
                                                icon='<svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>'
                                            >
                                                Edit
                                            </x-button>

                                            <x-button
                                                method="DELETE"
                                                :href="route('posts.comments.destroy', ['post' => $post->id, 'comment' => $comment->id])"
                                                type="danger"
                                                size="sm"
                                                confirm="Are you sure you want to delete this comment?"
                                                icon='<svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>'
                                            >
                                                Delete
                                            </x-button>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-2 text-gray-700">{{ $comment->content }}</div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center text-center py-12">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                                <p class="text-gray-500 italic">No comments yet. Be the first to comment!</p>
                            </div>
                        @endforelse
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</x-layout>
