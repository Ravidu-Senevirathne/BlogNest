@props(['posts'])

<x-theme.section bg="bg-gray-50 dark:bg-gray-900" id="blog">
    <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white sm:text-4xl">Recent Publications</h2>
        <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">Discover trending stories from our writers</p>
    </div>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($posts as $post)
            <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                @if($post->image && Storage::disk('public')->exists($post->image))
                    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                @else
                    <img src="{{ asset('images/default-post-image.jpg') }}" alt="Post thumbnail" class="w-full h-48 object-cover" onerror="this.onerror=null; this.src='https://via.placeholder.com/600x400?text=BlogNest';">
                @endif
                <div class="p-6">
                    <div class="flex items-center mb-2">
                        <span class="bg-{{ $post->category->color ?? 'indigo' }}-100 dark:bg-{{ $post->category->color ?? 'indigo' }}-900 text-{{ $post->category->color ?? 'indigo' }}-800 dark:text-{{ $post->category->color ?? 'indigo' }}-300 text-xs px-2 py-1 rounded-full">{{ $post->category->name ?? 'Uncategorized' }}</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-900 dark:text-white">{{ $post->title }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">{{ Str::limit(strip_tags($post->content), 120) }}</p>
                    <div class="flex items-center">
                        <div class="h-8 w-8 rounded-full bg-gray-300 dark:bg-gray-600 overflow-hidden flex items-center justify-center">
                            @if($post->user && $post->user->profile_photo)
                                <img src="{{ asset('storage/' . $post->user->profile_photo) }}" alt="{{ $post->user->name }}" class="w-full h-full object-cover">
                            @else
                                @if($post->user)
                                    <span class="text-xs font-medium text-gray-600 dark:text-gray-300">
                                        {{ strtoupper(substr($post->user->name, 0, 2)) }}
                                    </span>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 dark:text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                            @endif
                        </div>
                        <div class="ml-2">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $post->user->name ?? 'Anonymous' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $post->created_at->format('F j, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-8">
                <p class="text-gray-500 dark:text-gray-400">No featured posts available yet.</p>
            </div>
        @endforelse
    </div>
    
    <div class="text-center mt-10">
        @auth
            <a href="{{ route('blog.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white font-medium rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                View all posts
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </a>
        @else
            <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white font-medium rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Sign up to read more
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
            </a>
        @endauth
    </div>
</x-theme.section>
