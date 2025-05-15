<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Blog') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Categories -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Categories</h3>
                        </div>
                        <div class="p-4">
                            <ul class="space-y-2">
                                @foreach($categories as $category)
                                    <li>
                                        <a href="{{ route('blog.index', ['category' => $category->id]) }}" class="flex justify-between text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">
                                            <span>{{ $category->name }}</span>
                                            <span class="text-gray-500 dark:text-gray-400">({{ $category->posts_count }})</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Popular Tags -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Popular Tags</h3>
                        </div>
                        <div class="p-4">
                            <div class="flex flex-wrap gap-2">
                                @foreach($tags as $tag)
                                    <a href="{{ route('blog.index', ['tag' => $tag->id]) }}" class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs px-2 py-1 rounded hover:bg-blue-100 dark:hover:bg-blue-900">
                                        #{{ $tag->name }} ({{ $tag->posts_count }})
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <!-- Blog Posts -->
                    @if($posts->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($posts as $post)
                                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                    @if($post->image)
                                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-52 object-cover">
                                    @endif
                                    <div class="p-6">
                                        <div class="flex justify-between items-center mb-3">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $post->created_at->format('M d, Y') }}</span>
                                            <a href="{{ route('blog.index', ['category' => $post->category->id]) }}" class="text-xs bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200 py-1 px-2 rounded-full">
                                                {{ $post->category->name }}
                                            </a>
                                        </div>
                                        
                                        <h3 class="font-bold text-xl mb-2">
                                            <a href="{{ route('blog.show', $post->slug) }}" class="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                                                {{ $post->title }}
                                            </a>
                                        </h3>
                                        
                                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">
                                            {{ Str::limit(strip_tags($post->content), 150) }}
                                        </p>
                                        
                                        <div class="flex justify-between items-center mt-4">
                                            <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                    </svg>
                                                    {{ $post->likesCount() }}
                                                </div>
                                                
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                    </svg>
                                                    {{ $post->comments->count() }}
                                                </div>
                                            </div>
                                            
                                            <a href="{{ route('blog.show', $post->slug) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm">
                                                Read more →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-6">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                            <p class="text-gray-700 dark:text-gray-300">No posts found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <x-slot name="footer"></x-slot>
</x-app-layout>
