@props(['label', 'name', 'value' => '', 'placeholder' => '', 'rows' => 3, 'required' => false])

<div class="mb-6">
  <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-2">{{ $label }}</label>
  <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}" placeholder="{{ $placeholder }}" {{ $required ? 'required' : '' }} {{ $attributes->merge(['class' => 'w-full px-4 py-3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150 ease-in-out']) }}>{{ $value }}</textarea>
  @error($name)
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
  @enderror
</div>