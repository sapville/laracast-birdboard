@props(['buttonStyle' => 'bg-blue-400 border-transparent text-white'])
<button {{
    $attributes->merge([
        'type' => 'submit',
        'class' => 'inline-flex items-center px-4 py-2 border font-semibold ' . $buttonStyle .
        ' text-xs uppercase tracking-widest hover:bg-blue-600 active:bg-blue-600 focus:outline-none ' .
        'focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150'
    ]) }}>
    {{ $slot }}
</button>
