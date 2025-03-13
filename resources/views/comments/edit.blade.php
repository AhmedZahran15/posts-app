<x-layout>
    <div class="py-8">
        <div class="max-w-2xl mx-auto">
            <x-card title="Edit Comment" gradient="true">
                @if(session('error'))
                    <x-alert type="error" :message="session('error')" />
                @endif

                <form method="POST"
                    action="{{ route('posts.comments.update', ['post' => $post->id, 'comment' => $comment->id]) }}"
                    class="space-y-6">
                    @csrf
                    @method('PUT')

                    <x-form.textarea label="Comment" name="content" :value="old('content', $comment->content)" :rows="4"
                        required autofocus />

                    <div class="flex items-center justify-between">
                        <x-button :href="route('posts.show', $post)" type="secondary" icon='<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>'>
                            Cancel
                        </x-button>

                        <x-button type="primary" icon='<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>'>
                            Update Comment
                        </x-button>
                    </div>
                </form>
            </x-card>

            <!-- Post preview -->
            <div class="mt-6">
                <x-card>
                    <h3 class="text-md font-medium text-gray-800 mb-2">Commenting on:</h3>
                    <div class="border-l-4 border-indigo-500 pl-4 py-2">
                        <h4 class="font-medium text-lg text-gray-800">{{ $post->title }}</h4>
                        <p class="text-gray-600">{{ Str::limit($post->description, 100) }}</p>
                        <div class="mt-3">
                            <x-button :href="route('posts.show', $post)" type="info" size="sm">
                                View Post
                            </x-button>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</x-layout>