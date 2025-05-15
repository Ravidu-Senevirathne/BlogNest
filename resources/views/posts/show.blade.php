<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $post->title }}
            </h2>
            <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Featured Image -->
                    @if($post->image)
                        <div class="mb-6">
                            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-64 md:h-96 object-cover rounded-lg">
                        </div>
                    @endif

                    <!-- Post Header -->
                    <div class="mb-6">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $post->title }}</h1>
                        <div class="flex items-center justify-between mt-4">
                            <div class="flex items-center text-gray-600">
                                <span>By {{ $post->user->name }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $post->created_at->format('M d, Y') }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $post->category->name }}</span>
                            </div>
                            
                            <div class="flex space-x-3">
                                @auth
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
                                    
                                    <!-- Bookmark Button -->
                                    <form action="{{ route('reader.bookmark.toggle', $post) }}" method="POST" class="inline bookmark-form" data-post-id="{{ $post->id }}">
                                        @csrf
                                        <button type="submit" class="flex items-center text-sm {{ auth()->user()->hasBookmarked($post->id) ? 'text-blue-500' : 'text-gray-500' }} hover:text-blue-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                            </svg>
                                            <span class="bookmark-text">{{ auth()->user()->hasBookmarked($post->id) ? 'Bookmarked' : 'Bookmark' }}</span>
                                        </button>
                                    </form>
                                @endauth
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tags -->
                    @if($post->tags->count() > 0)
                        <div class="mb-6">
                            @foreach($post->tags as $tag)
                                <span class="inline-block bg-gray-100 text-gray-700 text-sm rounded-full px-3 py-1 mr-2 mb-2">
                                    #{{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <!-- Post Content -->
                    <div class="prose max-w-none">
                        {!! $post->content !!}
                    </div>
                    
                    <!-- Comments Section -->
                    <div class="mt-10 pt-6 border-t border-gray-200">
                        <h3 class="text-xl font-semibold mb-4">Comments ({{ $post->approvedComments()->count() }})</h3>
                        
                        @auth
                            <!-- Comment Form -->
                            <div class="mb-8">
                                <form action="{{ route('reader.comments.store', $post) }}" method="POST">
                                    @csrf
                                    <div>
                                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Leave a comment</label>
                                        <textarea id="content" name="content" rows="3" class="shadow-sm block w-full focus:ring-blue-500 focus:border-blue-500 sm:text-sm border border-gray-300 rounded-md" required></textarea>
                                        @error('content')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mt-3 flex justify-end">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Post Comment
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-md p-4 mb-8">
                                <p class="text-center text-gray-600">
                                    Please <a href="{{ route('login') }}" class="text-blue-600 hover:underline">login</a> to leave a comment.
                                </p>
                            </div>
                        @endauth
                        
                        <!-- Comment List -->
                        @if($post->approvedComments->count() > 0)
                            <div class="space-y-6">
                                @foreach($post->approvedComments()->latest()->get() as $comment)
                                    <div class="bg-gray-50 rounded-lg p-4" id="comment-{{ $comment->id }}">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center">
                                                @if($comment->user->avatar)
                                                    <img src="{{ $comment->user->avatar }}" alt="{{ $comment->user->name }}" class="h-8 w-8 rounded-full mr-2">
                                                @else
                                                    <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center mr-2">
                                                        <span class="text-white font-bold text-sm">{{ strtoupper(substr($comment->user->name, 0, 1)) }}</span>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h4 class="text-sm font-semibold">{{ $comment->user->name }}</h4>
                                                    <p class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                            
                                            @auth
                                                @if($comment->user_id === auth()->id())
                                                    <form action="{{ route('reader.comments.destroy', $comment) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-xs text-red-500 hover:text-red-700" onclick="return confirm('Are you sure you want to delete this comment?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            @endauth
                                        </div>
                                        <div class="text-sm text-gray-700 mt-2">
                                            {{ $comment->content }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-gray-500 py-6">
                                <p>No comments yet. Be the first to comment!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                    const bookmarkText = this.querySelector('.bookmark-text');
                    
                    if (data.bookmarked) {
                        bookmarkButton.classList.add('text-blue-500');
                        bookmarkButton.classList.remove('text-gray-500');
                        bookmarkText.textContent = 'Bookmarked';
                    } else {
                        bookmarkButton.classList.remove('text-blue-500');
                        bookmarkButton.classList.add('text-gray-500');
                        bookmarkText.textContent = 'Bookmark';
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    });
    </script>
    <x-slot name="footer"></x-slot>
</x-app-layout>
