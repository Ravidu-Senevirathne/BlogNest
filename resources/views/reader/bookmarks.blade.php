<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-300">
            {{ __('My Bookmarked Posts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 dark:bg-gray-800 text-gray-400">
                    @if($bookmarkedPosts->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($bookmarkedPosts as $post)
                                <div class="border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition">
                                    @if($post->image)
                                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                                    @endif
                                    <div class="p-4">
                                        <h4 class="text-xl font-semibold">
                                            <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-blue-600">
                                                {{ $post->title }}
                                            </a>
                                        </h4>
                                        <p class="text-sm text-gray-600 mt-1 dark:text-gray-400">
                                            By {{ $post->user->name }} in {{ $post->category->name }}
                                        </p>
                                        <div class="mt-2 text-gray-700 dark:text-gray-300">
                                            {{ Str::limit(strip_tags($post->content), 150) }}
                                        </div>
                                        
                                        <div class="flex items-center justify-between mt-4">
                                            <div class="flex space-x-3">
                                                <!-- Like Button -->
                                                <form action="{{ route('reader.like.toggle', $post) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="flex items-center text-sm {{ auth()->user()->hasLiked($post->id) ? 'text-blue-500' : 'text-gray-500' }} hover:text-blue-700">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                                        </svg>
                                                        {{ $post->likesCount() }}
                                                    </button>
                                                </form>
                                                
                                                <!-- Remove Bookmark Button -->
                                                <form action="{{ route('reader.bookmark.toggle', $post) }}" method="POST" class="inline bookmark-form" data-post-id="{{ $post->id }}">
                                                    @csrf
                                                    <button type="submit" class="flex items-center text-sm text-blue-500 hover:text-blue-700">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                                        </svg>
                                                        Remove
                                                    </button>
                                                </form>
                                                
                                                <!-- Comment Count -->
                                                <div class="flex items-center text-sm text-gray-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                    </svg>
                                                    {{ $post->comments->count() }}
                                                </div>
                                            </div>
                                            
                                            <a href="{{ route('blog.show', $post->slug) }}" class="text-sm text-blue-600 hover:text-blue-800">
                                                Read more →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-6">
                            {{ $bookmarkedPosts->links() }}
                        </div>
                    @else
                        <div class="text-center text-gray-500 py-8">
                            <p>You haven't bookmarked any posts yet.</p>
                            <a href="{{ route('reader.dashboard') }}" class="mt-2 inline-block text-blue-600 hover:text-blue-800">
                                Browse posts →
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle bookmark form submission via AJAX
    document.querySelectorAll('.bookmark-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const postId = this.getAttribute('data-post-id');
            const url = this.getAttribute('action');
            const token = this.querySelector('input[name="_token"]').value;
            const postElement = this.closest('.border.rounded-lg');
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (!data.bookmarked) {
                    // If the bookmark was removed, fade out and remove the post from the bookmarks page
                    postElement.style.transition = 'opacity 0.5s';
                    postElement.style.opacity = '0';
                    setTimeout(() => {
                        postElement.remove();
                        
                        // Check if there are any bookmarks left
                        if (document.querySelectorAll('.border.rounded-lg').length === 0) {
                            document.querySelector('.grid.grid-cols-1').innerHTML = `
                                <div class="text-center text-gray-500 py-8 col-span-full">
                                    <p>You haven't bookmarked any posts yet.</p>
                                    <a href="{{ route('reader.dashboard') }}" class="mt-2 inline-block text-blue-600 hover:text-blue-800">
                                        Browse posts →
                                    </a>
                                </div>
                            `;
                        }
                    }, 500);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
</script>
<x-slot name="footer"></x-slot>
</x-app-layout>
