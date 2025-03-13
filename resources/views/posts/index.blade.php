<x-layout>
    @if (session('success'))
        <div class="px-4 py-2">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Navigation Tabs -->
    <div class="px-4 mb-4">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-6">
                <a href="{{ route('posts.index', ['view' => 'active']) }}"
                    class="py-2 px-1 {{ $currentView != 'trashed' ? 'border-b-2 border-indigo-500 text-indigo-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Active Posts
                </a>
                <a href="{{ route('posts.index', ['view' => 'trashed']) }}"
                    class="py-2 px-1 {{ $currentView == 'trashed' ? 'border-b-2 border-indigo-500 text-indigo-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Trashed Posts
                </a>
            </nav>
        </div>
    </div>

    <!-- Table Component -->
    <div class="px-4">
        <div class="overflow-x-auto">
            @if($currentView == 'trashed')
                <!-- Trashed Posts Table -->
                <table
                    class="min-w-full border-2 rounded-lg border-neutral-200 text-center divide-y-2 divide-gray-200 bg-white text-sm">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 font-medium whitespace-nowrap text-gray-900">Title</th>
                            <th class="px-4 py-2 font-medium whitespace-nowrap text-gray-900">Posted By</th>
                            <th class="px-4 py-2 font-medium whitespace-nowrap text-gray-900">Deleted At</th>
                            <th class="px-4 py-2 font-medium whitespace-nowrap text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($posts as $post)
                            <tr>
                                <td class="px-4 py-2 whitespace-nowrap text-gray-700">{{ $post['title'] }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-gray-700">{{ $post->user['name'] }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-gray-700">
                                    {{ $post->deleted_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-gray-700 space-x-2">
                                    <form action="{{ route('posts.restore', $post->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="inline-block px-4 py-1 text-xs font-medium text-white bg-green-600 rounded hover:bg-green-700">Restore</button>
                                    </form>
                                    <form action="{{ route('posts.forceDelete', $post->id) }}" method="POST"
                                        class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-block px-4 py-1 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700"
                                            onclick="return confirm('Are you sure you want to permanently delete this post?')">Delete
                                            Permanently</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-gray-700 text-center">No trashed posts found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @else
                <!-- Active Posts Table -->
                <table
                    class="min-w-full border-2 rounded-lg border-neutral-200 text-center divide-y-2 divide-gray-200 bg-white text-sm">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 font-medium whitespace-nowrap text-gray-900">#</th>
                            <th class="px-4 py-2 font-medium whitespace-nowrap text-gray-900">Title</th>
                            <th class="px-4 py-2 font-medium whitespace-nowrap text-gray-900">Posted By</th>
                            <th class="px-4 py-2 font-medium whitespace-nowrap text-gray-900">Created At</th>
                            <th class="px-4 py-2 font-medium whitespace-nowrap text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($posts as $post)
                            <tr>
                                <td class="px-4 py-2 font-medium whitespace-nowrap text-gray-900">{{ $post['id'] }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-gray-700">{{$post['title']}}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-gray-700">{{ $post->user['name'] }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-gray-700">
                                    {{ \Carbon\Carbon::parse($post['created_at'])->format('Y-m-d') }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-gray-700 space-x-2">
                                    <a href="{{ route('posts.show', $post['id']) }}"
                                        class="inline-block px-4 py-1 text-xs font-medium text-white bg-blue-400 rounded hover:bg-blue-500">View</a>
                                    <a href="{{ route('posts.edit', $post['id']) }}"
                                        class="inline-block px-4 py-1 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700">Edit</a>
                                    <form action="{{ route('posts.destroy', $post['id']) }}" method="POST" class="inline-block"
                                        onsubmit="return confirm('Are you sure you want to delete this post?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-block cursor-pointer px-4 py-1 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-gray-700 text-center">No posts found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
        <!-- Pagination -->
        <div class="mt-4 px-4 py-2">
            {{ $posts->appends(['view' => $currentView])->onEachSide(2)->links() }}
        </div>
    </div>
</x-layout>
