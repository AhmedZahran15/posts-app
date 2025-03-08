<button {{ $attributes->merge(['class' => 'px-4 py-2 rounded-md font-medium text-sm transition-colors ' . 
    ($type === 'primary' ? 'bg-blue-500 hover:bg-blue-600 text-white' : 
    ($type === 'secondary' ? 'bg-gray-200 hover:bg-gray-300 text-gray-800' : 
    ($type === 'danger' ? 'bg-red-500 hover:bg-red-600 text-white' : 'bg-blue-500 hover:bg-blue-600 text-white')))]) }}>
    {{ $slot }}
</button>
