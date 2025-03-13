@props(['title' => null, 'gradient' => false])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-lg overflow-hidden']) }}>
  @if($title)
    <div
    class="{{ $gradient ? 'bg-gradient-to-r from-indigo-500 to-purple-600' : 'bg-gray-50 border-b border-gray-200' }} px-6 py-4">
    <h2 class="text-xl font-bold {{ $gradient ? 'text-white' : 'text-gray-800' }}">{{ $title }}</h2>
    </div>
  @endif

  <div class="p-8">
    {{ $slot }}
  </div>
</div>