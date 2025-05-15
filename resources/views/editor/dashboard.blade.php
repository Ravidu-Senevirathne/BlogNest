<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editor Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mb-3">Welcome to BlogNest, Editor!</h3>
                        <p class="text-gray-600 dark:text-gray-300">Create and manage your content with our powerful tools.</p>
                    </div>
                    
                    <div class="flex flex-wrap gap-4 mt-6">
                        <a href="{{ route('editor.posts.create') }}" class="px-5 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 font-medium text-sm">
                            Create New Post
                        </a>
                        <a href="{{ route('editor.posts.index') }}" class="px-5 py-3 bg-gray-600 text-white rounded-md hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 font-medium text-sm">
                            Manage Posts
                        </a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">
                        <div class="bg-indigo-50 dark:bg-gray-700 p-5 rounded-lg border-l-4 border-indigo-500">
                            <h4 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300 mb-2">Content Stats</h4>
                            <div class="mt-3 space-y-1 text-gray-700 dark:text-gray-300">
                                <div class="flex justify-between">
                                    <span>Approved</span>
                                    <span class="font-medium">{{ Auth::user()->posts()->where('status', 'approved')->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Pending</span>
                                    <span class="font-medium">{{ Auth::user()->posts()->where('status', 'pending')->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Rejected</span>
                                    <span class="font-medium">{{ Auth::user()->posts()->where('status', 'rejected')->count() }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-indigo-50 dark:bg-gray-700 p-5 rounded-lg border-l-4 border-indigo-500">
                            <h4 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300 mb-2">Quick Draft</h4>
                            <p class="text-gray-700 dark:text-gray-300">Jot down ideas for your next article.</p>
                            <a href="{{ route('editor.posts.create') }}" class="block mt-4 text-indigo-600 dark:text-indigo-400 font-medium hover:underline">
                                Start Writing
                            </a>
                        </div>
                        
                        <div class="bg-indigo-50 dark:bg-gray-700 p-5 rounded-lg border-l-4 border-indigo-500">
                            <h4 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300 mb-2">Editor Resources</h4>
                            <ul class="space-y-1 mt-2 text-gray-700 dark:text-gray-300">
                                <li>
                                    <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                        <span class="inline-block w-5">→</span> Writing Guidelines
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                        <span class="inline-block w-5">→</span> Formatting Tips
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                        <span class="inline-block w-5">→</span> SEO Best Practices
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
