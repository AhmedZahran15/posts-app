<x-layout>
    <!-- Status Messages -->
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <x-alert type="success" :message="session('success')" />
        </div>
    @endif

    @if (session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <x-alert type="error" :message="session('error')" />
        </div>
    @endif

    <div id="app" class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Navigation Tabs -->
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-6">
                    <a href="{{ route('posts.index', ['view' => 'active']) }}"
                        class="py-2 px-1 {{ $currentView != 'trashed' ? 'border-b-2 border-indigo-500 text-indigo-600 font-medium' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Active Posts
                    </a>
                    <a href="{{ route('posts.index', ['view' => 'trashed']) }}"
                        class="py-2 px-1 {{ $currentView == 'trashed' ? 'border-b-2 border-indigo-500 text-indigo-600 font-medium' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Trashed Posts
                    </a>
                </nav>
            </div>
        </div>

        <!-- Post Cards -->
        <div class="space-y-6">
            @if($currentView == 'trashed')
                @forelse ($posts as $post)
                    <!-- Trashed Post Card -->
                    <x-card>
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">{{ $post->title }}</h2>
                                <p class="text-sm text-gray-600">By {{ $post->user->name }}</p>
                            </div>
                            <div class="flex items-center">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mr-2">
                                    <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                    Deleted {{ $post->deleted_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                        @if(Auth::id() == $post->user_id)
                            <div class="mt-4 flex justify-end space-x-3">
                                <x-button method="PATCH" :href="route('posts.restore', $post->id)" type="primary"
                                    icon='<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>'>
                                    Restore
                                </x-button>

                                <x-button method="DELETE" :href="route('posts.forceDelete', $post->id)" type="danger"
                                    confirm="Are you sure you want to permanently delete this post? This action cannot be undone."
                                    icon='<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>'>
                                    Delete Permanently
                                </x-button>
                            </div>
                        @endif
                    </x-card>
                @empty
                    <x-card>
                        <div class="text-center">
                            <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            <p class="text-gray-500 text-lg">No trashed posts found.</p>
                        </div>
                    </x-card>
                @endforelse
            @else
                @forelse ($posts as $post)
                    <!-- Active Post Card -->
                    <x-card>
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">{{ $post->title }}</h2>
                                <div class="flex items-center text-sm text-gray-600 mt-1">
                                    <span>By {{ $post->user->name }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ \Carbon\Carbon::parse($post->created_at)->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end space-x-3">
                            <view-ajax :id="{{ $post->id }}"></view-ajax>

                            <x-button :href="route('posts.show', $post->id)" type="info"
                                icon='<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>'>
                                View
                            </x-button>

                            @if(Auth::id() == $post->user_id)
                                <x-button :href="route('posts.edit', $post->id)" type="primary"
                                    icon='<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>'>
                                    Edit
                                </x-button>

                                <x-button method="DELETE" :href="route('posts.destroy', $post->id)" type="danger"
                                    confirm="Are you sure you want to trash this post?"
                                    icon='<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>'>
                                    Delete
                                </x-button>
                            @endif
                        </div>
                    </x-card>
                @empty
                    <x-card>
                        <div class="text-center">
                            <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            <p class="text-gray-500 text-lg">No posts found.</p>
                            <div class="mt-4">
                                <x-button :href="route('posts.create')" type="primary">
                                    Create your first post
                                </x-button>
                            </div>
                        </div>
                    </x-card>
                @endforelse
            @endif
        </div>
        <!-- Pagination -->
        <div class="mt-6">
            {{ $posts->appends(['view' => $currentView])->onEachSide(2)->links() }}
        </div>
    </div>
</x-layout>
