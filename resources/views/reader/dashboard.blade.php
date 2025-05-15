<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Reader Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mb-3">Welcome to BlogNest, Reader!</h3>
                        <p class="text-gray-600 dark:text-gray-300">Discover and explore amazing stories from our writers.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                        <div class="bg-indigo-50 dark:bg-gray-700 p-5 rounded-lg border-l-4 border-indigo-500">
                            <h4 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300 mb-2">Reading Lists</h4>
                            <p class="text-gray-700 dark:text-gray-300">Organize and save your favorite articles.</p>
                            <a href="#" class="block mt-4 text-indigo-600 dark:text-indigo-400 font-medium hover:underline">
                                Create Reading List
                            </a>
                        </div>
                        
                        <div class="bg-indigo-50 dark:bg-gray-700 p-5 rounded-lg border-l-4 border-indigo-500">
                            <h4 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300 mb-2">Recommended For You</h4>
                            <p class="text-gray-700 dark:text-gray-300">Content tailored to your interests.</p>
                            <a href="/blog" class="block mt-4 text-indigo-600 dark:text-indigo-400 font-medium hover:underline">
                                View Recommendations
                            </a>
                        </div>
                        
                        <div class="bg-indigo-50 dark:bg-gray-700 p-5 rounded-lg border-l-4 border-indigo-500">
                            <h4 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300 mb-2">Community</h4>
                            <p class="text-gray-700 dark:text-gray-300">Connect with other readers and authors.</p>
                            <a href="#" class="block mt-4 text-indigo-600 dark:text-indigo-400 font-medium hover:underline">
                                Join Discussions
                            </a>
                        </div>
                    </div>
                    
                    <div class="mt-10">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-xl font-semibold text-indigo-700 dark:text-indigo-300">Latest Posts</h4>
                            <a href="/blog" class="text-indigo-600 dark:text-indigo-400 hover:underline">View All</a>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-5">
                            <p class="text-gray-500 dark:text-gray-400 italic">Start exploring the latest content from our authors.</p>
                            <div class="mt-4">
                                <a href="/blog" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-md text-sm font-medium inline-block">
                                    Browse Articles
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
