<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <div>
                <a href="{{ route('reader.search') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    {{ __('Search Posts') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if($publishedPosts->count() > 0)
                @foreach($publishedPosts as $post)
                    <div id="post-{{ $post->id }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <!-- Post Header with Author Info -->
                        <div class="p-4 flex items-center space-x-3 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex-shrink-0">
                                @if($post->user->avatar)
                                    <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}" class="h-10 w-10 rounded-full">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                                        {{ substr($post->user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div class="font-medium text-sm text-gray-900 dark:text-gray-100">{{ $post->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</div>
                            </div>
                            
                            <div class="ml-auto flex items-center text-xs">
                                <span class="bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200 py-1 px-2 rounded">
                                    {{ $post->category->name }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Post Image -->
                        @if($post->image)
                            <div class="w-full">
                                <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full object-cover max-h-[500px]">
                            </div>
                        @endif
                        
                        <!-- Post Content -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-gray-100">
                                <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                    {{ $post->title }}
                                </a>
                            </h3>
                            
                            <div class="prose dark:prose-invert max-w-none line-clamp-3 mb-4 text-gray-400">
                                {!! Str::limit(strip_tags($post->content), 250) !!}
                            </div>
                            
                            @if(strlen(strip_tags($post->content)) > 250)
                                <div class="mb-4">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                        Read more
                                    </a>
                                </div>
                            @endif

                            <!-- Tags -->
                            @if($post->tags->count() > 0)
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach($post->tags as $tag)
                                        <span class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs px-2 py-1 rounded">
                                            #{{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                            
                            <!-- Interaction Buttons -->
                            <div class="flex items-center justify-between pt-3 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex space-x-4">
                                    <!-- Like Button -->
                                    <form action="{{ route('reader.like.toggle', $post) }}" method="POST" class="like-form" data-post-id="{{ $post->id }}">
                                        @csrf
                                        <button type="submit" class="flex items-center space-x-1 group">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="{{ auth()->user()->hasLiked($post->id) ? 'text-red-500 fill-red-500' : 'text-gray-400 dark:text-gray-500 group-hover:text-red-500' }}" width="22" height="22" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572"></path>
                                            </svg>
                                            <span class="text-sm {{ auth()->user()->hasLiked($post->id) ? 'text-red-500' : 'text-gray-500 dark:text-gray-400' }} like-count">{{ $post->likesCount() }}</span>
                                        </button>
                                    </form>
                                    
                                    <!-- Comment Button -->
                                    <button type="button" class="flex items-center space-x-1 group comment-btn" data-post-id="{{ $post->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-gray-400 dark:text-gray-500 group-hover:text-blue-500" width="22" height="22" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M12 20l-3 -3h-2a3 3 0 0 1 -3 -3v-6a3 3 0 0 1 3 -3h10a3 3 0 0 1 3 3v6a3 3 0 0 1 -3 3h-2l-3 3"></path>
                                            <line x1="8" y1="9" x2="16" y2="9"></line>
                                            <line x1="8" y1="13" x2="14" y2="13"></line>
                                        </svg>
                                        <span class="text-sm text-gray-500 dark:text-gray-400 comment-count">{{ $post->comments->count() }}</span>
                                    </button>
                                </div>
                                
                                <!-- Bookmark Button -->
                                <form action="{{ route('reader.bookmark.toggle', $post) }}" method="POST" class="bookmark-form">
                                    @csrf
                                    <button type="submit" class="flex items-center space-x-1 group">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="{{ auth()->user()->hasBookmarked($post->id) ? 'text-indigo-500 fill-indigo-500' : 'text-gray-400 dark:text-gray-500 group-hover:text-indigo-500' }}" width="22" height="22" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M9 4h6a2 2 0 0 1 2 2v14l-5 -3l-5 3v-14a2 2 0 0 1 2 -2"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>

                            <!-- Comments Section (Initially Hidden) -->
                            <div class="comment-section hidden mt-4 pt-4 border-t border-gray-200 dark:border-gray-700" id="comments-{{ $post->id }}">
                                <!-- Comment List -->
                                <div class="space-y-4 mb-4 comments-container">
                                    @foreach($post->comments()->latest()->get() as $comment)
                                        <div class="flex space-x-3 comment-item">
                                            <div class="flex-shrink-0">
                                                @if($comment->user->avatar)
                                                    <img src="{{ $comment->user->avatar }}" alt="{{ $comment->user->name }}" class="h-8 w-8 rounded-full">
                                                @else
                                                    <div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-700 dark:text-gray-300 text-sm font-bold">
                                                        {{ substr($comment->user->name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow">
                                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg px-3 py-2 text-sm">
                                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $comment->user->name }}</span>
                                                    <p class="text-gray-700 dark:text-gray-300">{{ $comment->content }}</p>
                                                </div>
                                                <div class="mt-1 flex items-center space-x-2 text-xs text-gray-500 dark:text-gray-400">
                                                    <span>{{ $comment->created_at->diffForHumans() }}</span>
                                                    @if($comment->user_id === auth()->id())
                                                        <form action="{{ route('reader.comments.destroy', $comment) }}" method="POST" class="delete-comment-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Comment Form -->
                                <form action="{{ route('reader.comments.store', $post) }}" method="POST" class="flex space-x-2 comment-form" data-post-id="{{ $post->id }}">
                                    @csrf
                                    <div class="flex-grow">
                                        <input type="text" name="content" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" placeholder="Add a comment..." required>
                                    </div>
                                    <button type="submit" class="px-3 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Post
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="mt-6">
                    {{ $publishedPosts->links() }}
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 text-center">
                        <p class="mb-4">No posts have been published yet.</p>
                        <p>Check back later for new content!</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- AJAX Script for Interactive Features -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle comments visibility
            document.querySelectorAll('.comment-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const postId = this.getAttribute('data-post-id');
                    const commentSection = document.getElementById(`comments-${postId}`);
                    commentSection.classList.toggle('hidden');
                });
            });

            // Handle like form submission via AJAX
            document.querySelectorAll('.like-form').forEach(form => {
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
                        const likeButton = this.querySelector('svg');
                        const likeCount = this.querySelector('.like-count');
                        
                        if (data.liked) {
                            likeButton.classList.add('text-red-500', 'fill-red-500');
                            likeButton.classList.remove('text-gray-400', 'dark:text-gray-500');
                            likeCount.classList.add('text-red-500');
                            likeCount.classList.remove('text-gray-500', 'dark:text-gray-400');
                        } else {
                            likeButton.classList.remove('text-red-500', 'fill-red-500');
                            likeButton.classList.add('text-gray-400', 'dark:text-gray-500');
                            likeCount.classList.remove('text-red-500');
                            likeCount.classList.add('text-gray-500', 'dark:text-gray-400');
                        }
                        
                        likeCount.textContent = data.count;
                    })
                    .catch(error => console.error('Error:', error));
                });
            });

            // Handle bookmark form submission via AJAX
            document.querySelectorAll('.bookmark-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
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
                        const bookmarkIcon = this.querySelector('svg');
                        
                        if (data.bookmarked) {
                            bookmarkIcon.classList.add('text-indigo-500', 'fill-indigo-500');
                            bookmarkIcon.classList.remove('text-gray-400', 'dark:text-gray-500');
                        } else {
                            bookmarkIcon.classList.remove('text-indigo-500', 'fill-indigo-500');
                            bookmarkIcon.classList.add('text-gray-400', 'dark:text-gray-500');
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            });

            // Handle comment form submission via AJAX
            document.querySelectorAll('.comment-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const postId = this.getAttribute('data-post-id');
                    const url = this.getAttribute('action');
                    const token = this.querySelector('input[name="_token"]').value;
                    const content = this.querySelector('input[name="content"]').value;
                    
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({ content: content }),
                        credentials: 'same-origin'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Clear the input
                            this.querySelector('input[name="content"]').value = '';
                            
                            // Create a new comment element
                            const commentHTML = `
                                <div class="flex space-x-3 comment-item">
                                    <div class="flex-shrink-0">
                                        ${data.user.avatar ? 
                                            `<img src="${data.user.avatar}" alt="${data.user.name}" class="h-8 w-8 rounded-full">` :
                                            `<div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-700 dark:text-gray-300 text-sm font-bold">
                                                ${data.user.name.charAt(0)}
                                            </div>`
                                        }
                                    </div>
                                    <div class="flex-grow">
                                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg px-3 py-2 text-sm">
                                            <span class="font-medium text-gray-900 dark:text-gray-100">${data.user.name}</span>
                                            <p class="text-gray-700 dark:text-gray-300">${data.comment.content}</p>
                                        </div>
                                        <div class="mt-1 flex items-center space-x-2 text-xs text-gray-500 dark:text-gray-400">
                                            <span>Just now</span>
                                            <form action="/reader/comments/${data.comment.id}" method="POST" class="delete-comment-form">
                                                <input type="hidden" name="_token" value="${token}">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            `;
                            
                            // Update the comments container
                            const commentsContainer = document.querySelector(`#comments-${postId} .comments-container`);
                            commentsContainer.insertAdjacentHTML('afterbegin', commentHTML);
                            
                            // Update comment count
                            const commentCountEl = document.querySelector(`button[data-post-id="${postId}"] .comment-count`);
                            const currentCount = parseInt(commentCountEl.textContent);
                            commentCountEl.textContent = currentCount + 1;
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            });

            // Handle comment deletion via AJAX
            document.addEventListener('click', function(e) {
                if (e.target && e.target.closest('.delete-comment-form')) {
                    e.preventDefault();
                    const form = e.target.closest('.delete-comment-form');
                    const url = form.getAttribute('action');
                    const token = form.querySelector('input[name="_token"]').value;
                    const commentItem = form.closest('.comment-item');
                    
                    if (confirm('Are you sure you want to delete this comment?')) {
                        fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': token
                            },
                            credentials: 'same-origin'
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Remove the comment from the DOM
                                commentItem.remove();
                                
                                // Update comment count for the post
                                const postId = data.post_id;
                                const commentCountEl = document.querySelector(`button[data-post-id="${postId}"] .comment-count`);
                                const currentCount = parseInt(commentCountEl.textContent);
                                commentCountEl.textContent = currentCount - 1;
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    }
                }
            });
        });
    </script>
    <x-slot name="footer"></x-slot>
</x-app-layout>
