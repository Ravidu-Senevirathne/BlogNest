<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $post->title }}
            </h2>
            <a href="{{ route('blog.index') }}" class="px-4 py-2 bg-gray-800 dark:bg-gray-200 text-white dark:text-gray-800 rounded-md text-sm">
                Back to Blog
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <article class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Post Header with Author Info -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            @if($post->user->avatar)
                                <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}" class="h-10 w-10 rounded-full">
                            @else
                                <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr($post->user->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $post->user->name }}</p>
                            <div class="flex space-x-2 text-sm text-gray-500 dark:text-gray-400">
                                <time datetime="{{ $post->created_at->format('Y-m-d') }}">
                                    {{ $post->created_at->format('M d, Y') }}
                                </time>
                                <span aria-hidden="true">&middot;</span>
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">
                        {{ $post->title }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-2 mb-6">
                        <a href="#" class="bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200 py-1 px-3 rounded-full text-sm">
                            {{ $post->category->name }}
                        </a>
                        
                        @foreach($post->tags as $tag)
                            <span class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs px-2 py-1 rounded">
                                #{{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
                
                <!-- Featured Image -->
                @if($post->image)
                    <div class="w-full border-b border-gray-200 dark:border-gray-700">
                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full object-cover max-h-[500px]">
                    </div>
                @endif
                
                <!-- Post Content -->
                <div class="p-6 prose dark:prose-invert max-w-none dark:text-white">
                    {!! $post->content !!}
                </div>
                
                <!-- Post Actions -->
                @auth
                <div class="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <div class="flex items-center justify-between">
                        <div class="flex space-x-4">
                            <!-- Like Button -->
                            <form action="{{ route('reader.like.toggle', $post) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="flex items-center text-sm {{ auth()->user()->hasLiked($post->id) ? 'text-red-500' : 'text-gray-500' }} hover:text-red-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 {{ auth()->user()->hasLiked($post->id) ? 'fill-red-500' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    {{ $post->likesCount() }} likes
                                </button>
                            </form>
                            
                            <!-- Bookmark Button -->
                            <form action="{{ route('reader.bookmark.toggle', $post) }}" method="POST" class="inline bookmark-form" data-post-id="{{ $post->id }}">
                                @csrf
                                <button type="submit" class="flex items-center text-sm {{ auth()->user()->hasBookmarked($post->id) ? 'text-blue-500' : 'text-gray-500' }} hover:text-blue-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 bookmark-icon {{ auth()->user()->hasBookmarked($post->id) ? 'fill-blue-500' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                    </svg>
                                    <span class="bookmark-text">{{ auth()->user()->hasBookmarked($post->id) ? 'Bookmarked' : 'Bookmark' }}</span>
                                </button>
                            </form>
                        </div>
                        
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $post->comments->count() }} comments
                        </div>
                    </div>
                </div>
                @endauth
                
                <!-- Comments Section -->
                <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Comments</h3>
                    
                    @auth
                        <!-- Comment Form -->
                        <form action="{{ route('reader.comments.store', $post) }}" method="POST" class="mb-6">
                            @csrf
                            <div>
                                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Leave a comment</label>
                                <textarea rows="3" name="content" id="content" class="shadow-sm block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md" required></textarea>
                            </div>
                            <div class="mt-3 text-right">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Post Comment
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 mb-6 text-center">
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                Please <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 hover:underline">log in</a> to comment on this post.
                            </p>
                        </div>
                    @endauth
                    
                    <!-- Comments List -->
                    <div class="space-y-6">
                        @forelse($post->comments as $comment)
                            <div class="flex space-x-3">
                                <div class="flex-shrink-0">
                                    @if($comment->user->avatar)
                                        <img src="{{ $comment->user->avatar }}" alt="{{ $comment->user->name }}" class="h-10 w-10 rounded-full">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-700 dark:text-gray-300 text-sm font-bold">
                                            {{ substr($comment->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow">
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg px-4 py-3">
                                        <div class="flex items-center justify-between">
                                            <span class="font-medium text-gray-900 dark:text-gray-100">{{ $comment->user->name }}</span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="mt-1 text-gray-700 dark:text-gray-300">{{ $comment->content }}</p>
                                    </div>
                                    
                                    @auth
                                        @if(auth()->id() === $comment->user_id)
                                            <div class="mt-1 flex justify-end">
                                                <form action="{{ route('reader.comments.destroy', $comment) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs text-red-500 hover:text-red-700">Delete</button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 dark:text-gray-400 py-4">
                                No comments yet. Be the first to comment!
                            </div>
                        @endforelse
                    </div>
                </div>
            </article>

            <!-- Related Posts -->
            @if($relatedPosts->count() > 0)
                <div class="mt-8">
                    <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Related Posts</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($relatedPosts as $relatedPost)
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                @if($relatedPost->image)
                                    <img src="{{ asset('storage/' . $relatedPost->image) }}" alt="{{ $relatedPost->title }}" class="w-full h-40 object-cover">
                                @endif
                                <div class="p-4">
                                    <h4 class="font-semibold text-lg mb-2">
                                        <a href="{{ route('blog.show', $relatedPost->slug) }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                            {{ $relatedPost->title }}
                                        </a>
                                    </h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ Str::limit(strip_tags($relatedPost->content), 100) }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <x-slot name="footer"></x-slot>
</x-app-layout>

<!-- Add JavaScript at the end of the file, just before closing x-app-layout tag -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle bookmark form submission via AJAX
    document.querySelectorAll('.bookmark-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const postId = this.getAttribute('data-post-id');
            const url = this.getAttribute('action');
            const token = this.querySelector('input[name="_token"]').value;
            
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
                const bookmarkButton = this.querySelector('button');
                const bookmarkIcon = this.querySelector('.bookmark-icon');
                const bookmarkText = this.querySelector('.bookmark-text');
                
                if (data.bookmarked) {
                    bookmarkButton.classList.add('text-blue-500');
                    bookmarkButton.classList.remove('text-gray-500');
                    bookmarkIcon.classList.add('fill-blue-500');
                    bookmarkText.textContent = 'Bookmarked';
                } else {
                    bookmarkButton.classList.remove('text-blue-500');
                    bookmarkButton.classList.add('text-gray-500');
                    bookmarkIcon.classList.remove('fill-blue-500');
                    bookmarkText.textContent = 'Bookmark';
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
</script>
