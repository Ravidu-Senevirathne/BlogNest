@props([
    'from' => 'from-indigo-600',
    'to' => 'to-purple-600',
    'darkFrom' => 'dark:from-indigo-800',
    'darkTo' => 'dark:to-purple-900',
    'container' => 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8',
    'py' => 'py-16'
])

<section class="bg-gradient-to-r {{ $from }} {{ $to }} {{ $darkFrom }} {{ $darkTo }} text-white {{ $py }}">
    <div class="{{ $container }}">
        {{ $slot }}
    </div>
</section>
