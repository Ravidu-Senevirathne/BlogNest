@props([
    'bg' => 'bg-white dark:bg-gray-800',
    'container' => 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8',
    'py' => 'py-16'
])

<section class="{{ $bg }} {{ $py }}">
    <div class="{{ $container }}">
        {{ $slot }}
    </div>
</section>
