@props(['type' => 'success', 'message'])

@php
  $typeClasses = [
    'success' => 'bg-green-100 border-l-4 border-green-500 text-green-700',
    'error' => 'bg-red-100 border-l-4 border-red-500 text-red-700',
    'warning' => 'bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700',
    'info' => 'bg-blue-100 border-l-4 border-blue-500 text-blue-700',
  ];
@endphp

@if($message)
  <div class="{{ $typeClasses[$type] }} p-4 mb-6 rounded-md" role="alert">
    <p class="font-medium">{{ $message }}</p>
  </div>
@endif