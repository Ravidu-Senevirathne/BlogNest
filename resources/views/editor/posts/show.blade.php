<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-indigo-700 dark:text-indigo-300 leading-tight">
                {{ $post->title }}
            </h2>
            <div>
                <a href="{{ route('editor.posts.edit', $post) }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white font-semibold rounded-md shadow transition-colors text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('editor.posts.index') }}"
                   class="ml-2 inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-md shadow transition-colors text-sm">
                    Back to Posts
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    @if($post->image)
                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-auto max-h-96 object-cover rounded-lg mb-6 shadow">
                    @endif

                    <div class="mb-4">
                        <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 uppercase tracking-wide">
                            {{ ucfirst($post->status) }}
                        </span>
                        @if($post->category)
                            <span class="ml-2 inline-block px-3 py-1 text-sm font-semibold rounded-full bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300">
                                Category: {{ $post->category->name }}
                            </span>
                        @endif
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ $post->title }}</h1>
                    
                    <div class="prose prose-indigo dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                        {!! $post->content !!}
                    </div>

                    @if($post->tags && $post->tags->isNotEmpty())
                        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Tags:</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($post->tags as $tag)
                                    <span class="inline-block bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs px-3 py-1 rounded-full">{{ $tag->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 text-sm text-gray-500 dark:text-gray-400">
                        <p>Published on: {{ $post->created_at->format('F j, Y, g:i a') }}</p>
                        <p>Last updated: {{ $post->updated_at->format('F j, Y, g:i a') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
