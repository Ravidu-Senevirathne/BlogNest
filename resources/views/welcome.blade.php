<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="BlogNest - A platform for passionate writers">
    <title>{{ config('app.name', 'BlogNest') }}</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Dark mode script -->
    <x-theme.script />
</head>
<body class="font-sans antialiased text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-gray-900 transition-colors">
    <x-navbar />
    
    <main>
        <x-hero />
        <x-features />
        {{-- Replace the static recent-posts component with the dynamic one --}}
        <x-recent-posts :posts="$featuredPosts" />
        <x-cta />
    </main>
    
    <x-footer />
</body>
</html>