<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="BlogNest - A platform for passionate writers">
    <title>{{ config('app.name', 'BlogNest') }}</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="font-sans antialiased text-gray-800 bg-gray-50">
    <x-navbar />
    
    <main>
        <x-hero />
        <x-features />
        <x-recent-posts />
        <x-cta />
    </main>
    
    <x-footer />
</body>
</html>