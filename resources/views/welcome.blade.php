<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="BlogNest - A platform for passionate writers">
    <title>{{ config('app.name', 'BlogNest') }}</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Dark mode script -->
    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="font-sans antialiased text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-gray-900 transition-colors">
    <x-navbar />
    
    <main>
        <x-hero />
        <x-features />
        <x-recent-posts />
        <x-cta />
    </main>
    
    <x-footer />

    <x-theme.script />
</body>
</html>