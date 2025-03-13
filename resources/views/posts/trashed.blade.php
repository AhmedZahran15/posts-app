<x-layout>
    <div class="px-4">
        <div class="overflow-x-auto">
            <div class="card">
                <div class="card-header font-medium text-lg mb-4">Trashed Posts</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($trashedPosts->count() > 0)
                        <table
                            class="min-w-full border-2 rounded-lg border-neutral-200 text-center divide-y-2 divide-gray-200 bg-white text-sm">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 font-medium whitespace-nowrap text-gray-900">Title</th>
                                    <th class="px-4 py-2 font-medium whitespace-nowrap text-gray-900">Deleted At</th>
                                    <th class="px-4 py-2 font-medium whitespace-nowrap text-gray-900">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($trashedPosts as $post)
                                    <tr>
                                        <td class="px-4 py-2 whitespace-nowrap text-gray-700">{{ $post->title }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-gray-700">
                                            {{ $post->deleted_at->format('Y-m-d H:i') }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-gray-700 space-x-2">
                                            <form action="{{ route('posts.restore', $post->id) }}" method="POST"
                                                class="inline-block">
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
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-gray-700">No trashed posts found.</p>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('posts.index') }}"
                            class="inline-block px-4 py-1 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700">Back
                            to Posts</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>