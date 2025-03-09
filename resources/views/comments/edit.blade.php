<x-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8 bg-white shadow-md rounded-lg">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Edit Comment</h2>

        <form method="POST"
            action="{{ route('posts.comments.update', ['post' => $post->id, 'comment' => $comment->id]) }}"
            class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                <textarea name="content" id="content" rows="3"
                    class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    required>{{ old('content', $comment->content) }}</textarea>
                @error('content')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center justify-between">
                <a href="{{ route('posts.show', $post) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:shadow-outline-gray disabled:opacity-25 transition">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center cursor-pointer px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue disabled:opacity-25 transition">
                    Update Comment
                </button>
            </div>
        </form>
    </div>
</x-layout>