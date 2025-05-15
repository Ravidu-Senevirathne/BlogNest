<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-indigo-700 dark:text-indigo-300 leading-tight">
                {{ __('My Posts') }}
            </h2>
            <a href="{{ route('editor.posts.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 dark:from-indigo-500 dark:to-purple-500 text-white font-semibold rounded-md shadow transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                New Post
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-4 max-w-6xl mx-auto">
        @if (session('success'))
            <div class="mb-4 p-4 rounded bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 shadow">
                {{ session('success') }}
            </div>
        @endif

        @if (($posts ?? collect())->isEmpty())
            <div class="text-center py-16">
                <svg class="mx-auto h-16 w-16 text-indigo-300 dark:text-indigo-700" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/>
                </svg>
                <h3 class="mt-4 text-xl font-bold text-gray-700 dark:text-gray-200">No posts yet</h3>
                <p class="mt-2 text-gray-500 dark:text-gray-400">Start sharing your thoughts by creating a new post.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach (($posts ?? collect()) as $post)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-indigo-100 dark:border-indigo-900 hover:shadow-2xl transition-shadow duration-300 relative overflow-hidden">
                        @if($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}"
                                 class="w-full h-40 object-cover rounded-t-xl">
                        @else
                            <div class="w-full h-40 bg-gradient-to-r from-indigo-100 to-purple-100 dark:from-indigo-900 dark:to-purple-900 flex items-center justify-center">
                                <svg class="w-12 h-12 text-indigo-400 dark:text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                        @endif
                        <div class="p-5">
                            <div class="flex items-center justify-between mb-2">
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 uppercase tracking-wide">
                                    {{ ucfirst($post->status) }}
                                </span>
                                <span class="text-xs text-gray-400 dark:text-gray-500">{{ $post->created_at->format('M d, Y') }}</span>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 truncate">{{ $post->title }}</h3>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300 line-clamp-3">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach(($post->tags ?? []) as $tag)
                                    <span class="inline-block bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300 text-xs px-2 py-1 rounded">{{ $tag->name }}</span>
                                @endforeach
                            </div>
                            <div class="mt-4 flex justify-between items-center">
                                <a href="{{ route('editor.posts.show', $post) }}"
                                   class="text-indigo-600 dark:text-indigo-400 hover:underline font-medium text-sm">View</a>
                                <div class="flex gap-2">
                                    <a href="{{ route('editor.posts.edit', $post) }}"
                                       class="inline-flex items-center px-2 py-1 bg-indigo-50 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded hover:bg-indigo-100 dark:hover:bg-indigo-800 transition-colors text-xs font-semibold">
                                        Edit
                                    </a>
                                    <form action="{{ route('editor.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center px-2 py-1 bg-red-50 dark:bg-red-900 text-red-700 dark:text-red-300 rounded hover:bg-red-100 dark:hover:bg-red-800 transition-colors text-xs font-semibold">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <x-slot name="footer"></x-slot>
</x-app-layout>
