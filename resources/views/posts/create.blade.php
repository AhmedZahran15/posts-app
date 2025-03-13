<x-layout :title="'Create Post'">
    <div class="py-8">
        <div class="max-w-3xl mx-auto">
            <x-card title="Create New Post" gradient="true">
                @if(session('error'))
                    <x-alert type="error" :message="session('error')" />
                @endif

                <form method="POST" action="/posts">
                    @csrf
                    <x-form.input label="Title" name="title" :value="old('title')" required autofocus />

                    <x-form.textarea label="Description" name="description" :value="old('description')" :rows="6"
                        required />

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4">
                        <x-button :href="route('posts.index')" type="secondary">
                            Cancel
                        </x-button>

                        <x-button type="primary">
                            Create Post
                        </x-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-layout>
