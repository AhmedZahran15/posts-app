<x-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Post details -->
                    <h1 class="text-3xl font-bold mb-2">{{ $post->title }}</h1>
                    <div class="text-gray-500 mb-6">
                        By <span class="font-medium">{{ $post->user->name }}</span> on
                        {{ \Carbon\Carbon::parse($post->user['created_at'])->format('l jS \\of F Y h:i:s A') }}
                    </div>
                    <div class="prose max-w-none">
                        {{ $post->description }}
                    </div>

                    <div class="mt-6 border-t pt-4">
                        <a href="{{ route('posts.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150">
                            Back to Posts
                        </a>
                    </div>
                </div>
            </div>

            <!-- Comments section -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold mb-6">Comments ({{ $post->comments->count() }})</h2>

                    <!-- Add comment form -->
                    <div class="mb-8">
                        <form action="{{ route('posts.comments.store', $post) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Add a
                                    comment</label>
                                <textarea id="content" name="content" rows="3"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    required>{{ old('content') }}</textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Post Comment
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Comments list -->
                    <div class="space-y-6">
                        @forelse ($post->comments()->orderBy('created_at', 'desc')->get() as $comment)
                            <div class="p-4 border rounded-lg bg-gray-50" id="comment-{{ $comment->id }}">
                                <div class="flex justify-between">
                                    <div class="font-medium">{{ $comment->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="mt-2">{{ $comment->content }}</div>

                                <div class="mt-3 flex justify-end space-x-2">
                                    <a href="{{ route('posts.comments.edit', ['post' => $post->id, 'comment' => $comment->id]) }}"
                                        class="inline-block px-3 py-1 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                                        Edit
                                    </a>
                                    <form
                                        action="{{ route('posts.comments.destroy', ['post' => $post->id, 'comment' => $comment->id]) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-block cursor-pointer px-3 py-1 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 italic">No comments yet. Be the first to comment!</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>