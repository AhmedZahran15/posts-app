<x-layout :title="'Edit Post'">
    <div class="py-8">
        <div class="max-w-3xl mx-auto">
            <x-card title="Edit Post #{{ $post['id'] }}" gradient="true">
                @if(session('error'))
                    <x-alert type="error" :message="session('error')" />
                @endif

                <form method="POST" action="/posts/{{ $post['id'] }}">
                    @csrf
                    @method('PUT')

                    <x-form.input label="Title" name="title" :value="$post['title']" required />

                    <x-form.textarea label="Description" name="description" :value="$post['description']" :rows="6"
                        required />

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4">
                        <x-button :href="route('posts.index')" type="secondary">
                            Cancel
                        </x-button>

                        <x-button type="primary">
                            Update Post
                        </x-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-layout>